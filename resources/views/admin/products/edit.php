<?php

$view->layout();
$wei->page->addAsset('plugins/product/css/admin/products.css');
$hasScore = $wei->plugin->isInstalled('product-score');
?>

<div class="page-header">
  <a class="btn btn-default pull-right" href="<?= $url('admin/products') ?>">返回列表</a>

  <h1>
    商品管理
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-12">
    <!-- PAGE detail BEGINS -->
    <form id="product-form" class="form-horizontal" method="post" role="form">
      <fieldset>
        <legend class="text-muted text-xl">1. 商品基本信息</legend>
        <div class="form-group">
          <label class="col-lg-2 control-label" for="category-id">
            <span class="text-warning">*</span>
            栏目
          </label>

          <div class="col-lg-4">
            <select id="category-id" name="categoryId" class="form-control" data-rule-required="true">
              <option value="">请选择栏目</option>
            </select>
          </div>
        </div>

        <?php if (wei()->plugin->isInstalled('virtual-product')) : // TODO 事件如何控制中间插入的表单?>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="isVirtual">
              商品类型
            </label>

            <div class="col-lg-5">

              <label class="radio-inline">
                <input class="virtual" type="radio" name="isVirtual" value="0"> 实物商品
              </label>
              <label class="radio-inline">
                <input class="virtual" type="radio" name="isVirtual" value="1"> 虚拟商品
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

        <?php if (wei()->setting('product.enableIntro')) { ?>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="intro">
              简介
            </label>

            <div class="col-lg-4">
              <input type="text" class="form-control" name="intro" id="intro">
            </div>
          </div>
        <?php } ?>

        <div class="form-group sku-form-group display-none">
          <label class="col-sm-2 control-label">
            商品规格
          </label>

          <div class="col-sm-6 product-skus">
            <div class="sku-control-form-group"></div>
            <div class="col-form-label mb-2">
              <a href="javascript:" class="add-sku">+增加规格</a>
            </div>
            <div class="sku-table-form-group">
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
          <label class="col-lg-2 control-label" for="original-price">
            原价
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="originalPrice" id="original-price" data-rule-number="true"
              data-rule-min="0">
          </div>

          <label class="col-lg-6 help-text" for="discount">
            <span class="js-discount-text"></span>
            <input class="js-discount" type="hidden" name="discount" id="discount">
          </label>
        </div>

        <?php if ($hasScore) { ?>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="scores">
              所需积分
            </label>

            <div class="col-lg-4">
              <input type="text" class="form-control" name="scores" id="scores">
            </div>
          </div>
        <?php } ?>

        <div class="form-group form-group-quantity">
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
              <?php foreach (wei()->product()->getUnits() as $unit) : ?>
              <option value="<?= $unit ?>">
              <?php endforeach ?>
            </datalist>
          </div>

          <label class="col-lg-6 help-text quantity-tips" for="config[unit]">
            默认为“件”
          </label>
        </div>

      </fieldset>
      <fieldset>
        <legend class="text-muted text-xl">2. 商品详情信息</legend>
        <div class="form-group single-price-form-group">
          <label class="col-lg-2 control-label" for="no">
            货号
          </label>

          <div class="col-lg-4">
            <input type="text" class="form-control" name="no" id="no">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="product-images">
            <span class="text-warning">*</span>
            图片
          </label>

          <div class="col-sm-10">
            <input id="product-images" class="js-images" name="images[]" type="text" required>
            <label class="help-text" for="product-images">图片长宽比1:1<br>建议宽度大于等于750像素</label>
          </div>
        </div>

        <?php wei()->product->enableVideo && require $app->getControllerFile('_edit-video') ?>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="detail">
            商品描述
          </label>

          <div class="col-lg-8">
            <textarea id="detail" name="detail"></textarea>
          </div>
        </div>

        <?php if (wei()->product->enableProps) { ?>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="props">
              商品参数
            </label>

            <div class="col-lg-8">
              <textarea id="props" name="props"></textarea>
            </div>
          </div>
        <?php } ?>
      </fieldset>

      <fieldset>
        <legend class="text-muted text-xl">3. 商品物流信息</legend>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="shipping-tpl-id">
            <span class="text-warning">*</span>
            运费模板
          </label>

          <div class="col-lg-4">
            <select class="form-control" name="shippingTplId" id="shipping-tpl-id" data-rule-required="true">
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
        <legend class="text-muted text-xl">4. 其他信息</legend>

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
              <input type="radio" name="listing" data-rule-required="true" value="0"> 下架
            </label>
            <?php if (wei()->product->enableListingExt) { ?>
            <label class="radio-inline">
              <input type="radio" name="listing" data-rule-required="true" value="2"> 上架但不显示在列表
            </label>
            <?php } ?>

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

        <div class="form-group-config-no-show-cart form-group">
          <label class="col-lg-2 control-label" for="config[noShowCart]">
            不可加入购物车
          </label>

          <div class="col-lg-4">
            <label class="radio-inline">
              <input type="radio" name="config[noShowCart]"
                value="1" <?= $product['config']['noShowCart'] ? 'checked' : ''; ?>> 是
            </label>
            <label class="radio-inline">
              <input type="radio" name="config[noShowCart]"
                value="0" <?= !$product['config']['noShowCart'] ? 'checked' : ''; ?>> 否
            </label>
          </div>

          <label class="col-lg-6 help-text" for="config[noShowCart]">
            虚拟商品限定是不可加入购物车的
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

        <div class="form-group">
          <label class="col-lg-2 control-label">
            填写地址
          </label>

          <div class="col-lg-4">
            <label class="radio-inline">
              <input type="radio" name="config[requireAddress]" value="1"
                <?= $product['config']['requireAddress'] !== '0' ? 'checked' : '' ?>> 是
            </label>
            <label class="radio-inline">
              <input type="radio" name="config[requireAddress]" value="0"
                <?= $product['config']['requireAddress'] === '0' ? 'checked' : '' ?>> 否
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label">
            允许留言
          </label>

          <div class="col-lg-4">
            <label class="radio-inline">
              <input type="radio" name="config[allowComment]" value="1"
                <?= $product['config']['allowComment'] !== '0' ? 'checked' : '' ?>> 是
            </label>
            <label class="radio-inline">
              <input type="radio" name="config[allowComment]" value="0"
                <?= $product['config']['allowComment'] === '0' ? 'checked' : '' ?>> 否
            </label>
          </div>
        </div>

        <?php $event->trigger('adminProductsEdit', [$product]) ?>
      </fieldset>

      <input type="hidden" name="id" id="id">
      <input type="hidden" name="template" id="template">

      <div class="clearfix form-actions form-group">
        <div class="offset-lg-2">
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
  <div class="form-row sku-control mb-2" id="sku-control-<%= id %>">
    <div class="col-auto">
      <input type="text" class="sku-name" placeholder="规格名称"/>
    </div>
    <div class="col-form-label px-0">：</div>
    <div class="col-auto">
      <input type="text" class="sku-attrs" placeholder="请选择或输入规格">
    </div>
    <div class="col-auto col-form-label">
      &nbsp;<a href="javascript:" class="delete-sku">删除</a>
    </div>
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
    <?php if ($hasScore) { ?>
      <th>积分</th>
    <?php } ?>
    <th>货号</th>
    <th>销量</th>
  </tr>
  </thead>
  <tbody>

  <% if (specs.length === 0) { %>
  <tr>
    <td colspan="6">
      <div class="table-empty-tips">请先输入规格</div>
    </td>
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
    <?php if ($hasScore) { ?>
      <td>
        <input type="text" name="skus[<%= i %>][score]" class="sku-score"
          value="<%= skus[i].score || 0 %>" data-rule-required="true" data-rule-number="true" data-rule-min="0">
      </td>
    <?php } ?>
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

<?= $block->js() ?>
<script>
  require([
    'plugins/product/js/admin/product',
    'assets/numeric',
    'form',
    'comps/select2/select2.min',
    'validator',
    'ueditor',
    'plugins/admin/js/range-date-time-picker',
    'comps/jquery.serializeJSON/jquery.serializejson.min'
  ], function (product, numeric, form) {
    var categoryJson = <?= json_encode(wei()->category()->notDeleted()->withParent('mall')->getTreeToArray()) ?>;
    var shipJson = <?= json_encode(wei()->shippingTpl()->curApp()->notDeleted()->desc('id')->fetchAll()) ?>;
    form.toOptions($('#category-id'), categoryJson, 'id', 'name');
    form.toOptions($('#shipping-tpl-id'), shipJson, 'id', 'name');

    product.init({
      data: <?= $product->toJson() ?>,
      skus: <?= $skus->toJson() ?>
    });

    $('#detail').ueditor();

    var $props = $('#props');
    $props.length && $props.ueditor();

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
