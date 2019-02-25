<script id="sku-selector-tpl" type="text/html">

  <% if (skus.length > 1) { %>
    <% for (var i in skuConfigs) { %>
    <div class="form-group">
      <label class="col-2 control-label" for="name">
        <%= skuConfigs[i].name %>
      </label>
      <div class="col-10">
        <ul class="sku-selectors">
          <% for (var j in skuConfigs[i].attrs) { %>
            <li class="price <% if (j == 0) { %> active <% } %>" data-id="<%= skuConfigs[i].attrs[j].id %>">
              <span><%= skuConfigs[i].attrs[j].value %></span>
            </li>
          <% } %>
        </ul>
      </div>
    </div>
    <% } %>
  <% } %>

  <div class="form-group">
    <label class="col-2 control-label" for="quantity">
      价格
    </label>

    <div class="col-6">
      <p class="form-control-plaintext text-warning">
        <small>￥</small>
        <span class="product-price-range"><%= skus[0].price %></span>
      </p>
    </div>
  </div>

  <div class="form-group">
    <label class="col-2 control-label" for="quantity">
      数量
    </label>

    <div class="col-10">
      <div class="input-group quantity-spinner float-left">
        <span class="input-group-prepend sub-quantity">
          <span class="input-group-text">
            <i class="fa fa-minus"></i>
          </span>
        </span>
        <input type="text" class="form-control text-center quantity" name="quantity" value="1">
        <span class="input-group-append add-quantity">
          <span class="input-group-text">
            <i class="fa fa-plus"></i>
          </span>
        </span>
      </div>
      <p class="form-control-plaintext sku-quantity-text float-left">
        库存<span class="sku-quantity"><%= skus[0].quantity %></span>件
      <p>
    </div>
  </div>

  <input type="hidden" class="skuId" name="skuId" value="<%= skus[0].id %>">

</script>
