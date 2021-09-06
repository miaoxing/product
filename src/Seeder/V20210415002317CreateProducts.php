<?php

namespace Miaoxing\Product\Seeder;

use Faker\Factory;
use Miaoxing\Category\Service\CategoryModel;
use Miaoxing\Plugin\Seeder\BaseSeeder;
use Miaoxing\Product\Service\Product;
use Miaoxing\Product\Service\ProductModel;

class V20210415002317CreateProducts extends BaseSeeder
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $faker = Factory::create('zh_CN');

        $categoryIds = array_column(CategoryModel::select('id')->fetchAll(), 'id');

        foreach (range(1, 30) as $i) {
            Product::create([
                'name' => $faker->words(3, true),
                'images' => [
                    [
                        'url' => $faker->imageUrl(640, 640),
                    ],
                ],
                'detail' => [
                    'content' => $faker->realTextBetween(),
                ],
                'categoriesProducts' => [
                    [
                        'categoryId' => $faker->randomElement($categoryIds),
                    ],
                ],
                'spec' => [
                    'specs' => ProductModel::getDefaultSpecs(),
                ],
                'skus' => [
                    [
                        'price' => $faker->randomFloat(2, 0, 100),
                        'stockNum' => $faker->biasedNumberBetween(),
                        'specValues' => [
                            [
                                'name' => '默认',
                                'specName' => '默认',
                            ],
                        ],
                    ],
                ],
            ]);
        }
    }
}
