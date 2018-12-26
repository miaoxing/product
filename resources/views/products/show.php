<?php

$view->layout();
$unit = $product['config']['unit'] ?: '件';
?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/product/css/products.css') ?>">
<?= $block->end() ?>

<?php wei()->event->trigger('productPreShow', [$product]) ?>

<form id="productForm" class="product-container">
  <?php $event->trigger('preProductsShow', [$product]) ?>

  <?php require $view->getFile('@product/products/images.php') ?>

  <div class="product-info border-top-bottom">

    <h2 class="product-title"><?= $product['name'] ?></h2>

    <?php if (!$hidePrice) : ?>
      <div class="product-item product-item-price">
        <strong class="product-price text-primary">
          <?= $product->getPriceText($product->getPriceRange(), $product->getScoreRange()) ?>
        </strong>
        <?php if ($product['originalPrice'] != '0.00') : ?>
          <span class="product-original-price text-muted small">
            原价:￥<del><?= $product['originalPrice'] ?></del>
          </span>
        <?php endif ?>

        <?php if (!$setting('product.hideBondedTax', true) && $product['bonded']) : ?>
          <span class="product-bonded-tax">
          (单价：<?= sprintf('%.2f', $product['price'] - $product['config']['bondedTax']); ?>
            + 进口税：<?= sprintf('%.2f', $product['config']['bondedTax']) ?>)
        </span>
        <?php endif ?>
      </div>
    <?php endif ?>

    <div class="product-item border-top product-item-min">
      <?php if (!$setting('product.hideQuantity', false)) : ?>
        <dl>
          <dt>库存：</dt>
          <dd>
            <?php if ($product['config']['dailyReserveCount']) : ?>
              每天 <?= $product['config']['dailyReserveCount'], ' ', $unit ?>
            <?php else : ?>
              <?= $product['quantity'] ?>
            <?php endif ?>
          </dd>
        </dl>
      <?php endif ?>
      <?php if (!$setting('product.hideSoldQuantity', false)) : ?>
        <dl>
          <dt>销量：</dt>
          <dd>
            <?= $product['soldQuantity'] > 10 ? $product['soldQuantity'] . $unit : ' 10 ' . $unit . '以内' ?>
          </dd>
        </dl>
      <?php endif ?>
      <?php $event->trigger('productsShowItemMin', [$product]) ?>
    </div>

    <?php $event->trigger('productsShowItem', [$product]) ?>
  </div>

  <?php if (wei()->product->enableProps) { ?>
    <ul class="js-product-tabs nav tab-underline border-bottom m-t-sm header-tab m-b-0">
      <li class="active border-primary">
        <a class="text-active-primary" href="#detail-tab" data-toggle="tab">详情</a>
      </li>
      <li class="border-primary">
        <a class="text-active-primary" href="#props-tab" data-toggle="tab">参数</a>
      </li>
    </ul>
  <?php } else { ?>
    <div class="product-header border-top-bottom">商品详情</div>
  <?php } ?>
  <div class="tab-content">
    <div class="tab-pane fade in active" id="detail-tab">
      <div class="product-specs">
        <?php $event->trigger('productsShowSpecs', [$product]) ?>
      </div>
      <?php if ($product->getTags()->length() && $setting('products.showTag')) : ?>
        <div class="product-item">
          <?php foreach ($product->getTags() as $tag) : ?>
            <?php if ($tag['enable']) : ?>
              <span class="product-tag" style="background-color: <?= $tag['color'] ?: '#777' ?>"><?= $tag['name'] ?></span>
            <?php endif ?>
          <?php endforeach ?>
        </div>
      <?php endif ?>
      <div class="js-images-preview product-detail">
        <?= $product->getProcessedDetail() ?>
      </div>
    </div>
    <div class="tab-pane fade in" id="props-tab">
      <?= $product['props'] ?>
    </div>
  </div>

  <?php require $view->getFile('@product/products/show-footer-bar.php') ?>
</form>

<?php require $view->getFile('@product/products/picker.php') ?>
<?= $block->js() ?>
<script>
  require([
    'plugins/product/js/products',
    'comps/artTemplate/template.min',
    'comps/jquery_lazyload/jquery.lazyload.min',
    'assets/bsTab'
  ], function (products) {
    template.helper('$', $);
    products.showAction(<?= json_encode($packageData, JSON_UNESCAPED_UNICODE) ?>);
    $('.js-lazy').lazyload();

    $('.js-product-tabs a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
  });

  var cartNum = $('.product-cart-num');
  if (cartNum.length) {
    $.getJSON($.url('carts/count'), function (ret) {
      if (ret.count > 0) {
        cartNum.html(ret.count);
      }
    });
  }
</script>
<?= $block->end() ?>
<?php require $view->getFile('@wechat/wechat/_images-preview.php') ?>
