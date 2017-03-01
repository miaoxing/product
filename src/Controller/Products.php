<?php

namespace Miaoxing\Product\Controller;

use Miaoxing\Product\Service\Product;

class Products extends \miaoxing\plugin\BaseController
{
    protected $guestPages = ['products'];

    /**
     * 查看商品列表
     */
    public function indexAction($req)
    {
        $rows = 10;
        $page = $req['page'] > 0 ? (int) $req['page'] : 1;

        // 如果未设置分类,就是所有商品
        $category = wei()->category()->notDeleted()->findOrInitById($req['categoryId'], [
            'name' => '所有商品',
        ]);
        if ($category->isNew() && $listTpl = $this->setting('product.defaultListTpl')) {
            $category['listTpl'] = $listTpl;
        }

        $products = wei()->product()
            ->limit($rows)
            ->page($page)
            ->notDeleted()
            ->andWhere(['visible' => 1])
            ->andWhere(['listing' => 1]);

        // 指定栏目
        if (!$category->isNew()) {
            $products->andWhere(['categoryId' => $category->getChildrenIds()]);
        }

        // 搜索商品名称
        if ($req['q']) {
            $products->andWhere('name LIKE ?', '%' . $req['q'] . '%');
        }

        // 排序
        switch ($req['sort']) {
            case 'price':
                if (isset($req['order']) && $req['order'] == 'desc') {
                    $products->desc('price');
                } else {
                    $products->asc('price');
                }
                break;

            case 'soldQuantity':
                if (isset($req['order']) && $req['order'] == 'desc') {
                    $products->desc('soldQuantity');
                } else {
                    $products->asc('soldQuantity');
                }
                break;

            case 'discount':
                $products->asc('discount');
                break;

            case 'scores':
                $products->asc('scores');
                break;

            default:
                $products->desc('sort')->desc('id');
                break;
        }

        // 保留变量给视图
        $tags = $req['tags'] ? explode(',', $req['tags']) : [];

        // 触发查找前事件
        $this->event->trigger('preProductListFind', [$req, $products]);

        $products->findAll();

        // 触发查找后事件
        $this->event->trigger('postProductListFind', [$req, $products]);

        $data = [];
        /** @var \Miaoxing\Product\Service\Product $product */
        foreach ($products as $product) {
            $price = rtrim(rtrim($product['price'], '0'), '.');
            $data[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price,
                'originalPrice' => rtrim(rtrim($product['originalPrice'], '0'), '.'),
                'coverName' => $product->getCoverName(),
                'images' => $product['images'],
                'scores' => $product['scores'],
                'priceText' => $product->getPriceText($price),
            ];
        }

        $ret = [
            'data' => $data,
            'page' => $page,
            'rows' => $rows,
            'records' => $products->count(),
        ];

        //商品栏目轮转图
        $albums = null;
        if ($req['categoryId']) {
            $albumCategory = wei()->category()
                ->where(['binding' => $req['categoryId']])
                ->andWhere(['type' => 'photo'])
                ->notDeleted()
                ->desc('sort')
                ->find();

            if ($albumCategory) {
                $albums = wei()->album()->byClass($albumCategory['id'])->desc('sort')->enable()->findAll();
            }
        }

        switch ($req['_format']) {
            case 'json':
                return $this->ret($ret);

            default:
                // 设置视图标题
                if ($req['q']) {
                    $headerTitle = '搜索：' . wei()->e($req['q']);
                } else {
                    $headerTitle = $category['name'];
                }

                $this->pageConfig['displayHeader'] = false;
                $this->pageConfig['displayFooter'] = false;

                return get_defined_vars();
        }
    }

    public function showAction($req)
    {
        $product = wei()->product()->notDeleted()->findOneById($req['id']);

        $hidePrice = $this->setting('product.hidePrice');

        // 使用真实库存作为数量
        $product['quantity'] = $product->getStock();

        $packageData = $product->getPackageData();

        switch ($req['_format']) {
            case 'json':
                return $this->suc($packageData);
                break;

            default:
                $category = wei()->category()->findOrInitById($product['categoryId']);
                $categoryDetail = wei()->categoryDetail()->curApp()->findOrInit(['categoryId' => $product['categoryId'], 'showed' => 1]);
                if ($categoryDetail->isNew()) {
                    $categories = wei()->category()->where(['id' => $product['categoryId']])->getParents();
                    foreach ($categories as $category) {
                        $categoryDetail = wei()->categoryDetail()->curApp()->andWhere(['showed' => 1])->findOrInit(['categoryId' => $category['id']]);
                        if (!$categoryDetail->isNew()) {
                            break;
                        }
                    }
                }

                $payable = $product->checkViewPayable();
                $images = $product['images'];
                $account = wei()->wechatAccount->getCurrentAccount();
                $headerTitle = '商品详情';
                $htmlTitle = $product['name'];
                $scoreTitle = $this->setting('score.title', '积分');
                $this->pageConfig['displayFooter'] = false;

                return get_defined_vars();
        }
    }
}
