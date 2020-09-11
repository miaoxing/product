<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\ConfigTrait;
use Miaoxing\Plugin\BaseModel;
use Miaoxing\Product\Job\ProductSave;
use Miaoxing\ProductTag\Service\Tag;

/**
 * 配置
 * 1. hideQuantity 是否隐藏库存 默认为0
 *
 * @property bool $enableProps
 * @property bool $enableVideo
 * @property bool $enableListingExt
 */
class Product extends BaseModel
{
    use ConfigTrait;

    const STATUS_ON_SALE = 1;

    const STATUS_UNLISTED = 2;

    const STATUS_SOLD_OUT = 3;

    const STATUS_NOT_STARTED = 4;

    const STATUS_ENDED = 5;

    protected $autoId = true;

    protected $configs = [
        'enableProps' => [
            'default' => false,
        ],
        'enableVideo' => [
            'default' => false,
        ],
        'enableListingExt' => [
            'default' => false,
        ],
    ];

    /**
     * @var \Miaoxing\Category\Service\Category
     */
    protected $category;

    /**
     * @var Sku|Sku[]
     */
    protected $skus;

    /**
     * @var Tag|\Miaoxing\ProductTag\Service\Tag[]
     */
    protected $tags;

    /**
     * @var \Miaoxing\Logistics\Service\ShippingTpl
     */
    protected $shippingTpl;

    /**
     * @var string
     */
    protected $table = 'product';

    protected $data = [
        'isVirtual' => 0,
        'sort' => 50,
        'scores' => 0,
        'visible' => 1,
        'template' => 'common',
        'limitation' => 0,
        'allowCoupon' => 1,
        'subAtPay' => 1,
        'listing' => 1,
        'allowCashOnDelivery' => 0,
        'shippingTplId' => 0,
        'skuConfigs' => [],
        'config' => [],
        'detail' => '',
    ];

    protected $units = [
        '件',
        '杯',
        '只',
        '罐',
        '盒',
        '人',
        '个',
    ];

    protected $listTpls = [
        'md' => '中图,2个1行',
        'sm' => '小图,1个1行',
        'lg' => '大图,1个1行',
        'xs' => '小图,3个1行',
    ];

    /**
     * @var array
     */
    protected $statusConfigs = [
        // 在售
        1 => [
            'name' => false,
            'shortName' => false,
        ],
        2 => [
            'name' => '商品已下架',
            'shortName' => '已下架',
        ],
        3 => [
            'name' => '售罄，努力补货中',
            'shortName' => '售罄',
        ],
        4 => [
            'name' => '抢购即将开始',
            'shortName' => '即将开始',
        ],
        5 => [
            'name' => '抢购结束',
            'shortName' => '已结束',
        ],
    ];

    /**
     * 获取商品的状态
     *
     * @return int
     */
    public function getStatus()
    {
        if (!$this['listing']) {
            return static::STATUS_UNLISTED;
        }

        if ($this->getStock() <= 0) {
            return static::STATUS_SOLD_OUT;
        }

        if ($this->isWillStart()) {
            return static::STATUS_NOT_STARTED;
        }

        if ($this->isEnd()) {
            return static::STATUS_ENDED;
        }

        return static::STATUS_ON_SALE;
    }

    /**
     * @return array
     */
    public function getStatusConfigs()
    {
        foreach ($this->statusConfigs as $status => &$config) {
            $config['status'] = $status;
        }

        return $this->statusConfigs;
    }

    /**
     * @return array
     */
    public function getStatusConfig()
    {
        $status = $this->getStatus();

        return $this->statusConfigs[$status] + ['status' => $status];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this['images'] = (array) json_decode($this['images'], true);
        $this['config'] = (array) json_decode($this['config'], true);
        $this['skuConfigs'] = (array) json_decode($this['skuConfigs'], true);
        $this['startTime'] = ($this['startTime'] != '0000-00-00 00:00:00' ? $this['startTime'] : '');
        $this['endTime'] = ($this['endTime'] != '0000-00-00 00:00:00' ? $this['endTime'] : '');

        $this->event->trigger('postImageDataLoad', [&$this, ['images', 'detail']]);
    }

    public function beforeSave()
    {
        parent::beforeSave();

        $this->event->trigger('preImageDataSave', [&$this, ['images', 'detail']]);

        if (is_array($this['images'])) {
            $this['images'] = json_encode($this['images'], JSON_UNESCAPED_SLASHES);
        }

        if (is_array($this['config'])) {
            $this['config'] = json_encode($this['config'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $this['skuConfigs'] = json_encode($this['skuConfigs'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (!$this['startTime']) {
            $this['startTime'] = '0000-00-00 00:00:00';
        }

        if (!$this['endTime']) {
            $this['endTime'] = '0000-00-00 00:00:00';
        }
    }

    /**
     * Repo
     *
     * @return array
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Record: 获取当前商品的单位
     */
    public function getUnit()
    {
        if (isset($this['config']['unit']) && $this['config']['unit']) {
            return $this['config']['unit'];
        }

        return $this->units[0];
    }

    /**
     * 获取商品规格
     */
    public function getSkus()
    {
        $this->skus || $this->skus = wei()->sku()->notDeleted()->asc('sort')->findAll(['productId' => $this['id']]);

        return $this->skus;
    }

    /**
     * 获取商品中,所有规格中最贵的商品
     *
     * @return float
     */
    public function getMaxPrice()
    {
        $max = '0.00';
        foreach ($this->getSkus() as $sku) {
            if ($sku['price'] > $max) {
                $max = $sku['price'];
            }
        }

        return $max;
    }

    /**
     * 获取商品中,所有规格中积分最高的商品
     *
     * @return int
     */
    public function getMaxScore()
    {
        $max = '0';
        foreach ($this->getSkus() as $sku) {
            if ($sku['score'] > $max) {
                $max = $sku['score'];
            }
        }

        return $max;
    }

    /**
     * Record: 获取商品相关的标签
     */
    public function getTags()
    {
        if (!$this->tags) {
            $recordTags = $this->db('recordTag')->fetchAll(['recordTable' => 'product', 'recordId' => $this['id']]);
            $tagIds = [];
            foreach ($recordTags as $recordTag) {
                $tagIds[] = (int) $recordTag['tagId'];
            }
            $this->tags = wei()->tag()->beColl();
            if ($tagIds) {
                $this->tags->findAll(['id' => $tagIds]);
            }
        }

        return $this->tags;
    }

    public function getMinPrice()
    {
        return $this['price'];
    }

    public function getPriceRange()
    {
        $max = $this->getMaxPrice();
        $min = $this->getMinPrice();
        if ($max == $min) {
            return $max;
        } else {
            return $min . '~' . $max;
        }
    }

    public function getScoreRange()
    {
        $max = $this->getMaxScore();
        $min = $this['scores'];

        if ($max == $min) {
            return $max;
        } else {
            return $min . '~' . $max;
        }
    }

    /**
     * Record|Repo 生成价格文案
     *
     * @param null|string $price
     * @param null|string $scores
     * @return string
     */
    public function getPriceText($price = null, $scores = null)
    {
        if ($price == null) {
            $price = $this['price'];
        }

        if ($scores == null) {
            $scores = $this['scores'];
        }

        $text = '';

        // 1. 价格不为0,或者为0,但是没有积分时,显示价格
        if ($price != '0.00' || !$scores) {
            $text .= '￥' . $price;
        }

        // 2. 增加中间的连接符号
        if ($price != '0.00' && $scores) {
            $text .= ' + ';
        }

        // 3. 最后显示积分
        if ($scores) {
            $text .= $scores . wei()->setting('score.title', '积分');
        }

        return $text;
    }

    public function getCategory()
    {
        $this->category || $this->category = wei()->category()->findOrInitById($this['categoryId']);

        return $this->category;
    }

    public function getProductsByTag(Tag $tag, $limit = 0)
    {
        $products = wei()->product()
            ->select('DISTINCT product.*')
            ->leftJoin('recordTag', 'product.id = recordTag.recordId')
            ->andWhere("recordTag.recordTable = 'product'")
            ->andWhere(['recordTag.tagId' => $tag['id']])
            ->notDeleted()
            ->groupBy('product.id')
            ->desc('product.sort')
            ->desc('product.id');

        if ($limit) {
            $products->limit($limit);
        }

        $products->findAll();

        return $products;
    }

    public function getThumb()
    {
        return $this['images'][0];
    }

    /**
     * 获取库存,超卖显示为0
     *
     * @return int
     */
    public function getStock()
    {
        return $this['quantity'] < 0 ? 0 : $this['quantity'];
    }

    public function isSoldOut()
    {
        return $this['listing'] && ($this->isSoftDeleted() || $this['quantity'] == 0);
    }

    public function isWillStart()
    {
        return $this['listing'] && (!empty($this['startTime']) && strtotime($this['startTime']) > time());
    }

    public function isEnd()
    {
        return !$this['listing'] || (!empty($this['endTime']) && $this['endTime'] < date('Y-m-d H:i:s'));
    }

    /**
     * 是否显示购物车
     *
     * @return bool
     */
    public function isShowCart()
    {
        return !$this['isVirtual'] && !$this['config']['noShowCart'];
    }

    /**
     * 判断当前商品是否为单规格(单价格)
     *
     * @return bool
     */
    public function isSingleSku()
    {
        return count($this->getSkus()) == 1;
    }

    public function isSingle()
    {
        return count($this['skuConfigs']) === 1 && count($this['skuConfigs'][0]['attrs']) === 1;
    }

    /**
     * 获取当前商品第一个规格
     *
     * @return Sku
     */
    public function getFirstSku()
    {
        return $this->getSkus()->get(0);
    }

    /**
     * 获取运费模板
     *
     * @return \Miaoxing\Logistics\Service\ShippingTpl
     */
    public function getShippingTpl()
    {
        $this->shippingTpl || $this->shippingTpl = wei()->shippingTpl()->findOrInitById($this['shippingTplId']);

        return $this->shippingTpl;
    }

    /**
     * 检查商品,是否在指定的分类中
     *
     * @param int $categoryId
     * @return bool
     */
    public function inCategoryId($categoryId)
    {
        if ($this['categoryId'] == $categoryId) {
            return true;
        }

        $category = $this->getCategory();
        while ($category['parentId']) {
            $category = $category->getParent();
            if ($category['id'] == $categoryId) {
                return true;
            }
        }

        return false;
    }

    public function create($req)
    {
        // 1. 为单价格商品增加一个SKU
        if ($this->isNew() && !isset($req['skus'])) {
            $skuData = $this->initOneSkuData($req['price'], $req['quantity'], (int) $req['scores'], (string) $req['no']);
            $req['skus'] = $skuData['skus'];
            $req['skuConfigs'] = $skuData['skuConfigs'];
        }

        // 触发保存商品前回调
        $ret = wei()->event->until('preProductSave', [$this, $req]);
        if ($ret) {
            return $ret;
        }

        // 2. 保存商品数据
        $this->save($req);

        // 3. 如果商品只有一个SKU,将商品价格和更新到SKU
        $skus = $this->getSkus();
        if ($skus->length() == 1) {
            $skus[0]->save([
                'price' => $this['price'],
                'quantity' => $this['quantity'],
                'score' => (int) $this['scores'],
                'no' => $this['no'],
            ]);
        }

        // 4. 更新SKU的值
        if ($req['skus']) {
            $skus = $this->getSkus();
            $skus->saveColl($req['skus'], ['productId' => $this['id']]);
        }

        // 5. 更新标签
        if (isset($req['tags'])) {
            $tags = [];
            $recordTags = wei()->db('recordTag')->findAll(['recordId' => $this['id']]);
            $tagIds = array_column($recordTags->toArray(), 'id', 'tagId');
            foreach (explode(',', $req['tags']) as $tagId) {
                $tags[] = [
                    'id' => isset($tagIds[$tagId]) ? $tagIds[$tagId] : null,
                    'tagId' => $tagId,
                    'recordTable' => 'product',
                    'recordId' => $this['id'],
                ];
            }
            $recordTags->saveColl($tags);
        }

        $ret = wei()->event->until('postProductsUpdate', [$this, $req]);
        wei()->queue->push(ProductSave::class, ['id' => $this['id']]);
        if ($ret) {
            return $ret;
        }

        return $this->suc();
    }

    /**
     * 初始化单个SKU的数据
     *
     * @param float $price
     * @param int $quantity
     * @param int $score
     * @param string $no
     * @return array
     */
    protected function initOneSkuData($price, $quantity, $score = 0, $no = '')
    {
        $attrId = wei()->seq();
        $price = (float) $price;
        $quantity = (int) $quantity;

        $data = [];

        $data['skus'] = [
            [
                'price' => $price,
                'quantity' => $quantity,
                'score' => $score,
                'no' => $no,
                'attrIds' => [$attrId],
            ],
        ];

        $data['skuConfigs'] = [
            [
                'id' => wei()->seq(),
                'name' => '规格',
                'attrs' => [
                    [
                        'id' => $attrId,
                        'value' => $price,
                    ],
                ],
            ],
        ];

        return $data;
    }

    /**
     * 检查商品是否可购买
     *
     * @return array
     */
    public function checkPayable()
    {
        $statusConfig = $this->getStatusConfig();
        if ($statusConfig['status'] !== static::STATUS_ON_SALE) {
            return [
                'code' => -$statusConfig['status'],
                'message' => $statusConfig['name'],
                'shortMessage' => $statusConfig['shortName'],
            ];
        }

        $ret = wei()->event->until('productCheckPayable', [$this]);
        if ($ret) {
            return $ret;
        }

        return $this->suc('可以购买');
    }

    /**
     * 检查视图上能否展示购买相关的元素
     *
     * @return array
     */
    public function checkViewPayable()
    {
        $ret = $this->checkPayable();
        if ($ret['code'] !== 1) {
            return $ret;
        }

        $eventRet = wei()->event->until('productCheckViewPayable', [$this]);
        if ($eventRet) {
            return $eventRet;
        }

        return $ret;
    }

    /**
     * 获取封面名称
     *
     * @return string
     */
    public function getCoverName()
    {
        $config = $this->getStatusConfig();

        return $config['status'] == static::STATUS_ON_SALE ? '' : $config['shortName'];
    }

    /**
     * @param $index
     * @return string
     * @todo 待getStock升级后,才可合并到statusConfig
     */
    public function getStatusSql($index)
    {
        $sql = '';
        switch ($index) {
            case 1:
                $sql = 'startTime > NOW()';
                break;
            case 2:
                $sql = 'startTime <= NOW() and endTime >= NOW()';
                break;
            case 3:
                $sql = 'endTime < NOW()';
                break;
            case 4:
                $sql = 'quantity <= 0';
                break;
            case 5:
                $sql = 'listing = 1';
                break;
            case 6:
                $sql = 'listing = 0';
                break;
        }

        return $sql;
    }

    /**
     *  <option value="1">即将开始</option>
     *  <option value="2">在出售</option>
     *  <option value="3">已结束</option>
     *  <option value="4">售罄</option>
     *  <option value="5">上架</option>
     *  <option value="6">不上架</option>
     * @return string
     */
    public function statusToOption()
    {
        $status = [
            '' => '全部',
            '1' => '即将开始',
            '2' => '在出售',
            '3' => '已结束',
            '4' => '售罄',
            '5' => '上架',
            '6' => '不上架',
        ];
        $html = '';
        foreach ($status as $key => $value) {
            $html .= '<option value="' . $key . '">' . $value . '</option>';
        }

        return $html;
    }

    public function afterSave()
    {
        parent::afterSave();

        $this['images'] = (array) json_decode($this['images'], true);
        $this['config'] = (array) json_decode($this['config'], true);
        $this['skuConfigs'] = (array) json_decode($this['skuConfigs'], true);

        $this->clearTagCache();
        $this->clearRecordCache();
        $this->clearSkuSpecsCache();
    }

    public function afterDestroy()
    {
        parent::afterDestroy();
        $this->clearTagCache();
        $this->clearRecordCache();
        $this->clearSkuSpecsCache();
    }

    protected function clearSkuSpecsCache()
    {
        foreach ($this->getSkus() as $sku) {
            $sku->removeSpecsCache();
        }
    }

    /**
     * 获取处理过的详情内容
     *
     * 1. 替换图片为延迟加载
     *
     * @return string
     * @todo 改为filter来处理详情内容
     */
    public function getProcessedDetail()
    {
        return strtr($this['detail'], ['<img src="' => '<img class="js-lazy" data-original="']);
    }

    /**
     * 获取商品信息供直接输出展示
     *
     * @todo 通过toArray配置不输出createTime等数据
     */
    public function getPackageData()
    {
        wei()->event->trigger('preProductGetPackageData', [$this]);

        return [
            'data' => $this->toArray([
                'id',
                'name',
                'price',
                'scores',
                'images',
                'quantity',
                'config',
                'limitation',
                'isVirtual',
                'quantity',
                'reserveStartTime',
                'reserveEndTime',
                'skuConfigs',
            ]),
            'skus' => $this->getSkus()->toArray([
                'id',
                'quantity',
                'price',
                'score',
                'attrIds',
            ]),
        ];
    }

    public function getListTpls()
    {
        return $this->listTpls;
    }

    public function getListTplsOptions()
    {
        $data = [];
        foreach ($this->listTpls as $name => $value) {
            $data[] = ['name' => $name, 'value' => $value];
        }

        return $data;
    }

    /**
     * 获取属性编号和规格名称,属性值的对应列表
     *
     * @return array
     */
    public function getSkuAttrs()
    {
        $attrs = [];
        foreach ($this['skuConfigs'] as $skuConfig) {
            foreach ((array) $skuConfig['attrs'] as $attr) {
                $attrs[$attr['id']] = [
                    'name' => $skuConfig['name'],
                    'value' => $attr['value'],
                ];
            }
        }

        return $attrs;
    }

    /**
     * 设置配置字段的内容
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setConfig($name, $value)
    {
        $config = $this['config'];
        $config[$name] = $value;
        $this['config'] = $config;

        return $this;
    }
}
