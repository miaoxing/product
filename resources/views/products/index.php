<?php $view->layout() ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('comps/snapjs/snap.css') ?>">
<link rel="stylesheet" href="<?= $asset('plugins/product/css/products.css') ?>">
<?= $block->end() ?>

<div class="js-product-snap snap-content bg-muted">
  <?php require $view->getFile('product:products/nav.php') ?>
  <?php require $view->getFile('product:products/menu.php') ?>
  <?php require $view->getFile('product:products/tab.php') ?>
  <?php require $view->getFile('product:products/list.php') ?>
</div>

<?php require $view->getFile('product:products/footer.php') ?>
<?php require $view->getFile('product:products/drawer.php') ?>


