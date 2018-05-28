<?php if (wei()->setting('product.showIndexLink')) : ?>
  <a class="product-action-link link-dark border-right" href="<?= $url('index') ?>">
    <div class="ni ni-home icon"></div>
  </a>
<?php endif ?>
<a class="product-action-link link-dark" href="<?= $url('carts') ?>">
  <span class="js-product-cart-num product-cart-num label label-danger"></span>
  <i class="ni ni-cart"></i>
</a>

