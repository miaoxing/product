define(['jquery', 'form', plugins/app/libs/artTemplate/template.min, 'plugins/admin/js/data-table'], function($, form, template) {

  // SKU PICKER CLASS DEFINITION
  // ===========================

  var SkuPicker = function(element, options) {
    this.options = options;
    this.$element = $(element);
    this.$modal = $(template.render('skuPickerTpl', options));
    this.$table = this.$modal.find('.js-sku-picker-table');
    this.$modal.appendTo(this.options.target);
    form.toOptions(this.$modal.find('#categoryId'), this.options.category, 'id', 'name');
    this.init();
  };

  SkuPicker.DEFAULTS = {
    data: {},
    target: 'body',
    paramType: 'quantity'
  };

  SkuPicker.prototype.init = function () {
    var that = this;

    this.indexData();
    this.updateSelectedSku();

    this.$table = this.$table.dataTable({
      ajax: {
        url: $.url('admin/skus.json')
      },
      columns: [
        {
          data: 'product',
          render: function (data) {
            return template.render('product-tpl', data);
          }
        },
        {
          data: 'specs',
          render: function (data, type, full) {
            if (full.product.skuConfigs.length === 1
              && full.product.skuConfigs[0].attrs.length === 1) {
              return '-';
            }

            var values = [];
            for (var key in data) {
              if (data.hasOwnProperty(key)) {
                values.push(data[key]);
              }
            }
            return '<span class="js-sku-specs">' + values.join(', ') + '</span>';
          }
        },
        {
          data: 'quantity'
        },
        {
          data: 'quantity',
          render: function (data, type, full) {
            return template.render('skuPickerQuantityTpl', full);
          }
        },
        {
          data: 'id',
          render: function (data, type, full) {
            if (typeof that.options.data[data] !== 'undefined') {
              full.selectedQuantity = that.options.data[data][that.options.paramType];
              full.selected = true;
            } else {
              full.selectedQuantity = 1;
              full.selected = false;
            }
            return template.render('skuPickerActionsTpl', full);
          }
        }
      ]
    });

    this.$modal.find('.js-sku-picker-form').update(function () {
      that.$table.reload($(this).serialize(), false);
    });

    // 加入或取消商品
    this.$table.on('click', '.js-sku-picker-toggle', function () {
      var $this = $(this);

      var id = $this.data('id');
      var selected = $this.hasClass('selected');
      var $row = $this.closest('tr');
      var rowData = that.$table.fnGetData($row[0]);

      // 更新sku数据
      if (selected) {
        delete that.options.data[id];
      } else {
        // .js-sku-item
        that.options.data[id] = {
          id: id,
          name: rowData.product.name,
          specs: $row.find('.js-sku-specs').html()
        };
        that.options.data[id][that.options.paramType] = $row.find('.js-sku-picker-quantity').val();
      }

      // 重新渲染视图
      that.updateSelectedSku();
      $this.parent().html(template.render('skuPickerActionsTpl', {
        id: id,
        selected: !selected
      }));
    });

    // 更改商品数量
    this.$table.on('change', '.js-sku-picker-quantity', function () {
      var $this = $(this);
      var id = $this.data('id');

      var val = $this.val();
      switch (that.options.paramType) {
        case 'price':
          val = parseFloat(val);
          if (val < 0 || isNaN(val)) {
            val = 0;
          }
          $this.val(val.toFixed(2));
          break;

        case 'quantity':
          val = parseInt(vak, 10);
          if (val < 1 || isNaN(val)) {
            val = 1;
          }
          $this.val(val);
          break;
      }

      if (typeof that.options.data[id] !== 'undefined') {
        that.options.data[id][that.options.paramType] = val;
      }
    });

    // 关闭选择框,触发关闭事件
    this.$modal.on('hide.bs.modal', function () {
      var e = $.Event('close');
      var data = [];
      for (var i in that.options.data) {
        if (Object.prototype.hasOwnProperty.call(that.options.data, i)) {
          data.push(that.options.data[i]);
        }
      }
      that.$element.trigger(e, [data]);
    });
  };

  // 更改已选数量和id
  SkuPicker.prototype.updateSelectedSku = function () {
    this.$modal.find('.js-sku-picker-selected-num').html(this.options.data.length);

    var ids = [];
    $.each(this.options.data, function (id, data) {
      ids.push(id);
    });
    var val = '';
    if (ids.length === 0) {
      val = 'not-exists';
    } else {
      val = ids.join(',');
    }
    this.$modal.find('.js-sku-picker-view-selected').val(val);
  };

  // 更新数据以id作为索引
  SkuPicker.prototype.indexData = function () {
    var data = {};
    for (var i in this.options.data) {
      if (Object.prototype.hasOwnProperty.call(this.options.data, i)) {
        data[this.options.data[i].id] = this.options.data[i];
      }
    }
    this.options.data = data;
  };

  SkuPicker.prototype.show = function () {
    this.$modal.modal('show');
  };

  // SKU PICKER PLUGIN DEFINITION
  // ===========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this);
      var data    = $this.data('sku-picker');
      var options = $.extend({}, SkuPicker.DEFAULTS, typeof option === 'object' && option);

      if (!data) {
        $this.data('sku-picker', (data = new SkuPicker(this, options)));
      }
      if (typeof option === 'string') {
        data[option]();
      } else if (options.show) {
        data.show();
      }
    });
  }

  $.fn.skuPicker = Plugin;
  $.fn.skuPicker.Constructor = SkuPicker;

  return SkuPicker;
});
