<?php

namespace Miaoxing\Product\Service;

use miaoxing\plugin\BaseModel;

class Sku extends BaseModel
{
    protected $autoId = true;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var string
     */
    protected $cachePrefix = 'skuSpecs';

    /**
     * {@inheritdoc}
     */
    protected $data = [
        'attrIds' => [],
    ];

    public function getProduct()
    {
        $this->product || $this->product = wei()->product()->findOrInitById($this['productId']);

        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * 是否为下订单就减少库存
     *
     * @return bool
     */
    public function isSubAtOrder()
    {
        return $this->getProduct()->get('subAtPay') == 0;
    }

    /**
     * 是否为支付成功就减少库存
     *
     * @return bool
     */
    public function isSubAtPay()
    {
        return $this->getProduct()->get('subAtPay') == 1;
    }

    /**
     * 减库存
     * @param $quantity
     * @return bool
     */
    public function subQuantity($quantity)
    {
        $this->decr('quantity', (int) $quantity);
        $this->save();
    }

    /**
     * 软删除
     *
     * @todo 通过saveColl处理
     */
    public function destroy($conditions = false)
    {
        $this->andWhere($conditions);
        !$this->loaded && $this->loadData(0);

        if (!$this->isColl) {
            $this->triggerCallback('beforeDestroy');

            $this->softDelete();

            $this->isDestroyed = true;
            $this->triggerCallback('afterDestroy');
        } else {
            foreach ($this->data as $record) {
                $record->destroy();
            }
        }

        return $this;
    }

    /**
     * 获取当前SKU的规格信息
     *
     * @return array
     */
    public function getSpecs()
    {
        $specs = [];
        $attrs = $this->getProduct()->getSkuAttrs();
        foreach ($this['attrIds'] as $attrId) {
            $attr = $attrs[$attrId];
            $specs[$attr['name']] = $attr['value'];
        }

        return $specs;
    }

    /**
     * 从缓存获取当前SKU的规格信息
     *
     * @return array
     */
    public function getSpecsFromCache()
    {
        // 此处的缓存,在商品更新时清空
        return wei()->cache->get($this->cachePrefix . $this['id'], 86400, function () {
            return $this->getSpecs();
        });
    }

    /**
     * 移除缓存中当前SKU的规格信息
     */
    public function removeSpecsCache()
    {
        $this->cache->remove($this->cachePrefix . $this['id']);
    }

    /**
     * 检查当前SKU是否可购买
     *
     * @return array
     */
    public function checkPayable()
    {
        if ($this->isSoftDeleted()) {
            return $this->err('该商品规格已下架', -11);
        }

        if ($this['quantity'] <= 0) {
            return $this->err('该商品规格已售罄');
        }

        return $this->suc('可以购买');
    }

    public function afterFind()
    {
        parent::afterFind();
        $this['attrIds'] = (array) json_decode($this['attrIds']);
    }

    public function beforeSave()
    {
        parent::afterSave();
        $this['attrIds'] = json_encode((array) $this['attrIds']);
    }

    public function afterSave()
    {
        parent::afterSave();
        $this->clearTagCache();
        $this->clearRecordCache();
    }

    public function afterDestroy()
    {
        parent::afterDestroy();
        $this->clearTagCache();
        $this->clearRecordCache();
    }
}
