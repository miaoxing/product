<?php $view->layout() ?>

<?= $block('css') ?>
<link rel="stylesheet" href="<?= $asset('assets/admin/mall/product.css') ?>"/>
<?= $block->end() ?>

<div class="page-header">
  <a class="btn pull-right" href="<?= $url('admin/products') ?>">返回列表</a>

  <h1>
    商品管理
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE detail BEGINS -->
    <form id="product-form" class="form-horizontal" method="post" role="form">
      <fieldset>
        <legend class="grey bigger-130">1. 商品基本信息</legend>
        <div class="form-group">
          <label class="col-lg-2 control-label" for="categoryId">
            <span class="text-warning">*</span>
            栏目
          </label>

          <div class="col-lg-4">
            <select id="categoryId" name="categoryId" class="form-control" data-rule-required="true">
              <option value="">请选择栏目</option>
            </select>
          </div>
        </div>

        <?php if (wei()->plugin->isInstalled('virtual-product')) : // TODO 事件如何控制中间插入的表单
 ?>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="virtual">
              商品类型
            </label>

            <div class="col-lg-5">

              <label class="radio-inline">
                <input class="virtual" type="radio" name="virtual" value="0"> 实物商品
              </label>
              <label class="radio-inline">
                <input class="virtual" type="radio" name="virtual" value="1"> 虚拟商品
                <a href="http://miaoxing.mydoc.io/?t=163026" target="_blank">《虚拟商品使用文档》</a>
              </label>

            </div>
          </div>
        <?php endif ?>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="name">
            <span class="text-warning">*</span>
            商品标题
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="name" id="name" data-rule-required="true">
          </div>
        </div>

        <div class="form-group sku-form-group display-none">
          <label class="col-sm-2 control-label">
            商品规格
          </label>

          <div class="col-sm-6 product-skus">
            <div class="form-group sku-control-form-group">

            </div>
            <div class="form-group">
              <a href="javascript:;" class="add-sku">+增加规格</a>
            </div>
            <div class="form-group sku-table-form-group">
              <table class="sku-table table table-bordered">

              </table>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="price">
            <span class="text-warning">*</span>
            价格
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="price" id="price" data-rule-required="true"
                   data-rule-number="true" data-rule-min="0">
          </div>

          <label class="col-lg-6 help-text price-tips display-none" for="price">
            默认显示商品规格中的最小价格
          </label>
        </div>

        <div class="form-group single-price-form-group">
          <label class="col-lg-2 control-label" for="originalPrice">
            原价
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="originalPrice" id="originalPrice" data-rule-number="true"
                   data-rule-min="0">
          </div>

          <label class="col-lg-6 help-text" for="discount">
            <span class="js-discount-text"></span>
            <input class="js-discount" type="hidden" name="discount" id="discount">
          </label>
        </div>

        <?php if ($plugin->isInstalled('product-score')) : ?>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="scores">
              所需积分
            </label>

            <div class="col-lg-4">
              <input type="text" class="form-control" name="scores" id="scores">
            </div>
          </div>
        <?php endif ?>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="quantity">
            <span class="text-warning">*</span>
            总库存
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="quantity" id="quantity" data-rule-required="true"
                   data-rule-digits="true">
          </div>

          <label class="col-lg-6 help-text quantity-tips display-none" for="quantity">
            总库存等于"商品规格"中的"库存"总和
          </label>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="config[quantityName]">
            数量名称
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="config[quantityName]" id="config[quantityName]"
                   value="<?= $product['config']['quantityName'] ?>">
          </div>

          <label class="col-lg-6 help-text quantity-tips" for="config[quantityName]">
            商品详情页上的购买数量显示的名称
          </label>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="config[unit]">
            单位
          </label>

          <div class="col-lg-4">
            <input list="units" type="text" class="form-control" name="config[unit]" id="config[unit]"
                   value="<?= $product['config']['unit'] ?>">
            <datalist id="units">
              <?php foreach (wei()->product()->getUnits() as $unit) {
     ?>
              <option value="<?= $unit ?>">
                <?php

 } ?>
            </datalist>
          </div>

          <label class="col-lg-6 help-text quantity-tips" for="config[unit]">
            默认为“件”
          </label>
        </div>

      </fieldset>
      <fieldset>
        <legend class="grey bigger-130">2. 商品详情信息</legend>
        <div class="form-group single-price-form-group">
          <label class="col-lg-2 control-label" for="no">
            货号
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="no" id="no">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">
            <span class="text-warning">*</span>
            图片
          </label>

          <div class="col-sm-10">
            <ul class="ace-thumbnails image-picker">
              <li class="select-image text-center">
                <h5>选择图片</h5>
                <i class="fa fa-picture-o"></i>
              </li>
            </ul>
            <label class="help-text">图片长宽比1:1<br>建议宽度大于等于640像素</label>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="detail">
            商品描述
          </label>

          <div class="col-lg-8">
            <textarea id="detail" name="detail"></textarea>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend class="grey bigger-130">3. 商品物流信息</legend>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="shippingTplId">
            <span class="text-warning">*</span>
            运费模板
          </label>

          <div class="col-lg-4">
            <select class="form-control" name="shippingTplId" id="shippingTplId" data-rule-required="true">
              <option value="">无</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="allowCashOnDelivery">
            货到付款
          </label>

          <div class="col-lg-4">

            <label class="radio-inline">
              <input type="radio" name="allowCashOnDelivery" value="1"> 允许
            </label>
            <label class="radio-inline">
              <input type="radio" name="allowCashOnDelivery" value="0"> 不允许
            </label>

          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend class="grey bigger-130">4. 其他信息</legend>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="listing">
            <span class="text-warning">*</span>
            上架设置
          </label>

          <div class="col-lg-4">

            <label class="radio-inline">
              <input type="radio" name="listing" data-rule-required="true" value="1"> 上架
            </label>
            <label class="radio-inline">
              <input type="radio" name="listing" data-rule-required="true" value="0"> 不上架
            </label>

          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="startTime">
            上架时间
          </label>

          <div class="col-lg-4">
            <div>
              <input type="text" class="form-control" name="startTime" id="startTime"
                     style="position: relative; z-index: 1000;">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="endTime">
            下架时间
          </label>

          <div class="col-lg-4">
            <div>
              <input type="text" class="form-control" name="endTime" id="endTime"
                     style="position: relative; z-index: 1000;">
            </div>
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
          <label class="col-lg-2 control-label" for="limitation">
            最大购买数量
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="limitation" id="limitation">
          </div>

          <label class="col-lg-6 help-text" for="limitation">
            0表示不限制
          </label>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="config[noShowCart]">
            不可加入购物车
          </label>

          <div class="col-lg-4">
            <label class="radio-inline">
              <input type="radio" name="config[noShowCart]" value="1" <?= $product['config']['noShowCart'] ? 'checked' : ''; ?>> 是
            </label>
            <label class="radio-inline">
              <input type="radio" name="config[noShowCart]" value="0" <?= !$product['config']['noShowCart'] ? 'checked' : ''; ?>> 否
            </label>
          </div>

          <label class="col-lg-6 help-text" for="config[noShowCart]">
            虚拟商品限定是不可加入购物车的
          </label>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="config[buyWithSameCategory]">
            同类下单
          </label>

          <div class="col-lg-4">
            <label class="radio-inline">
              <input type="radio" name="config[buyWithSameCategory]" value="1" <?= $product['config']['buyWithSameCategory'] ? 'checked' : ''; ?>> 是
            </label>
            <label class="radio-inline">
              <input type="radio" name="config[buyWithSameCategory]" value="0" <?= !$product['config']['buyWithSameCategory'] ? 'checked' : ''; ?>> 否
            </label>
          </div>

          <label class="col-lg-6 help-text" for="config[buyWithSameCategory]">
            同类别的产品才可以一起下单支付
          </label>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="allowCoupon">
            优惠券
          </label>

          <div class="col-lg-4">

            <label class="radio-inline">
              <input type="radio" name="allowCoupon" value="1"> 允许使用
            </label>
            <label class="radio-inline">
              <input type="radio" name="allowCoupon" value="0"> 不允许使用
            </label>

          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="subAtPay">
            库存计数
          </label>

          <div class="col-lg-4">

            <label class="radio-inline" title="买家拍下商品即减少库存，存在恶拍风险。秒杀、超低价等热销商品，如需避免超卖可选此方式">
              <input type="radio" name="subAtPay" class="subAtPay" value="0"> 拍下减库存
            </label>
            <label class="radio-inline" title="买家拍下并完成付款方减少库存，存在超卖风险。如需减少恶拍、提高回款效率，可选此方式">
              <input type="radio" name="subAtPay" class="subAtPay" value="1"> 付款减库存
            </label>

          </div>
        </div>

        <?php $event->trigger('adminProductsEdit', [$product]) ?>
      </fieldset>

      <input type="hidden" name="id" id="id">
      <input type="hidden" name="template" id="template">

      <div class="clearfix form-actions form-group">
        <div class="col-lg-offset-2">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-check"></i>
            提交
          </button>

          &nbsp; &nbsp; &nbsp;
          <a class="btn btn-default" href="javascript:window.history.back();">
            <i class="fa fa-undo"></i>
            返回
          </a>
        </div>
      </div>

    </form>
  </div>
  <!-- PAGE detail ENDS -->
</div><!-- /.col -->
<!-- /.row -->

<!-- 商品规格选择器 -->
<script id="sku-form-group-tpl" type="text/html">
  <div class="form-group sku-control" id="sku-control-<%= id %>">
    <input type="text" class="sku-name" placeholder="规格名称"/>

    <p class="form-control-static pull-left">：</p>
    <input type="text" class="sku-attrs" placeholder="请选择或输入规格">

    <p class="form-control-static">
      &nbsp;<a href="javascript:;" class="delete-sku">删除</a>
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
    <th>货号</th>
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
    <td>
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

<?= $block('js') ?>
<script>
  require([
    'plugins/product/js/admin/product',
    'assets/numeric',
    'form',
    'comps/select2/select2.min',
    'validator',
    'ueditor',
    'assets/dateTimePicker',
    'comps/jquery.serializeJSON/jquery.serializejson.min'
  ], function (product, numeric, form) {
    form.toOptions($('#categoryId'), <?= json_encode(wei()->category()->notDeleted()->withParent('mall')->getTreeToArray()) ?>, 'id', 'name');
    form.toOptions($('#shippingTplId'), <?= json_encode(wei()->shippingTpl()->curApp()->notDeleted()->desc('id')->fetchAll()) ?>, 'id', 'name');

    product.init({
      data: <?= $product->toJson() ?>,
      skus: <?= $skus->toJson() ?>
    });

    $('#detail').ueditor();

    // 开始结束时间使用日期时间范围选择器
    $('#startTime, #endTime').rangeDateTimePicker({
      showSecond: true,
      dateFormat: 'yy-mm-dd',
      timeFormat: 'HH:mm:ss'
    });

    // 通过价格和原始价格,自动计算折扣
    $('#originalPrice, #price').keyup(function () {
      var discount = parseFloat($('#price').val()) / parseFloat($('#originalPrice').val()) * 10;
      if (!isFinite(discount) || discount >= 10 || discount < 0.01) {
        $('.js-discount').val('0.00');
        $('.js-discount-text').html('');
      } else {
        discount = discount.toFixed(2);
        $('.js-discount').val(discount);
        $('.js-discount-text').html(discount + '折');
      }
    });
    $('#originalPrice').keyup();
  });

  // 选择或新增标签
  require([
    'assets/admin/tag',
    'comps/select2/select2.min',
    'css!comps/select2/select2',
    'css!comps/select2-bootstrap-css/select2-bootstrap'
  ], function (tag) {
    var tags = $('#tags');
    tag.init({
      obj: tags,
      recordTable: 'product',
      productTags: <?= $tags->toJson() ?>
    });
  });
</script>
<?= $block->end() ?>
