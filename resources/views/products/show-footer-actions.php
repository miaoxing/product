<?php if ($payable['code'] !== 1) : ?>
  <?php if (isset($payable['link'])) : ?>
    <a class="btn btn-primary" href="<?= $payable['link'] ?>"><?= $payable['shortMessage'] ?></a>
  <?php else : ?>
    <button class="js-product-action-disabled btn btn-default disabled border-left" type="button">
      <?= $payable['shortMessage'] ?>
    </button>
  <?php endif ?>
<?php else : ?>
  <?php if ($product->isShowCart()) : ?>
    <button class="js-picker-show btn btn-primary" type="button" data-type="cart">
      <?= $setting('product.titleAddToCart') ?: '加入购物车' ?>
    </button>
  <?php endif ?>
  <button class="js-picker-show btn btn-danger" type="button"
    data-type="order"><?= $setting('product.titleBuyNow') ?: '立即购买' ?></button>
<?php endif ?>
