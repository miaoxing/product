<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Category\Service\CategoryModel;
use Miaoxing\Logistics\Service\ShippingTplModel;
use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\ReqQueryTrait;
use Miaoxing\Plugin\Model\SoftDeleteTrait;
use Miaoxing\Product\Metadata\ProductTrait;
use Miaoxing\Seq\Model\SeqTrait;
use Wei\Event;
use Wei\Ret;
use Wei\Time;

/**
 * 商品模型
 *
 * @property ProductImageModel|ProductImageModel[] $images 商品图片
 * @property ProductDetailModel $detail 商品详情
 * @property ProductSpecModel $spec 商品规格
 * @property SkuModel|SkuModel[] $skus 商品 SKU
 * @property CategoryModel|CategoryModel[] $categories 商品分类
 * @property CategoriesProductModel|CategoriesProductModel[] $categoriesProducts 商品分类关联
 * @property ShippingTplModel $shippingTpl 运费模板
 */
class ProductModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use ProductTrait;
    use ReqQueryTrait;
    use SeqTrait;
    use SoftDeleteTrait;

    public const STATUS_NOT_STARTED = 1;

    public const STATUS_ON_SALE = 2;

    public const STATUS_ENDED = 3;

    public const STATUS_SOLD_OUT = 4;

    public const STATUS_DELISTED = 5;

    public const STATUS_DELETED = 6;

    /**
     * 下单减库存
     */
    public const DEC_STOCK_MODE_BUY = 1;

    /**
     * 脏腑减库存
     */
    public const DEC_STOCK_MODE_PAY = 2;

    protected $deleteStatusColumn = 'status';

    protected $columns = [
        'configs' => [
            'cast' => 'array',
            'default' => [],
        ],
    ];

    /**
     * 状态值的详细配置
     *
     * @var array
     */
    protected $statusConfigs = [
        self::STATUS_NOT_STARTED => [
            'name' => '抢购即将开始',
            'shortName' => '即将开始',
        ],
        self::STATUS_ON_SALE => [
        ],
        self::STATUS_ENDED => [
            'name' => '抢购结束',
            'shortName' => '已结束',
        ],
        self::STATUS_SOLD_OUT => [
            'name' => '商品已卖光了',
            'shortName' => '已售罄',
        ],
        self::STATUS_DELISTED => [
            'name' => '商品已下架',
            'shortName' => '已下架',
        ],
        self::STATUS_DELETED => [
            'name' => '商品已删除',
            'shortName' => '已删除',
        ],
    ];

    public function getGuarded(): array
    {
        return array_merge($this->guarded, [
            'price',
            'stockNum',
            'soldNum',
        ]);
    }

    /**
     * 商品图片
     *
     * @return ProductImageModel|ProductImageModel[]
     */
    public function images(): ProductImageModel
    {
        return $this->hasMany(ProductImageModel::class);
    }

    /**
     * 商品详情
     *
     * @return ProductDetailModel
     */
    public function detail(): ProductDetailModel
    {
        return $this->hasOne(ProductDetailModel::class);
    }

    /**
     * 商品规格
     *
     * @return ProductSpecModel
     */
    public function spec(): ProductSpecModel
    {
        return $this->hasOne(ProductSpecModel::class);
    }

    /**
     * 商品 SKU
     *
     * @return SkuModel|SkuModel[]
     */
    public function skus(): SkuModel
    {
        return $this->hasMany(SkuModel::class);
    }

    /**
     * 商品分类
     *
     * @return CategoryModel|CategoryModel[]
     */
    public function categories(): CategoryModel
    {
        return $this->belongsToMany(CategoryModel::class);
    }

    /**
     * 商品分类关联
     *
     * @return CategoriesProductModel|CategoriesProductModel[]
     */
    public function categoriesProducts(): CategoriesProductModel
    {
        return $this->hasMany(CategoriesProductModel::class);
    }

    /**
     * 运费模板
     *
     * @return ShippingTplModel
     */
    public function shippingTpl(): ShippingTplModel
    {
        return $this->belongsTo(ShippingTplModel::class);
    }

    /**
     * 获取状态配置
     *
     * @return array
     */
    public function getStatusConfigs(): array
    {
        foreach ($this->statusConfigs as $status => &$config) {
            $config['status'] = $status;
        }

        return $this->statusConfigs;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSave()
    {
        $this->isInList = $this->calIsInList();
    }

    /**
     * {@inheritDoc}
     */
    public function afterSave()
    {
        $this->clearTagCache();
//        $this->clearRecordCache();
    }

    /**
     * {@inheritDoc}
     */
    public function afterDestroy()
    {
        $this->clearTagCache();
//        $this->clearRecordCache();
    }

    /**
     * 计算出商品状态并保存
     *
     * @return $this
     */
    public function updateStatus(): self
    {
        $this->status = $this->calStatus();
        $this->save();
        return $this;
    }

    /**
     * 返回默认的规格配置
     *
     * @return array[][]
     * @svc
     */
    protected function getDefaultSpecs(): array
    {
        $spec = SpecModel::findByOrCreate(['name' => '默认']);
        $specValue = SpecValueModel::findByOrCreate(['specId' => $spec->id, 'name' => '默认']);
        return [
            $spec->toArray(['id', 'name']) + [
                'values' => [
                    $specValue->toArray(['id', 'name']),
                ],
            ],
        ];
    }

    /**
     * 计算出商品状态
     *
     * @return int
     * @experimental 可能改为保存前自动计算
     */
    public function calStatus(): int
    {
        if (!$this->isListing) {
            return static::STATUS_DELISTED;
        }

        if ($this->stockNum <= 0) {
            return static::STATUS_SOLD_OUT;
        }

        $now = Time::now();
        if ($this->startAt && $this->startAt > $now) {
            return static::STATUS_NOT_STARTED;
        }

        if ($this->endAt && $this->endAt < $now) {
            return static::STATUS_ENDED;
        }

        return static::STATUS_ON_SALE;
    }

    /**
     * 根据状态等计算是否显示在前台列表
     *
     * @return bool
     */
    protected function calIsInList(): bool
    {
        if (
            !$this->isListing
            || !$this->isHidden
            || $this->isDeleted()
        ) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDeleteStatusValue(): int
    {
        return static::STATUS_DELETED;
    }

    /**
     * {@inheritDoc}
     */
    protected function getRestoreStatusValue(): int
    {
        return $this->calStatus();
    }

    /**
     * @param string|int|array $categoryId
     * @return $this
     */
    public function withCategoryId($categoryId): self
    {
        $categoryIds = (array) $categoryId;
        $subCategories = CategoryModel::select('id')->where('parentId', $categoryId)->fetchAll();
        $categoryIds = array_merge($categoryIds, array_column($subCategories, 'id'));

        $this->selectMain()
            ->leftJoinRelation('categoriesProducts')
            ->where('categoriesProducts.categoryId', $categoryIds);

        return $this;
    }

    /**
     * 检查商品是否可以加入购物车和下单的相同操作
     *
     * @return Ret
     */
    public function checkBeforeCreateCartAndOrder(): Ret
    {
        if (!in_array($this->status, [static::STATUS_ON_SALE, static::STATUS_NOT_STARTED], true)) {
            $statusConfig = $this->getStatusConfigs()[$this->status];
            return err([
                'message' => $statusConfig['name'],
                'shortMessage' => $statusConfig['shortName'],
            ]);
        }

        $ret = Event::until('productCheckBeforeCreateCartAndOrder', [$this]);
        if ($ret) {
            return $ret;
        }

        return suc();
    }

    /**
     * 检查商品是否可以加入购物车
     *
     * @param Ret|null $createRet
     * @return Ret
     */
    public function checkCreateCart(Ret $createRet = null): Ret
    {
        $createRet || $createRet = $this->checkBeforeCreateCartAndOrder();
        if ($createRet->isErr()) {
            return $createRet;
        }

        if (!$this->isAllowAddCart) {
            return err('该商品不可加入购物车');
        }

        $ret = Event::until('productCheckCreateCart', [$this]);
        if ($ret) {
            return $ret;
        }

        return suc('可以加入购物车');
    }

    /**
     * 检查商品是否可以下单
     *
     * @param Ret|null $createRet
     * @return Ret
     */
    public function checkCreateOrder(Ret $createRet = null): Ret
    {
        $createRet || $createRet = $this->checkBeforeCreateCartAndOrder();
        if ($createRet->isErr()) {
            return $createRet;
        }

        if ($this->status === static::STATUS_NOT_STARTED) {
            $statusConfig = $this->getStatusConfigs()[$this->status];
            return err([
                'message' => $statusConfig['name'],
                'shortMessage' => $statusConfig['shortName'],
            ]);
        }

        $ret = Event::until('productCheckCreateOrder', [$this]);
        if ($ret) {
            return $ret;
        }

        return suc('可以购买');
    }

    /**
     * 检查商品是否可以加入购物车或下单
     *
     * 可用场景
     * 1. 商品详情，按需显示加入购物车或立即下单按钮
     * 2. SKU 选择器，按需显示加入购物车或立即下单按钮
     * 3. 商品列表，按需显示下单按钮，点击后再弹出选择窗口
     *
     * @return Ret
     */
    public function checkCreateCartOrOrder(): Ret
    {
        $create = $this->checkBeforeCreateCartAndOrder();
        $createCart = $this->checkCreateCart($create);
        $createOrder = $this->checkCreateOrder($create);

        if ($createCart->isErr() && $createOrder->isErr()) {
            // 如果两者都失败，暂时使用购物车的返回值
            $ret = err($createOrder['message'], $createCart['code']);
        } else {
            $ret = suc();
        }
        $ret['createCart'] = $createCart;
        $ret['createOrder'] = $createOrder;

        return $ret;
    }
}
