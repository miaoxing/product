<div class="js-product-list product-container product-list product-list-<?= $category['listTpl'] ?: 'md' ?> bg-white">

</div>

<?php require $app->getControllerFile('_list-item') ?>

<?= $block->js() ?>
<script>
  require(['plugins/app/libs/artTemplate/template.min', 'plugins/app/libs/jquery-list/jquery-list'], function () {
    var $list = $('.js-product-list');
    $list.list({
      url: '<?= $url->query('products.json') ?>',
      template: template.compile($('.js-product-item-tpl').html()),
      localData: <?= json_encode($ret, JSON_UNESCAPED_UNICODE) ?>,
      $container: $('.js-product-snap'),
      $content: $list,
      scrollOffset: 40,
      page: <?= $page ?>,
      keepScrollPosition: true,
      beforeLoad: function (list, position) {
        if (position == 'top' && $('.product-list-item:first').data('list-page') == 1) {
          this.updateLoading('加载中,您已经在第一页^_^');
        }
      }
    });

    $list.on('click', '.js-product-list-item', function () {
      window.location = $(this).find('a:first').attr('href');
    });
  });
</script>
<?= $block->end() ?>
