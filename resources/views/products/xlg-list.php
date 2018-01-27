<div class="js-product-list product-container product-list product-list-xlg">

</div>

<script type="text/html" class="js-product-item-tpl">
  <div class="product-list-item border-bottom bg-light xlg-item">
    <a class="product-list-image xlg-image" href="<%= $.url('products/%s', id, $.req('picker') ? <?= json_encode($req->getQueries()) ?> : {}) %>">
      <img class="product-list-thumb xlg-thumb" src="<%= images[0] %>">
      <% if (coverName) { %>
      <span class="product-list-cover xlg-list-cover"><%= coverName %></span>
      <% } %>
    </a>
    <span class="xlg-price flex flex-center flex-y">
      <% if(originalPrice-price < 0) { %>
          <span class="xlg-price-new">
            ￥<%= price %>
          </span>
      <% } else { %>
        <div class="xlg-price-differ">
          立省￥<%= originalPrice-price %>
        </div>

        <span class="xlg-price-new xlg-price-new-border">
          ￥<%= price %>
        </span>

        <% if (originalPrice != '0') { %>
          <div class="xlg-price-old">
            <del>￥<%= originalPrice %></del>
          </div>
        <% } %>
      <% } %>
    </span>

    <div class="product-list-detail">
      <div class="product-list-name xlg-name">
        <a href="<%= $.url('products/%s', id) %>">
          <%= name %>
        </a>
      </div>
    </div>
  </div>
</script>

<?= $block->js() ?>
<script>
  require(['comps/artTemplate/template.min'], function () {
    template.helper('$', $);
    var $list = $('.js-product-list');
    var list = $list.list({
      url: '<?= $url->query('products.json') ?>',
      template: template.compile($('.js-product-item-tpl').html()),
      localData: <?= json_encode($ret, JSON_UNESCAPED_UNICODE) ?>,
      $container: $('.js-product-snap'),
      $content: $list,
      scrollOffset: 0
    });
  });
</script>
<?= $block->end() ?>
