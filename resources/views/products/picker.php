<script type="text/html" id="productModalTpl">
  <div class="js-product-picker product-picker modal-bottom modal fade" tabindex="-1" role="dialog"
    aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header border-bottom">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <div class="modal-title flex" id="productModalLabel">
            <div class="product-picker-thumb">
              <img src="<%= data.images[0] %>">
            </div>
            <div class="product-picker-base-info flex-grow-1">
              <h4 class="product-picker-title truncate-2"><%= data.name %></h4>
              <?php if (!$setting('product.hidePrice')) : ?>
                <strong class="js-product-price product-price text-primary">
                  <%= priceText %>
                </strong>
              <?php endif ?>
            </div>
          </div>
        </div>
        <div class="modal-body">
          <% if (skus.length > 1) { %>
          <div class="sku-item-list border-bottom">
            <% $.each(data.skuConfigs, function (i, skuConfig) { %>
            <dl class="sku-item clearfix">
              <dt class="sku-name"><%= skuConfig.name %></dt>
              <dd>
                <% $.each(skuConfig.attrs, function (j, attr) { %>
                  <label class="js-sku-attr sku-attr text-active-primary border-active-primary after-active-primary"
                    data-id="<%= attr.id %>">
                    <%= attr.value %>
                  </label>
                <% }) %>
              </dd>
            </dl>
            <% }) %>
          </div>
          <% } %>

          <dl class="js-quantity-item quantity-item flex flex-center">
            <dt class="quantity-name"><%= data.config.quantityName || '数量' %></dt>
            <dd class="quantity-spinner flex-grow-1">

              <span class="product-quantity-tips text-muted">
                <% if (data.limitation != '0') { %>
                  每人限购<%= data.limitation %><%= data.config.unit || '件' %>,
                <% } %>
                <?php if (!$setting('product.hideQuantity', false)) : ?>
                  剩下: <span class="js-quantity-left"><%= data.quantity %><%= data.config.unit || '件' %></span>
                <?php endif ?>
              </span>

              <div class="spinner">
                <button class="spinner-button spinner-minus" type="button"></button>
                <input type="text" class="spinner-input js-quantity" name="quantity" value="<%= quantity %>"
                  data-max-target=".js-quantity-left">
                <button class="spinner-button spinner-plus" type="button"></button>
              </div>

            </dd>
          </dl>
          <input type="hidden" class="js-sku-id" value="<%= skus[0].id %>" name="skuId">
        </div>

        <div class="js-product-actions modal-footer flex">
          <% if (action == 'updateCart') { %>
          <input type="hidden" class="js-cart-id" name="id" value="<%= cartId %>">
          <button class="js-cart-update product-picker-btn btn btn-primary flex-grow-1" type="button">确定</button>
          <% } else if (action == 'confirm') { %>
            <button class="js-<%= actionType %>-create product-picker-btn btn btn-primary flex-grow-1" type="button"
              data-type="<%= actionType %>">确定</button>
          <% } else { %>
          <% if (data.isVirtual == '0' && (typeof data.config.noShowCart == 'undefined' || data.config.noShowCart == '0'))
          { %>
          <button class="js-cart-create product-picker-btn btn btn-primary flex-grow-1"
            type="button"><?= $setting('product.titleAddToCart') ?: '加入购物车' ?></button>
          <% } %>
          <button class="js-order-create product-picker-btn btn btn-danger flex-grow-1"
            type="button"><?= $setting('product.titleBuyNow') ?: '立即购买' ?></button>
          <% } %>
        </div>
      </div>
    </div>
  </div>
</script>

<?php $event->trigger('productPickerRender') ?>
