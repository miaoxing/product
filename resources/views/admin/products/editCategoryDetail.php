<?php $view->layout() ?>

<div class="page-header">
  <a class="btn btn-default pull-right" href="<?= $url('admin/product-categories/edit?id='.$category['id']) ?>">返回列表</a>

  <h1>
    微商城
    <small>
      <i class="fa fa-angle-double-right"></i>
      栏目详情管理
    </small>
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-12">
    <!-- PAGE detail BEGINS -->
    <form id="categoryDetail-form" class="form-horizontal" method="post" role="form">
      <fieldset>
        <legend class="text-muted text-xl">商品栏目详细信息</legend>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="categoryId">
            <span class="text-warning">*</span>
            栏目
          </label>

          <div class="col-lg-4 form-control-static">
            <p><?= $category['name'] ?></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="showed">
            状态
          </label>

          <div class="col-lg-4">
            <label class="radio-inline">
              <input type="radio" name="showed" value="1"> 显示
            </label>

            <label class="radio-inline">
              <input type="radio" name="showed" value="0"> 不显示
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="detailUp">
            栏目详情头部
          </label>

          <div class="col-lg-8">
            <textarea id="detailUp" name="detailUp"></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="detailDown">
            栏目详情尾部
          </label>

          <div class="col-lg-8">
            <textarea id="detailDown" name="detailDown"></textarea>
          </div>
        </div>
      </fieldset>

      <input type="hidden" name="categoryId" id="categoryId" value="<?= $category['id'] ?>">

      <div class="clearfix form-actions form-group">
        <div class="offset-lg-2">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-check bigger-110"></i>
            提交
          </button>

          &nbsp; &nbsp; &nbsp;
          <a class="btn btn-default" href="<?= $url('admin/products') ?>">
            <i class="fa fa-undo"></i>
            返回列表
          </a>
        </div>
      </div>

    </form>
  </div>
  <!-- PAGE detail ENDS -->
</div><!-- /.col -->
<!-- /.row -->

<?= $block->js() ?>
<script>
  require(['form', 'validator', 'assets/spectrum','ueditor'], function () {
    $('#categoryDetail-form')
      .loadJSON(<?= $categoryDetail->toJson() ?>)
      .ajaxForm({
      url: '<?= $url('admin/products/updateCategoryDetail') ?>',
      dataType: 'json',
      success: function (result) {
        $.msg(result, function () {
          if (result.code > 0) {
            window.location = $.url('admin/product-categories/edit?id=<?= $category['id'] ?>');
          }
        });
      }
    }).validate();

    $('#detailUp').ueditor();
    $('#detailDown').ueditor();

  });

</script>
<?= $block->end() ?>
