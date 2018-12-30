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
            <span class="product-tag"
              style="background-color: <?= $tag['color'] ?: '#777' ?>"><?= $tag['name'] ?></span>
          <?php endif ?>
        <?php endforeach ?>
      </div>
    <?php endif ?>
    <div class="js-images-preview product-detail">
      <?= isset($productDetail) ? $productDetail : $product->getProcessedDetail() ?>
    </div>
  </div>
  <div class="tab-pane fade in" id="props-tab">
    <?= $product['props'] ?>
  </div>
</div>

<?= $block->js() ?>
<script>
  require([
    'comps/jquery_lazyload/jquery.lazyload.min',
    'assets/bsTab'
  ], function () {
    $('.js-lazy').lazyload();

    $('.js-product-tabs a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
  })
</script>
<?= $block->end() ?>