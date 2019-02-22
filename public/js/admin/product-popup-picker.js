/* global template */
define(['form', 'css!../../css/admin/product-popup-picker', 'plugins/app/libs/artTemplate/template.min', 'plugins/admin/js/data-table'], function (form) {
  var ProductPopupPicker = function () {
    // do nothing.
  };

  ProductPopupPicker.DEFAULTS = {
    data: {},
    target: 'body',
    maxNum: 1,
    inputName: 'productId',
    selectName: '请选择商品',
    changeName: '重新选择',
    clearName: '取消'
  };

  ProductPopupPicker.prototype.render = function (element, options) {
    this.options = $.extend({}, ProductPopupPicker.DEFAULTS, typeof options === 'object' && options);
    this.$element = $(element);

    this.initElements();
    this.renderPicker();
    this.initEvents();
  };

  ProductPopupPicker.prototype.initElements = function () {
    this.$modal = $(template.render('product-popup-picker-modal-tpl'));
    this.$form = this.$modal.find('.js-product-popup-picker-form');
    this.$table = this.$modal.find('.js-product-popup-picker-table');
    this.$viewSelected = this.$modal.find('.js-product-popup-picker-view-selected');
    this.$selectedNum = this.$modal.find('.js-product-popup-picker-selected-num');
  };

  ProductPopupPicker.prototype.renderPicker = function () {
    var products = $.map(this.options.data, function(product) {
      product.content = '价格: ' + product.price + ' 库存: ' + product.quantity;
      return product;
    });

    this.$element.html(template.render('product-popup-picker-tpl', {
      products: products,
      selectName: this.options.selectName,
      changeName: this.options.changeName,
      clearName: this.options.clearName,
      inputName: this.options.inputName
    }));

    var e = $.Event('productPopupPicker:renderPicker', {
      products: products
    });
    this.$element.trigger(e);
  };

  ProductPopupPicker.prototype.renderModal = function () {
    if ($.fn.dataTable.fnIsDataTable(this.$table[0])) {
      this.$table.reload();
      return;
    }

    var that = this;
    this.$modal.find('.js-product-popup-picker-max-num').html(this.options.maxNum);
    this.$modal.appendTo(this.options.target);
    form.toOptions(this.$modal.find('.js-product-popup-picker-category-id'), this.options.categories, 'id', 'name');

    this.indexData();
    this.updateSelectedData();

    this.$table = this.$table.dataTable({
      ajax: {
        url: $.url('admin/products.json')
      },
      columns: [
        {
          data: 'id',
          render: function (data, type, full) {
            return template.render('product-tpl', full);
          }
        },
        {
          data: 'categoryName',
          sClass: 'text-center'
        },
        {
          data: 'price',
          sClass: 'text-center',
          render: function (data, type, full) {
            return '￥' + data + (full.scores !== '0' ? '+' + full.scores + '积分' : '');
          }
        },
        {
          data: 'stock'
        },
        {
          data: 'id',
          render: function (data, type, full) {
            full.selected = typeof that.options.data[data] !== 'undefined';
            return template.render('product-popup-picker-actions-tpl', full);
          }
        }
      ]
    });
  };

  ProductPopupPicker.prototype.initEvents = function () {
    var that = this;

    this.$element.on('click', '.js-product-popup-picker-select', function () {
      that.show();
    });

    this.$element.on('click', '.js-product-popup-picker-clear', function () {
      that.clear();
    });

    this.$form.update(function () {
      that.$table.reload($(this).serialize(), false);
    });

    // 加入或取消商品
    this.$table.on('click', '.js-product-popup-picker-toggle', function () {
      var $this = $(this);

      var id = $this.data('id');
      var selected = $this.hasClass('selected');
      var $row = $this.closest('tr');
      var rowData = that.$table.fnGetData($row[0]);

      if (selected) {
        delete that.options.data[id];
      } else {
        if (Object.keys(that.options.data).length >= that.options.maxNum) {
          $.err('最多可选择' + that.options.maxNum + '项');
          return;
        }
        that.options.data[id] = rowData;
      }

      // 重新渲染视图
      that.updateSelectedData();
      $this.parent().html(template.render('product-popup-picker-actions-tpl', {
        id: id,
        selected: !selected
      }));
    });

    // 关闭选择框,触发关闭事件
    this.$modal.on('hide.bs.modal', function () {
      that.renderPicker();
    });
  };

  // 更改已选数量和id
  ProductPopupPicker.prototype.updateSelectedData = function () {
    var ids = Object.keys(this.options.data);

    var val = '';
    if (ids.length === 0) {
      val = 'not-exists';
    } else {
      val = ids.join(',');
    }
    this.$viewSelected.val(val);
    this.$selectedNum.html(ids.length);
  };

  // 更新数据以id作为索引
  ProductPopupPicker.prototype.indexData = function () {
    var data = {};
    for (var i in this.options.data) {
      if (this.options.data.hasOwnProperty(i)) {
        data[this.options.data[i].id] = this.options.data[i];
      }
    }
    this.options.data = data;
  };

  ProductPopupPicker.prototype.show = function () {
    this.renderModal();
    this.$modal.modal('show');
  };

  ProductPopupPicker.prototype.clear = function () {
    this.options.data = {};
    this.updateSelectedData();
    this.renderPicker();
  };

  return new ProductPopupPicker();
});
