<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/product/css/admin/skuPicker.css') ?>">
<?= $block->end() ?>

<script type="text/html" id="skuPickerTpl">
  <div class="modal fade sku-picker-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">请选择商品</h4>
        </div>
          <div class="modal-body sku-picker-modal-body">
          <div class="well form-well sku-picker-well">
            <form class="form-inline js-sku-picker-form" role="form">
              <div class="form-group">
                <select class="form-control" name="categoryId" id="categoryId">
                  <option value="">全部栏目</option>
                </select>
              </div>

              <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="请输入名称搜索">
              </div>

              <div class="pull-right">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" class="js-sku-picker-view-selected" name="id" value=""> 查看已选
                  </label>
                </div>
                <div class="form-group">
                  <p class="form-control-static">已选 <span class="js-sku-picker-selected-num">0</span> 个商品</p>
                </div>
              </div>
            </form>
          </div>

          <table class="js-sku-picker-table sku-picker-table modal-table table-center table table-bordered table-hover">
            <thead>
            <tr>
              <th>名称</th>
              <th>规格</th>
              <th style="width:120px">库存</th>
              <th style="width:120px"><%= paramName || '数量' %></th>
              <th style="width:120px">操作</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
        </div>
      </div>
    </div>
  </div>
</script>

<script type="text/html" id="skuPickerActionsTpl">
  <a href="javascript:;" class="btn <%= selected ? 'selected btn-info' : 'btn-default' %> js-sku-picker-toggle" data-id="<%= id %>"><%= selected ? '取消' : '选择' %></a>
</script>

<script type="text/html" id="skuPickerQuantityTpl">
  <input type="text" class="form-control js-sku-picker-quantity sku-picker-quantity text-center" data-id="<%= id %>" value="<%= selectedQuantity %>">
</script>
