<?php

/**
 * @property    Miaoxing\Product\Service\CategoriesProductModel $categoriesProductModel
 * @method      Miaoxing\Product\Service\CategoriesProductModel categoriesProductModel() 返回当前对象
 */
class CategoriesProductModelMixin {
}

/**
 * @property    Miaoxing\Product\Service\ProductDetailModel $productDetailModel
 * @method      Miaoxing\Product\Service\ProductDetailModel productDetailModel() 返回当前对象
 */
class ProductDetailModelMixin {
}

/**
 * @property    Miaoxing\Product\Service\ProductImageModel $productImageModel 商品图片模型
 * @method      Miaoxing\Product\Service\ProductImageModel productImageModel() 返回当前对象
 */
class ProductImageModelMixin {
}

/**
 * @property    Miaoxing\Product\Service\ProductModel $productModel 商品模型
 * @method      Miaoxing\Product\Service\ProductModel productModel() 返回当前对象
 */
class ProductModelMixin {
}

/**
 * @property    Miaoxing\Product\Service\ProductSpecModel $productSpecModel 商品规格
 * @method      Miaoxing\Product\Service\ProductSpecModel productSpecModel() 返回当前对象
 */
class ProductSpecModelMixin {
}

/**
 * @property    Miaoxing\Product\Service\SkuModel $skuModel SKU
 * @method      Miaoxing\Product\Service\SkuModel skuModel() 返回当前对象
 */
class SkuModelMixin {
}

/**
 * @property    Miaoxing\Product\Service\SpecModel $specModel 规格
 * @method      Miaoxing\Product\Service\SpecModel specModel() 返回当前对象
 */
class SpecModelMixin {
}

/**
 * @property    Miaoxing\Product\Service\SpecValueModel $specValueModel 规格值
 * @method      Miaoxing\Product\Service\SpecValueModel specValueModel() 返回当前对象
 */
class SpecValueModelMixin {
}

/**
 * @mixin CategoriesProductModelMixin
 * @mixin ProductDetailModelMixin
 * @mixin ProductImageModelMixin
 * @mixin ProductModelMixin
 * @mixin ProductSpecModelMixin
 * @mixin SkuModelMixin
 * @mixin SpecModelMixin
 * @mixin SpecValueModelMixin
 */
class AutoCompletion {
}

/**
 * @return AutoCompletion
 */
function wei()
{
    return new AutoCompletion;
}

/** @var Miaoxing\Product\Service\CategoriesProductModel $categoriesProduct */
$categoriesProduct = wei()->categoriesProductModel;

/** @var Miaoxing\Product\Service\CategoriesProductModel|Miaoxing\Product\Service\CategoriesProductModel[] $categoriesProducts */
$categoriesProducts = wei()->categoriesProductModel();

/** @var Miaoxing\Product\Service\ProductDetailModel $productDetail */
$productDetail = wei()->productDetailModel;

/** @var Miaoxing\Product\Service\ProductDetailModel|Miaoxing\Product\Service\ProductDetailModel[] $productDetails */
$productDetails = wei()->productDetailModel();

/** @var Miaoxing\Product\Service\ProductImageModel $productImage */
$productImage = wei()->productImageModel;

/** @var Miaoxing\Product\Service\ProductImageModel|Miaoxing\Product\Service\ProductImageModel[] $productImages */
$productImages = wei()->productImageModel();

/** @var Miaoxing\Product\Service\ProductModel $product */
$product = wei()->productModel;

/** @var Miaoxing\Product\Service\ProductModel|Miaoxing\Product\Service\ProductModel[] $products */
$products = wei()->productModel();

/** @var Miaoxing\Product\Service\ProductSpecModel $productSpec */
$productSpec = wei()->productSpecModel;

/** @var Miaoxing\Product\Service\ProductSpecModel|Miaoxing\Product\Service\ProductSpecModel[] $productSpecs */
$productSpecs = wei()->productSpecModel();

/** @var Miaoxing\Product\Service\SkuModel $sku */
$sku = wei()->skuModel;

/** @var Miaoxing\Product\Service\SkuModel|Miaoxing\Product\Service\SkuModel[] $skus */
$skus = wei()->skuModel();

/** @var Miaoxing\Product\Service\SpecModel $spec */
$spec = wei()->specModel;

/** @var Miaoxing\Product\Service\SpecModel|Miaoxing\Product\Service\SpecModel[] $specs */
$specs = wei()->specModel();

/** @var Miaoxing\Product\Service\SpecValueModel $specValue */
$specValue = wei()->specValueModel;

/** @var Miaoxing\Product\Service\SpecValueModel|Miaoxing\Product\Service\SpecValueModel[] $specValues */
$specValues = wei()->specValueModel();
