<style>
  .ui-border:before {
    content: "";
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    border: 1px solid #e9e9e9;
    -webkit-transform-origin: 0 0;
    padding: 1px;
    -webkit-box-sizing: border-box;
    pointer-events: none;
    z-index: 10;
    pointer-events: none
  }

  @media screen and (-webkit-min-device-pixel-ratio: 2) {
    .ui-border:before {
      width: 200%;
      height: 200%;
      -webkit-transform: scale(0.5)
    }
  }

  @media screen and (-webkit-min-device-pixel-ratio: 3) {
    .ui-border:before {
      width: 300%;
      height: 300%;
      -webkit-transform: scale(0.3333)
    }
  }
</style>

<div class="js-product-list product-container product-list product-list3-<?= $category['listTpl'] ?: 'md' ?> bg-light">

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
