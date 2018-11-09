<div class="js-product-list product-container product-list product-list-<?= $category['listTpl'] ?: 'md' ?> bg-light">

</div>

<script type="text/html" class="js-product-item-tpl">
  <div class="js-product-list-item product-list-item border-bottom" id="p<%= id %>">
    <a class="product-list-image"
      href="<%= $.url('products/%s', id, $.req('picker') ? <?= json_encode($req->getQueries()) ?> : {}) %>">
      <img class="product-list-thumb" src="<?= $asset->thumb('<%= images[0] %>', 750) ?>">
      <% if (coverName) { %>
        <span class="product-list-cover"><%= coverName %></span>
      <% } %>
    </a>

    <div class="product-list-detail">
      <div class="product-list-name truncate-2">
        <a href="<%= $.url('products/%s', id, $.req('picker') ? <?= json_encode($req->getQueries()) ?> : {}) %>">
          <%= name %>
        </a>
      </div>
      <?php if (!$setting('product.hidePrice')) : ?>
        <div class="product-list-price text-primary truncate">
          <%= priceText %>
          <% if (originalPrice != '0') { %>
            <del class="product-list-original-price text-muted small">￥<%= originalPrice %></del>
          <% } %>
        </div>
      <?php endif ?>
    </div>
  </div>
</script>

<?= $block->js() ?>
<script>
  require(['comps/artTemplate/template.min'], function () {
    template.helper('$', $);
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
