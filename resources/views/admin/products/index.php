<?php

$view->layout();
$wei->page->addAsset('plugins/product/css/admin/products.css');
?>

<div class="page-header">
  <div class="pull-right">
    <div class="dropdown">
      <button data-toggle="dropdown" class="btn btn-success dropdown-toggle">
        添加商品
      </button>

      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item product-new-single-price" href="<?= $url('admin/products/new') ?>">单价格</a>
        <a class="dropdown-item product-new-multi-price" href="<?= $url('admin/products/new', ['template' => 'advanced']) ?>">多价格</a>
      </div>

      <?php if (wei()->setting('product.enableExport')) { ?>
        <a id="export-csv" class="btn btn-default pull-right" style="margin-left: 5px;" href="javascript:void(0);">导出</a>
      <?php } ?>
    </div>

    <?php if (wei()->setting('product.enableImport')) {
    ?>
      <form id="pro-upload-form" class="form-horizontal" method="post" role="form" style="display: inline-block;">
        <div class="excel-fileinput fileinput fileinput-new" data-provides="fileinput">
          <span class="btn btn-default btn-file">
            <span class="fileinput-new">从Excel导入</span>
            <span class="fileinput-exists">重新上传Excel</span>
              <input type="file" name="file">
          </span>
          <a href="<?= $asset('assets/admin/mall/product/商品批量导入模板.xls') ?>" class="btn btn-link">下载范例</a>
        </div>
      </form>
    <?php

} ?>
  </div>

  <h1>
    商品管理
  </h1>
</div>

<!-- /.page-header -->

<div class="row">
  <div class="col-12">
    <!-- PAGE CONTENT BEGINS -->
    <div class="table-responsive">
      <div class="well">
        <form class="form-inline" id="search-form" role="form">

          <div class="form-group form-group-category-id">
            <select class="form-control" name="categoryId" id="categoryId">
              <option value="">全部栏目</option>
            </select>
          </div>

          <div class="form-group">
            <select class="form-control" name="status">
              <?= wei()->product()->statusToOption(); ?>
            </select>
          </div>

          <div class="form-group">
            <input type="text" class="form-control" name="startTimeRange" id="startTimeRange"
              placeholder="请选择上架时间范围">
          </div>

          <div class="form-group">
            <input type="text" class="form-control" name="search" placeholder="请输入名称或货号搜索">
          </div>

          <?php $event->trigger('adminProductsSearchRender', [$req]) ?>
        </form>
      </div>
      <table id="record-table" class="record-table table table-bordered table-hover"></div>
    <!-- /.table-responsive -->
    <!-- PAGE CONTENT ENDS -->
  </div>
  <!-- /col -->
</div>
<!-- /row -->

<!-- 更新库存 -->
<div class="modal fade" id="quantity-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">更新库存</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="edit-quantity-form"
              action="<?= $wei->url('admin/orders/ship') ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div><!-- /.modal -->

<script id="edit-quantity" type="text/html">
  <% if (config.dailyReserveCount) { %>
    每天 <%= config.dailyReserveCount %>
  <% } else { %>
    <%= stock %>
    <a href="javascript:" class="edit-quantity" data-href="<%= $.url('admin/products/editQuantity', {id: id}) %>"
       title="编辑">
      <i class="fa fa-pencil"></i>
    </a>
  <% } %>
</script>
<script id="table-actions" type="text/html">
  <div class="action-buttons">
    <?php if ($wei->plugin->isInstalled('wechat-qrcode-product')) : ?>
      <a href="<%= $.url('admin/wechat-qrcode-products/new', {productIds: id}) %>" title="生成原生支付二维码">
        <i class="fa fa-qrcode bigger-130"></i>
      </a>
    <?php endif ?>
    <a href="<%= $.url('products/%s', id) %>" target="_blank" title="查看">
      <i class="fa fa-search-plus bigger-130"></i>
    </a>
    <a href="<%= $.url('admin/products/edit', {id: id}) %>" title="编辑">
      <i class="fa fa-edit bigger-130"></i>
    </a>
    <a class="text-danger delete-record" href="javascript:" data-href="<%= $.url('admin/products/delete', {id: id}) %>"
       title="删除">
      <i class="fa fa-trash-o bigger-130"></i>
    </a>
  </div>
</script>

<?php require $this->getFile('@product/admin/products/richInfo.php') ?>
<script id="listing-col-tpl" type="text/html">
  <label>
    <input class="js-toggle-status ace toggle-status" name="<%= name %>" data-id="<%= id %>" data-value="<%= value %>" type="checkbox" <% if (value != 0) { %> checked <% } %> >
    <span class="lbl"></span>
  </label>
</script>

<?= $block->js() ?>
<script>
  require(['form', 'dataTable', 'jquery-deparam', 'daterangepicker', 'plugins/excel/js/excel'], function (form) {
    var categoryJson = <?= json_encode(wei()->category()->notDeleted()->withParent('mall')->getTreeToArray()) ?>;
    form.toOptions($('#categoryId'), categoryJson, 'id', 'name');

    $('#search-form').loadParams().update(function () {
      recordTable.search($(this).serializeArray(), false);
    });

    var recordTable = $('#record-table').statefulDataTable({
      ajax: {
        url: $.queryUrl('admin/products.json')
      },
      columns: [
        {
          data: 'id',
          title: '名称',
          render: function (data, type, full) {
            return template.render('product-tpl', full);
          }
        },
        {
          data: 'categoryName',
          title: '栏目',
          sClass: 't-4 text-center'
        },
        {
          data: 'startTime',
          title: '上架时间~下架时间',
          sClass: 't-13 text-center',
          render: function (data, type, full) {
            return full.startTime.substr(0, 10) + ' ~ ' +
              full.endTime.substr(0, 10);
          }
        },
        {
          data: 'price',
          title: '价格',
          sClass: 't-4 text-center',
          render: function (data, type, full) {
            return '￥' + data + (full.scores != '0' ? '+' + full.scores + '积分' : '');
          }
        },
        {
          data: 'soldQuantity',
          title: '销量',
          sClass: 't-4'
        },
        {
          data: 'stock',
          title: '库存',
          sClass: 't-4',
          render: function (data, type, full) {
            return template.render('edit-quantity', full);
          }
        },
        {
          data: 'listing',
          title: '上架',
          sClass: 't-4',
          render: function (data, type, full) {
            return template.render('listing-col-tpl', {
              id: full.id + "_" + full.template,
              name: 'listing',
              value: data
            });
          }
        },

        <?php $event->trigger('adminProductListColumnRender', [$req]) ?>

        {
          data: 'sort',
          title: '顺序',
          sClass: 't-4',
        },
        {
          data: 'id',
          title: '操作',
          sClass: 't-12',
          render: function (data, type, full) {
            return template.render('table-actions', full)
          }
        }
      ]
    });

    recordTable.on('click', '.delete-record', function () {
      var $this = $(this);
      $.confirm('删除后将无法还原,确认删除?', function () {
        $.post($this.data('href'), function (result) {
          $.msg(result);
          recordTable.redraw();
        }, 'json');
      });
    });

    $('#startTimeRange').daterangepicker({}, function (start, end) {
      this.element.trigger('change');
    });

    // 更新库存
    recordTable.on('click', '.edit-quantity', function () {
      $('#edit-quantity-form').load($(this).data('href'), function () {
        $('#quantity-modal').modal('show');
      });
    });

    // 快速更新库存
    $('body').on('change', 'input.sku-quantity', function () {
      var input = $(this);
      var data = {};
      data.id = input.data('id');
      data[input.data('name')] = input.val();

      $.ajax({
        url: $.url('admin/skus/update'),
        dataType: 'json',
        data: data
      }).done(function (result) {
        $.msg(result);
        recordTable.redraw();
      });
    });

    // 切换上架状态
    recordTable.on('click', '.toggle-status', function () {
      var $this = $(this);
      var data = {};
      var arr = $this.data('id').split('_');
      data['id'] = arr[0];
      data['template'] = arr[1];
      data['listing'] = +!$this.data('value');
      $.post($.url('admin/products/update'), data, function (result) {
        $.msg(result);
        recordTable.redraw();
      }, 'json');
    });

    // 商品批量上传
    $('.excel-fileinput').on('change.bs.fileinput', function (event) {
      $('#pro-upload-form').uploadFile('admin/products/upload', 13, function (result) {
        if (result.code == 1) {
          window.location.href = '<?=wei()->url('admin/products')?>';
        } else {
          alert('批量上传失败！');
        }
      });
      $(this).fileinput('clear');
    });

    // 商品批量导出
    $('#export-csv').click(function () {
      var url = recordTable.fnSettings().ajax.url.replace('.json', '.csv');
      window.location = $.appendUrl(url, {page: 1, rows: 99999});
    });
  });
</script>
<?= $block->end() ?>
