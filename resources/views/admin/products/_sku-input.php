<!-- 商品规格选择器 -->
<script id="sku-form-group-tpl" type="text/html">
  <div class="form-group sku-control" id="sku-control-<%= id %>">
    <input type="text" class="sku-name" placeholder="规格名称"/>

    <p class="form-control-static pull-left">：</p>
    <input type="text" class="sku-attrs" placeholder="请选择或输入规格">

    <p class="form-control-static">
      &nbsp;<a href="javascript:" class="delete-sku">删除</a>
    </p>
  </div>
</script>

<!-- 商品规格表格 -->
<script id="sku-table-tpl" type="text/html">
  <thead>
  <tr>
    <% $.each(skuConfigs, function (i, skuConfig) { %>
    <th><%= skuConfig.name %></th>
    <% }) %>
    <th>库存</th>
    <th>价格</th>
    <th class="<%= showNo ? '' : 'display-none' %>">货号</th>
    <th>销量</th>
  </tr>
  </thead>
  <tbody>

  <% if (specs.length === 0) { %>
  <tr>
    <td colspan="6" class="table-empty-tips">请先输入规格</td>
  </tr>
  <% } %>

  <% $.each(specs, function (i, row) { %>
  <tr>
    <% $.each(row, function (j, item) { %>
    <td rowspan="<%= item[1] %>"><%= item[0] %></td>
    <% }) %>
    <td>
      <input type="text" name="skus[<%= i %>][quantity]" class="sku-quantity"
        value="<%= skus[i].quantity %>" data-rule-required="true" data-rule-digits="true">
    </td>
    <td>
      <input type="text" name="skus[<%= i %>][price]" class="sku-price"
        value="<%= skus[i].price %>" data-rule-required="true" data-rule-number="true" data-rule-min="0">
    </td>
    <td class="<%= showNo ? '' : 'display-none' %>">
      <input type="text" name="skus[<%= i %>][no]" class="sku-no" value="<%= skus[i].no %>">
      <input type="hidden" name="skus[<%= i %>][id]" class="sku-id" value="<%= skus[i].id %>">
      <% $.each(skus[i].attrIds, function (k, attrId) { %>
      <input type="hidden" name="skus[<%= i %>][attrIds][]" class="sku-attrs-id" value="<%= attrId %>">
      <% }) %>
    </td>
    <td>
      <%= skus[i].soldQuantity || 0 %>
    </td>
  </tr>
  <% }) %>
  </tbody>
</script>
