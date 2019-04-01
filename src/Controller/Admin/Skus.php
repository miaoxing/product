<?php

namespace Miaoxing\Product\Controller\Admin;

class Skus extends \Miaoxing\Plugin\BaseController
{
    public function indexAction($req)
    {
        switch ($req['_format']) {
            case 'json':
            default:
                $skus = wei()->sku()
                    ->select('sku.*')
                    ->leftJoin('product', 'product.id = sku.productId')
                    ->andWhere(['product.visible' => true]);

                // 分页
                $skus->limit($req['rows'])->page($req['page']);

                // 排序
                $skus->desc('sku.id');

                // 排除删除
                $skus->notDeleted();

                $skus->andWhere("sku.deleteTime = '0000-00-00 00:00:00'");

                if ($req['categoryId']) {
                    $skus->andWhere(['product.categoryId' => $req['categoryId']]);
                }

                if ($req['name']) {
                    $skus->andWhere('product.name LIKE ?', '%' . $req['name'] . '%');
                }

                // 筛选指定的编号
                if ($req['id']) {
                    $skus->andWhere(['sku.id' => explode(',', $req['id'])]);
                }

                $data = [];
                foreach ($skus as $sku) {
                    $product = $sku->getProduct();
                    if ($product->isSoftDeleted()) {
                        continue;
                    }

                    $data[] = $sku->toArray() + [
                            'product' => $product->toArray(),
                            'specs' => $sku->getSpecs(),
                        ];
                }

                return $this->suc([
                    'message' => '读取列表成功',
                    'data' => $data,
                    'page' => $req['page'],
                    'rows' => $req['rows'],
                    'records' => $skus->count(),
                ]);
        }
    }

    public function updateAction($req)
    {
        $sku = wei()->sku()->findOrInitById($req['id']);
        $sku->save($req);

        // 同时更新商品的数量
        $product = $sku->getProduct();
        $product->save([
            'quantity' => wei()->db->sum('sku', 'quantity',
                ['productId' => $product['id'], 'deleteTime' => '0000-00-00 00:00:00']),
        ]);

        return $this->suc();
    }
}
