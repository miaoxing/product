<div class="js-product-drawers snap-drawers product-drawers bg-white display-none">
  <div class="snap-drawer snap-drawer-left category-drawer border-right">
    <form class="product-search-form" action="<?= $url->query('products') ?>" method="get">
      <div class="border-all border-radius">
        <input class="js-product-search product-search-input" name="q" value="<?= $e($req['q']) ?>" type="text"
          placeholder="请输入商品关键字搜索">
      </div>
      <button class="product-search-submit">
        <i class="text-muted ni ni-search"></i>
      </button>
    </form>

    <h3 class="product-drawer-title border-top-bottom bg-muted text-muted">
      商品栏目
      <a class="js-product-drawer-close float-right text-muted" href="javascript:">关闭</a>
    </h3>

    <?php $categories = wei()->category()->enabled()->notDeleted()->withParent('mall')->desc('sort')->findAll(); ?>
    <?php wei()->event->trigger('showCategory', [$categories]); ?>
    <?php if ($setting('products.categoryLevel') == '1') : ?>
      <ul class="category-list-sm">
        <li class="border-bottom">
          <a href="<?= $url('products') ?>">
            <h4 class="text-active-primary border-active-primary
            category-title <?= !$req['categoryId'] ? 'active' : '' ?>">全部</h4>
          </a>
        </li>
        <?php foreach ($categories as $category) : ?>
          <li class="border-bottom">
            <a href="<?= $url->query('products', ['categoryId' => $category['id']]) ?>">
              <h4 class="text-active-primary border-active-primary
                category-title <?= $req['categoryId'] == $category['id'] ? 'active' : '' ?>">
                <?= $category['name'] ?>
              </h4>
            </a>
          </li>
        <?php endforeach ?>
      </ul>
    <?php else : ?>
      <ul class="category-list-sm">
        <li class="border-bottom">
          <a href="<?= $url('products') ?>">
            <h4
              class="text-active-primary border-active-primary
              category-title <?= $req['categoryId'] ? '' : 'active' ?>">
              全部</h4>
          </a>
        </li>
      </ul>
      <?php foreach ($categories as $category) : ?>
        <h4
          class="js-category-title category-title border-active-primary text-active-primary border-bottom">
          <?= $category['name'] ?></h4>
        <ul class="category-children-list bg-muted">
          <li><a class="border-bottom" href="<?= $url->query('products', ['categoryId' => $category['id']]) ?>">全部</a>
          </li>
          <?php foreach ($category->getChildren()->enabled()->notDeleted() as $childCategory) : ?>
            <li>
              <a class="border-bottom"
                href="<?= $url->query('products', ['categoryId' => $childCategory['id']]) ?>">
                <?= $childCategory['name'] ?>
              </a>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endforeach ?>
    <?php endif ?>
  </div>

  <div class="snap-drawer snap-drawer-right filter-drawer border-left">
    <h3 class="product-drawer-title bg-muted text-muted">
      商品标签
      <a class="js-product-drawer-close float-right text-muted" href="javascript:">关闭</a>
    </h3>

    <?php require $view->getFile('@product-tag/tag/picker.php') ?>
  </div>
</div>
