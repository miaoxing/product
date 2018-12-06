<script type="text/html" class="js-product-item-tpl">
  <div class="js-product-list-item product-list-item ui-border" id="p<%= id %>">
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
