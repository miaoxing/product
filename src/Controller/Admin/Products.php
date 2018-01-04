<?php

namespace Miaoxing\Product\Controller\Admin;

class Products extends \Miaoxing\Plugin\BaseController
{
    protected $controllerName = '商品管理';

    protected $actionPermissions = [
        'index' => '列表',
        'new,create' => '添加',
        'edit,update' => '编辑',
        'destroy' => '删除',
        'editQuantity' => '编辑库存',
        'editCategoryDetail,updateCategoryDetail' => '编辑栏目详情',
    ];

    public function indexAction($req)
    {
        switch ($req['_format']) {
            case 'csv':
                return $this->renderCsv($req);

            case 'json':
                wei()->statsD->startTiming('admin.product.index');

                $products = wei()->product();

                // 分页
                $products->limit($req['rows'])->page($req['page']);

                // 排序
                $products->desc('sort')->desc('id');

                // 排除删除
                $products->notDeleted();

                if (!isset($req['with_invisible'])) {
                    $products->andWhere(['visible' => 1]);
                }

                // 搜索
                if ($req['search']) {
                    $products->andWhere('name LIKE ? OR no LIKE ?', [
                        '%' . $req['search'] . '%',
                        '%' . $req['search'] . '%',
                    ]);
                }

                // 分类筛选
                if ($req['categoryId']) {
                    $category = wei()->category()->notDeleted()->findOrInitById($req['categoryId']);
                    $products->andWhere(['categoryId' => $category->getChildrenIds()]);
                }

                // 是否上架
                if ($req['status']) {
                    $products->andWhere($products->getStatusSql($req['status']));
                }

                // 上线时间筛选
                if ($req['startTimeRange']) {
                    $ranges = explode('~', strtr($req['startTimeRange'], '.', '-'));
                    $ranges[0] = date('Y-m-d', strtotime($ranges[0]));
                    $ranges[1] = date('Y-m-d', strtotime($ranges[1])) . ' 23:59:59';
                    $products->andWhere('startTime BETWEEN ? AND ?', [$ranges[0], $ranges[1]]);
                }

                // 筛选指定的编号
                if ($req['id']) {
                    $products->andWhere(['id' => explode(',', $req['id'])]);
                }

                $this->event->trigger('preAdminProductListFind', [$products, $req]);

                $data = [];
                foreach ($products as $product) {
                    $data[] = $product->toArray() + [
                            'skus' => $product->getSkus()->toArray(),
                            'stock' => $product->getStock(),
                            'categoryName' => $product->getCategory()->get('name'),
                        ];
                }

                $this->event->trigger('postAdminProductListFind', [&$data, $req]);

                wei()->statsD->endTiming('admin.product.index');

                return $this->suc([
                    'message' => '读取列表成功',
                    'data' => $data,
                    'page' => $req['page'],
                    'rows' => $req['rows'],
                    'records' => $products->count(),
                ]);

            default:
                return get_defined_vars();
        }
    }

    /**
     * 导出商品表格，用于线下标签打印
     * @param $req
     * @return \Wei\Response
     * @throws \Exception
     */
    public function renderCsv($req)
    {
        $products = wei()->product();
        $products->limit($req['rows'])->page($req['page']);
        $products->desc('sort')->desc('id');
        $products->notDeleted()->andWhere(['visible' => 1]);

        // 分类筛选
        if ($req['categoryId']) {
            $category = wei()->category()->notDeleted()->findOrInitById($req['categoryId']);
            $products->andWhere(['categoryId' => $category->getChildrenIds()]);
        }

        $data = [];
        $data[0] = ['产品ID', '货号', '品名', '规格', '单位', '类别', '零售价', '产地', '链接'];

        foreach ($products as $product) {
            $product['countryId'] <= 0 ? $country = '' : $country = wei()->country()->findById($product['countryId']);

            // TODO 应该是getSkus获取所有规格
            foreach ($product['skuConfigs'] as $skuConfig) {
                foreach ($skuConfig['attrs'] as $skuAttr) {
                    $data[] = [
                        $product['id'],
                        $product['no'],
                        $product['name'],
                        is_numeric($skuAttr['value']) ? '' : $skuAttr['value'],
                        1,
                        $product->getCategory()->get('name'),
                        $product['price'],
                        $country ? $country['name'] : '',
                        $this->url->full('products/' . $product['id']),
                    ];
                }
            }
        }

        return wei()->csvExporter->export('products', $data);
    }

    public function newAction($req)
    {
        return $this->editAction($req);
    }

    public function editAction($req)
    {
        $product = wei()->product()->findOrInitById($req['id'], [
            'template' => $this->getTemplate(),
        ]);

        $skus = $product->getSkus();
        $tags = $product->getTags();

        return get_defined_vars();
    }

    /**
     * 单独编辑库存
     */
    public function editQuantityAction($req)
    {
        return $this->editAction($req);
    }

    public function createAction($req)
    {
        return $this->updateAction($req);
    }

    public function updateAction($req)
    {
        // 初始化模板
        $req['template'] = $this->getTemplate();

        $product = wei()->product()->findOrInitById($req['id']);

        $result = $product->create($req);

        if ($result['code'] < 1) {
            return $this->ret($result);
        } else {
            return $this->suc(['data' => $product->toArray()]);
        }
    }

    public function deleteAction($req)
    {
        $product = wei()->product()->findOneById($req['id']);

        $product->softDelete();

        return $this->suc();
    }

    protected function getTemplate()
    {
        $template = $this->request('template', 'common');

        if (!in_array($template, ['common', 'advanced'])) {
            throw new \Exception('商品模板不存在', 404);
        }

        return $template;
    }

    /**
     * 批量上传
     * @param $req
     * @return $this
     */
    public function uploadAction($req)
    {
        foreach ((array) $req['data'] as $key => $product) {
            $pro = wei()->product()->findOrInit(['no' => $product[1]]);
            $isUpdate = !$pro->isNew();
            $attrId = $isUpdate ? $pro->getSkus()[0]['attrIds'][0] : wei()->seq();
            $data = [
                'id' => $isUpdate ? $pro['id'] : wei()->seq(),
                'no' => $product[1],
                'name' => $product[3],
                'categoryId' => wei()->category->getCategoryIdByName($product[6]),
                'price' => $product[7],
                'originalPrice' => $product[8],
                'quantity' => $product[10],
//                'supplierId' => wei()->supplier->getSupplierIdByName($product[12]),
                'startTime' => date('Y-m-d H:i:s', time() + 8640000),
                'endTime' => date('Y-m-d H:i:s', time() + 86400000),
                'images' => $isUpdate ? $pro['images'] : [],
                'skus' => [
                    [
                        'id' => $isUpdate ? $pro->getSkus()[0]['id'] : wei()->seq(),
                        'price' => $product[7],
                        'quantity' => $product[10],
                        'attrIds' => [$attrId],
                    ],
                ],
                'skuConfigs' => [
                    [
                        'id' => $isUpdate ? $pro['skuConfigs'][0]['id'] : wei()->seq(),
                        'name' => '规格',
                        'attrs' => [
                            [
                                'id' => $attrId,
                                'value' => $product[4],
                            ],
                        ],
                    ],
                ],
            ];
            $pro->create($data);
        }

        return $this->suc();
    }

    /**
     * 编辑栏目详情
     */
    public function editCategoryDetailAction($req)
    {
        $categoryDetail = wei()->categoryDetail()->curApp()->findOrInit(['categoryId' => $req['categoryId']]);
        $category = wei()->category()->notDeleted()->find(['id' => $req['categoryId']]);

        return get_defined_vars();
    }

    public function updateCategoryDetailAction($req)
    {
        if (!$req['categoryId']) {
            return $this->err('请提供栏目Id！');
        }
        $categoryDetail = wei()->categoryDetail()->curApp()->findOrInit(['categoryId' => $req['categoryId']]);
        $categoryDetail->save($req);

        return $this->suc();
    }
}
