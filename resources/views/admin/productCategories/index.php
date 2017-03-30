<?php $view->layout() ?>

<div class="page-header">
  <a class="btn pull-right btn-success" href="<?= $url('admin/product-categories/new') ?>">添加栏目</a>

  <h1>
    微商城
    <small>
      <i class="fa fa-angle-double-right"></i>
      商品栏目管理
    </small>
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <div class="table-responsive">
      <div class="well form-well">
        <form class="form-inline" id="search-form" role="form">
          <div class="form-group">
            <input type="text" class="form-control" name="search" placeholder="请输入名称搜索">
          </div>
        </form>
      </div>
      <table id="category-table" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>名称</th>
          <th>简介</th>
          <th class="t-4">顺序</th>
          <th class="t-4">启用</th>
          <th class="t-12">操作</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <!-- /.table-responsive -->
    <!-- PAGE CONTENT ENDS -->
  </div>
  <!-- /col -->
</div>
<!-- /row -->

<script id="table-actions" type="text/html">
  <div class="action-buttons">
    <a href="<%= $.url('products', {categoryId: id}) %>" title="查看" target="_blank">
      <i class="fa fa-search-plus bigger-130"></i>
    </a>
    <a href="<%= $.url('admin/product-categories/edit', {id: id}) %>" title="编辑">
      <i class="fa fa-edit bigger-130"></i>
    </a>
    <% if(canDelete == 1) { %>
    <a class="text-danger delete-record" href="javascript:"
      data-href="<%= $.url('admin/product-categories/destroy', {id: id}) %>" title="删除">
      <i class="fa fa-trash-o bigger-130"></i>
    </a>
    <% } %>
  </div>
</script>

<?php require $view->getFile('admin:admin/checkboxCol.php') ?>

<?= $block('js') ?>
<script>
  require(['assets/apps/admin/category', 'dataTable', 'form', 'jquery-deparam'], function (category) {
    var recordTable = $('#category-table').dataTable({
      ajax: {
        url: $.url('admin/product-categories.json', {parentId: 'mall'})
      },
      columns: [
        {
          data: 'name',
          render: function (data, type, full) {
            return category.generatePrefix(full.level) + data;
          }
        },
        {
          data: 'description',
          render: function (data) {
            return data || '-';
          }
        },
        {
          data: 'sort',
          sClass: 'text-center'
        },
        {
          data: 'enable',
          sClass: 'text-center',
          render: function (data, type, full) {
            return template.render('checkbox-col-tpl', {
              id: full.id,
              name: 'enable',
              value: data
            });
          }
        },
        {
          data: 'id',
          sClass: 'text-center',
          render: function (data, type, full) {
            return template.render('table-actions', full);
          }
        }
      ]
    });

    // 切换状态
    recordTable.on('click', '.toggle-status', function () {
      var $this = $(this);
      var data = {};
      data['id'] = $this.data('id');
      data[$this.attr('name')] = +!$this.data('value');
      $.post($.url('admin/product-categories/update'), data, function (result) {
        $.msg(result);
        recordTable.reload();
      }, 'json');
    });

    recordTable.on('click', '.delete-record', function () {
      var $this = $(this);
      $.confirm('删除后将无法还原,确认删除?', function () {
        $.post($this.data('href'), function (result) {
          $.msg(result);
          recordTable.reload();
        }, 'json');
      });
    });

    $('#search-form').update(function () {
      recordTable.reload($(this).serialize(), false);
    });
  });
</script>
<?= $block->end() ?>
