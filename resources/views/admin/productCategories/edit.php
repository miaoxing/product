<?php $view->layout() ?>

<div class="page-header">
  <?php if (isset($category) && $category['id']) {
    ?>
    <div class="pull-right">
      <a class="btn btn-success" href="<?= $url('admin/products/editCategoryDetail?categoryId='.$category['id'])?>">添加栏目详情</a>
      <a class="btn btn-success " href="<?= $url('admin/albumCategory/edit?binding='.$category['id'])?>">添加栏目图片</a>
    </div>
  <?php

} ?>
  <h1>
    微商城
    <small>
      <i class="fa fa-angle-double-right"></i>
      商城栏目管理
    </small>
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <form id="category-form" class="form-horizontal" method="post" role="form">

      <div class="form-group">
        <label class="col-lg-2 control-label" for="parentId">
          所属栏目
        </label>

        <div class="col-lg-4">
          <select name="parentId" id="parentId" class="form-control">
            <option value="mall">根栏目</option>
          </select>
        </div>

        <label class="col-lg-6 help-text" for="sort">
          三级栏目别超过3个.
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="name">
          <span class="text-warning">*</span>
          名称
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="name" id="name" data-rule-required="true">
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="listTpl">
          列表模版
        </label>

        <div class="col-lg-4">
          <select name="listTpl" id="listTpl" class="form-control">
            <?= $wei->html->options($wei->product->getListTpls()) ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="sort">
          顺序
        </label>

        <div class="col-lg-4">
          <input type="text" class="form-control" name="sort" id="sort">
        </div>

        <label class="col-lg-6 help-text" for="sort">
          大的显示在前面,按从大到小排列.
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="description">
          简介
        </label>

        <div class="col-lg-4">
          <textarea class="form-control" id="description" name="description"></textarea>
        </div>
      </div>

      <input type="hidden" name="id" id="id">
      <input type="hidden" name="type" id="type" value="mall">

      <div class="clearfix form-actions form-group">
        <div class="col-lg-offset-2">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-check bigger-110"></i>
            提交
          </button>
          &nbsp; &nbsp; &nbsp;
          <a class="btn btn-default" href="<?= $url('admin/product-categories') ?>">
            <i class="fa fa-undo bigger-110"></i>
            返回列表
          </a>
        </div>
      </div>
    </form>
  </div>
  <!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
<!-- /.row -->

<?= $block('js') ?>
<script>
  require(['form', 'ueditor', 'jquery-deparam', 'dataTable', 'validator'], function (form) {
    form.toOptions($('#parentId'), <?= json_encode(wei()->category()->notDeleted()->withParent('mall')->getTreeToArray()) ?>, 'id', 'name');

    var category = <?= $category->toJson() ?>;

    $('#category-form')
      .loadJSON(category)
      .loadParams()
      .ajaxForm({
        url: '<?= $url('admin/product-categories/' . ($category->isNew() ? 'create' : 'update')) ?>',
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
          return $form.valid();
        },
        success: function (result) {
          $.msg(result, function () {
            if (result.code > 0) {
              window.location = $.url('admin/product-categories');
            }
          });
        }
      })
      .validate();
  });
</script>
<?= $block->end() ?>
