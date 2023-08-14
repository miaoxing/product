<?php

use Miaoxing\Plugin\BasePage;
use Miaoxing\Product\Service\ProductModel;
use Miaoxing\Services\Page\CollGetTrait;
use Miaoxing\Services\Page\PostToPatchTrait;
use Miaoxing\Services\Service\IndexAction;

return new class () extends BasePage {
    use CollGetTrait;
    use PostToPatchTrait;

    public function get()
    {
        return IndexAction::new()
            ->beforeFind(function (ProductModel $models, $req) {
                $models->setDefaultSortColumn(['sort', 'id']);

                if (isset($req['search']['categoryId'])) {
                    $models->withCategoryId($req['search']['categoryId']);
                }
            })
            ->afterFind(function (ProductModel $models, $req) {
                // @experimental 指定编号排序
                if ('id' !== $req['sortField']) {
                    return;
                }

                $ids = (array) ($req['search']['id'] ?? []);
                if (!$ids) {
                    return;
                }

                $iterator = $models->getIterator();
                $iterator->uasort(function ($article1, $article2) use ($ids) {
                    $pos1 = array_search($article1->id, $ids, true);
                    $pos2 = array_search($article2->id, $ids, true);
                    return $pos1 - $pos2;
                });
                $models->fromArray($iterator);
            })
            ->exec($this);
    }
};
