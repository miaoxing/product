<div class="js-product-actions flex product-footer-bar footer-bar border-top">
  <?php require $view->getFile('@product/products/show-footer-links.php') ?>
  <?php require $view->getFile('@product/products/show-footer-actions.php') ?>
</div>

<?php if ($product['quantity'] > 0 && $product->isWillStart()) : ?>
  <?php require $view->getFile('product:products/show-countdown.php') ?>
<?php endif ?>
