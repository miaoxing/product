<?php if (wei()->setting('product.showIndexLink')) : ?>
  <a class="product-action-link text-body border-right" href="<?= $url('index') ?>">
    <div class="ni ni-home icon"></div>
  </a>
<?php endif ?>
<a class="product-action-link text-body" href="<?= $url('carts') ?>">
  <span class="js-product-cart-num product-cart-num badge badge-danger"></span>
  <i class="ni ni-cart"></i>
</a>

