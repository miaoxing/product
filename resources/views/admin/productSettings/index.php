<?php $view->layout() ?>

<div class="page-header">
  <h1>
    功能设置
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">
    <form action="<?= $url('admin/product-settings/update') ?>" class="js-setting-form form-horizontal" method="post"
      role="form">

      <div class="form-group">
        <label class="col-lg-2 control-label" for="show-tag">
          <span class="text-warning">*</span>
          显示标签
        </label>

        <div class="col-lg-4">
          <label class="radio-inline">
            <input type="radio" class="js-products-show-tag" id="show-tag" name="settings[products.showTag]"
              value="1"> 是
          </label>
          <label class="radio-inline">
            <input type="radio" class="js-products-show-tag" name="settings[products.showTag]" value="0"> 否
          </label>
        </div>

        <label class="col-lg-4 help-text" for="show-tag">
          商品详情头部的标签
        </label>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="default-list-tpl">
          <span class="text-warning">*</span>
          商品列表默认模板
        </label>

        <div class="col-lg-4">
          <select class="js-product-default-list-tpl form-control" name="settings[product.defaultListTpl]"
            id="default-list-tpl">
            <?= $wei->html->options($wei->product->getListTpls()) ?>
          </select>
        </div>
      </div>

      <div class="clearfix form-actions form-group">
        <div class="col-lg-offset-2">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-check bigger-110"></i>
            提交
          </button>
        </div>
      </div>
    </form>
  </div>
  <!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
<!-- /.row -->

<?= $block('js') ?>
<script>
  require(['form', 'ueditor', 'validator'], function () {
    $('.js-setting-form')
      .loadJSON(<?= $setting->getFormJson(['products.showTag' => '0','product.defaultListTpl' => 'md']) ?>)
      .ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
          return $form.valid();
        }
      })
      .validate();
  });
</script>
<?= $block->end() ?>
