<?php

use Miaoxing\Logistics\Service\ShippingTplModel;
use Miaoxing\Plugin\BaseController;
use Miaoxing\Product\Service\ProductModel;
use Miaoxing\Product\Service\SpecModel;
use Miaoxing\Product\Service\SpecValueModel;
use Miaoxing\Services\Page\ItemTrait;
use Wei\IsCallback;
use Wei\V;

return new class extends BaseController {
    use ItemTrait;

    protected $expand = [
        'images',
        'spec',
        'skus',
        'detail',
        'categoriesProducts',
    ];

    public function patch($req)
    {
        $product = ProductModel::findFromReq($req);

        $v = V::defaultOptional();
        $v->tinyChar('name', '名称')->required($product->isNew())->notBlank();
        $v->tinyChar('intro', '简短描述');
        $v->array('images', '图片', null, 9)->each(function (V $v) {
            $v->uBigInt('id', '编号')->optional();
            $v->tinyChar('url', '地址');
        });
        $v->array('categoriesProducts', '分类', null, 9)->each(function (V $v) {
            $v->uDefaultInt('id', '编号')->optional();
            $v->uDefaultInt('categoryId', '值')->modelExists(CategoryModel::class);
        });
        $v->array(['spec', 'specs'], '规格', 1, 3)->required($product->isNew())->each(function (V $v) {
            $v->char('name', '名称')->maxCharLength(5);
            $v->array('values', '值', 1, 10)->each(function (V $v) {
                $v->tinyChar('name', '名称');
            });
        });
        $v->array('skus', '规格明细')->required($product->isNew())->each(function (V $v) {
            $v->defaultOptional();
            $v->uBigInt('id', '编号');
            $v->uNumber('price', '价格', 10, 2)->required();
            $v->uNumber('marketPrice', '划线价', 10, 2);
            $v->uDefaultInt('score', '积分')->callback(function ($input, IsCallback $callback) {
                return $callback->getValidator()->getFieldData('price') > 0 || $input > 0;
            }, '%name%和价格至少有一个大于0');
            $v->uDefaultInt('stockNum', '库存')->required();
            $v->char('no', '货号')->maxCharLength(16);
            $v->uNumber('weight', '重量', 10, 3);
            $v->tinyChar('image', '图片');
            $v->array('specValues', '规格值')->required()->each(function (V $v) {
                $v->tinyChar('name', '名称');
                $v->char('specName', '规格名称')->maxCharLength(5);
            });
        });
        $v->uDefaultInt('shippingTplId', '运费模板')->modelExists(ShippingTplModel::class);
        $v->mediumText(['detail', 'content'], '描述');
        $v->bool('isListing', '是否上架');
        $v->dateTime('startAt', '上架开始时间')->allowEmpty();
        $v->dateTime('endAt', '上架结束时间')->allowEmpty()->gte($req['startAt']);
        $v->uSmallInt('maxOrderQuantity', '最大购买数量');
        $v->char(['configs', 'quantityName'], '"数量"名称', null, 4);
        $v->char(['configs', 'unit'], '单位', null, 2);
        $v->char(['configs', 'hideSoldNum'], '是否隐藏销量');
        $v->uTinyInt('decStockMode', '库存计数');
        $v->bool('isAllowAddCart', '是否可加入购物车');
        $v->bool('isAllowCoupon', '是否可使用优惠券');
        $v->bool('isRequireAddress', '是否需填写地址');
        $v->bool('isAllowComment', '下单时是否允许留言');
        $v->uSmallInt('sort', '顺序');

        $ret = $v->check($req);
        if ($ret->isErr()) {
            return $ret;
        }
        $data = $ret['data'];

        if ($data['skus'] ?? false) {
            $product->stockNum = $this->calSkusCountValue($data['skus'], 'stockNum');
            $product->soldNum = $this->calSkusCountValue($data['skus'], 'soldNum');
            $minPriceSku = $this->calSkuMinItem($data['skus'], 'price');
            $product->minPrice = $minPriceSku['price'] ?? 0;
            $product->minMarketPrice = $minPriceSku['marketPrice'] ?? 0;
            $product->minScore = $this->calSkusMinValue($data['skus'], 'score');
        }

        $product->status = $product->calStatus();

        if ($data['images'] ?? false) {
            $product->image = $data['images'][0]['url'];
        }

        // 同步 spec 到 sku
        if ($data['spec']['specs'] ?? false) {
            $specValueIds = [];
            foreach ($data['spec']['specs'] as $i => &$reqSpec) {
                $spec = SpecModel::findByOrCreate(['name' => $reqSpec['name']]);
                $reqSpec['id'] = $spec->id;
                foreach ($reqSpec['values'] as &$reqSpecValue) {
                    $specValue = SpecValueModel::findByOrCreate([
                        'specId' => $spec->id,
                        'name' => $reqSpecValue['name'],
                    ]);
                    $specValueIds[$spec->name][$specValue->name] = $specValue->id;
                    $reqSpecValue['id'] = $specValue->id;
                }
            }

            foreach ($data['skus'] as $i => $sku) {
                $data['skus'][$i]['specValueIds'] = [];
                foreach ($sku['specValues'] as $specValue) {
                    $data['skus'][$i]['specValueIds'][] = $specValueIds[$specValue['specName']][$specValue['name']];
                }
            }
        }

        $this->saveWithRelations($product, $data, ['spec', 'images', 'detail', 'skus', 'categoriesProducts']);

        return $product->toRet();
    }

    /**
     * 计算出 SKU 数组中项目的合计值
     *
     * @param array $skus
     * @param string $column
     * @return int
     */
    private function calSkusCountValue($skus, $column)
    {
        $count = 0;
        foreach ($skus as $sku) {
            $count += $sku[$column] ?? 0;
        }
        return $count;
    }

    /**
     * 计算出 SKU 数组中的最小值的项目
     *
     * @param array $skus
     * @param string $column
     * @return array
     */
    private function calSkuMinItem($skus, string $column)
    {
        $match = [];
        $min = PHP_INT_MAX;
        foreach ($skus as $sku) {
            $num = $sku[$column] ?? 0;
            if ($num < $min) {
                $min = $num;
                $match = $sku;
            }
        }
        return $match;
    }

    /**
     * 计算出 SKU 数组中项目的最小值
     *
     * @param array $skus
     * @param string $column
     * @return int
     */
    private function calSkusMinValue($skus, $column)
    {
        $min = PHP_INT_MAX;
        foreach ($skus as $sku) {
            $num = $sku[$column] ?? 0;
            if ($num < $min) {
                $min = $num;
            }
        }
        return $min;
    }

    /**
     * 保存模型和关联模型数据
     *
     * @param ProductModel $product
     * @param array|ArrayAccess $data
     * @param $relations
     * @return $this
     */
    private function saveWithRelations(ProductModel $product, $data, $relations)
    {
        $product->save($data);
        $this->saveRelations($product, $data, $relations);
        return $this;
    }

    /**
     * 保存关联模型的数据
     *
     * @param ProductModel $product
     * @param array $data
     * @param array $relations
     * @return $this
     */
    private function saveRelations(ProductModel $product, $data, array $relations)
    {
        foreach ($relations as $relation) {
            if (isset($data[$relation])) {
                $product->$relation()->saveRelation((array) $data[$relation]);
            }
        }
        return $this;
    }
};
