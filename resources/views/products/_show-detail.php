<?php if (wei()->product->enableProps) { ?>
  <ul class="js-product-tabs header-nav nav nav-underline mt-2 mb-0">
    <li class="nav-item active">
      <a class="nav-link text-active-primary border-active-primary" href="#detail-tab" data-toggle="tab">详情</a>
    </li>
    <?php if ($product['props']) { ?>
      <li class="nav-item">
        <a class="nav-link text-active-primary border-active-primary" href="#props-tab" data-toggle="tab">参数</a>
      </li>
    <?php } ?>
    <?php if ($product['config']['video']) { ?>
      <li class="nav-item">
        <a class="nav-link text-active-primary border-active-primary" href="#video-tab" data-toggle="tab">视频</a>
      </li>
    <?php } ?>
  </ul>
<?php } else { ?>
  <div class="product-header border-y">商品详情</div>
<?php } ?>
<div class="tab-content" style="margin-bottom: 44px">
  <div class="js-images-preview images-preview product-detail tab-pane fade show active" id="detail-tab">
    <div class="product-specs">
      <?php $event->trigger('productsShowSpecs', [$product]) ?>
    </div>
    <?= isset($productDetail) ? $productDetail : $product->getProcessedDetail() ?>
  </div>
  <?php if ($product['props']) { ?>
    <div class="tab-pane fade show js-images-preview product-detail" id="props-tab">
      <?= $product['props'] ?>
    </div>
  <?php } ?>
  <?php if ($product['config']['video']) { ?>
    <div class="tab-pane fade show" id="video-tab">
      <video class="js-video" width="100%" controls controlsList="nodownload">
        <source src="<?= $product['config']['video'] ?>" type="video/mp4"/>
      </video>
    </div>
  <?php } ?>
</div>

<?= $block->js() ?>
<script>
  require([
    'comps/jquery_lazyload/jquery.lazyload.min',
    'plugins/app/js/bootstrap-tab'
  ], function () {
    $('.js-lazy').lazyload();

    $('.js-product-tabs a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');

      if ($(this).attr('href') === '#video-tab') {
        $('.js-video')[0].play();
      }
    });
  })
</script>
<?= $block->end() ?>
