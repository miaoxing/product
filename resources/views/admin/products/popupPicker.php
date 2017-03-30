<?php require_once $this->getFile('product:admin/products/richInfo.php') ?>

<script type="text/html" id="product-popup-picker-tpl">
  <% if (products.length !== 0) { %>
    <div class="js-product-popup-picker-products product-popup-picker-products">
      <% $.each(products, function(i, product) { %>
        <%== include('product-tpl', product) %>
        <input type="hidden" name="<%= inputName %>" value="<%= product.id %>">
      <% }) %>
    </div>
  <% } %>
  <a href="javascript:" class="js-product-popup-picker-select">
    <%= products.length === 0 ? selectName : changeName %>
  </a>
  <% if (products.length !== 0) { %>
    <a href="javascript:" class="js-product-popup-picker-clear"><%= clearName %></a>
  <% } %>
</script>

<script type="text/html" id="product-popup-picker-modal-tpl">
  <div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">请选择商品</h4>
        </div>
        <div class="modal-body p-a-0">
          <div class="well form-well product-popup-picker-well">
            <form class="js-product-popup-picker-form form-inline" role="form">
              <div class="form-group">
                <select class="js-product-popup-picker-category-id form-control" name="categoryId">
                  <option value="">全部栏目</option>
                </select>
              </div>

              <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="请输入名称搜索">
              </div>

              <div class="pull-right">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" class="js-product-popup-picker-view-selected" name="id" value=""> 查看已选
                  </label>
                </div>
                <div class="form-group">
                  <p class="form-control-static">
                    已选 <span class="js-product-popup-picker-selected-num">0</span> 个,
                    可选<span class="js-product-popup-picker-max-num">...</span>个
                  </p>
                </div>
              </div>
            </form>
          </div>

          <table class="js-product-popup-picker-table product-popup-picker-table table-center table table-bordered
          table-hover">
            <thead>
            <tr>
              <th>名称</th>
              <th>栏目</th>
              <th>价格</th>
              <th>库存</th>
              <th>操作</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="js-product-popup-picker-confirm btn btn-primary" data-dismiss="modal">确定</button>
        </div>
      </div>
    </div>
  </div>
</script>

<script type="text/html" id="product-popup-picker-actions-tpl">
  <a href="javascript:" class="js-product-popup-picker-toggle btn <%= selected ? 'selected btn-info' : 'btn-white' %>"
    data-id="<%= id %>"><%= selected ? '取消' : '选择' %></a>
</script>
