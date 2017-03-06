<?php $view->layout(); ?>

<?= $block('css') ?>
<link rel="stylesheet" href="<?= $asset('plugins/product/css/products.css') ?>">
<?= $block->end() ?>

<?php wei()->event->trigger('productPreShow', [$product]) ?>

<form id="productForm" class="product-container">
  <?php $event->trigger('preProductsShow', [$product]) ?>

  <?php require $view->getFile('product:products/images.php') ?>

  <div class="product-info border-top-bottom">

    <h2 class="product-title"><?= $product['name'] ?></h2>

    <?php if (!$hidePrice) : ?>
      <div class="product-item product-item-price">
        <strong class="product-price text-primary">
          <?= $product->getPriceText($product->getPriceRange()) ?>
        </strong>
        <?php if ($product['originalPrice'] != '0.00') : ?>
          <del class="product-original-price text-muted small">
            原价:￥<?= $product['originalPrice'] ?>
          </del>
        <?php endif ?>

        <?php if (!$setting('product.hideBondedTax', true) && $product['bonded']) : ?>
        <span class="product-bonded-tax">
          (单价：<?= sprintf('%.2f', $product['price'] - $product['config']['bondedTax']); ?> + 进口税：<?= sprintf('%.2f', $product['config']['bondedTax']) ?>)
        </span>
        <?php endif ?>
      </div>
    <?php endif ?>

    <?php if (!$setting('product.hideQuantity', false)) : ?>
      <dl class="product-item border-top">
        <dt>库存：</dt>
        <dd><?= $product['quantity'] ?></dd>
      </dl>
    <?php endif ?>

    <?php if (!$setting('product.hideSoldQuantity', false)) : ?>
    <dl class="product-item border-top">
      <dt>销量：</dt>
      <dd>
        <?= $product['soldQuantity'] > 10 ? $product['soldQuantity'] . ($product['config']['unit'] ?: '件') : '10' . ($product['config']['unit'] ?: '件') . '以内' ?>
      </dd>
    </dl>
    <?php endif ?>

    <?php if ($product['maxPayScores']) : ?>
      <dl class="product-item border-top">
        <dt><?= $scoreTitle ?>：</dt>
        <dd>可用<?= $product['maxPayScores'], $scoreTitle ?>抵用现金<?= $product->getPayScoresMoney() ?>元</dd>
      </dl>
    <?php endif ?>

    <?php $event->trigger('productsShowItem', [$product]) ?>
  </div>

  <div class="product-header border-top-bottom">商品详情</div>

  <div class="product-specs">
    <?php $event->trigger('productsShowSpecs', [$product]) ?>
  </div>

  <?php if ($product->getTags()->length() && $setting('products.showTag')) : ?>
    <div class="product-item">
      <?php foreach ($product->getTags() as $tag) : ?>
        <span class="product-tag" style="background-color: <?= $tag['color'] ?>"><?= $tag['name'] ?></span>
      <?php endforeach ?>
    </div>
  <?php endif ?>

  <div class="js-images-preview product-detail">
    <?php if ($categoryDetail['detailUp']) : ?>
      <?= $categoryDetail['detailUp'] ?>
    <?php endif ?>

    <?= $product->getProcessedDetail() ?>

    <?php if ($categoryDetail['detailDown']) : ?>
      <?= $categoryDetail['detailDown'] ?>
    <?php endif ?>
  </div>

  <?php require $view->getFile('product:products/show-footer-bar.php') ?>
</form>

<?php require $view->getFile('product:products/picker.php') ?>
<?= $block('js') ?>
<script>
  require(['plugins/product/js/products', 'comps/artTemplate/template.min'], function (products) {
    template.helper('$', $);
    products.showAction(<?= json_encode($packageData, JSON_UNESCAPED_UNICODE) ?>);
  });

  require(['plugins/wechat/js/wx'], function (wx) {
    wx.load(function () {
      $('.js-images-preview img').click(function () {
        var urls = $(this).closest('.js-images-preview').find('img').map(function () {
          return this.src;
        }).get();
        wx.previewImage({
          current: $(this).attr('src'),
          urls: urls
        });
      });
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
