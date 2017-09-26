define([
  'template',
  'css!plugins/product/css/admin/sku-input',
  'css!comps/select2/select2',
  'css!comps/select2-bootstrap-css/select2-bootstrap',
  'plugins/seq/js/seq',
  'comps/select2/select2.min',
  'comps/jquery.serializeJSON/jquery.serializejson.min'
], function (template) {
  var SkuInput = function () {
    this.container = $('.product-skus');
    this.skuControl = $('.sku-control-form-group');
    this.skuTable = $('.sku-table');
    this.data = {};
    this.skus = [];
    this.showNo = true;

    this.skuNameData = [
      {
        id: '',
        text: '规格'
      },
      {
        id: '',
        text: '颜色'
      },
      {
        id: '',
        text: '尺寸'
      },
      {
        id: '',
        text: '地区'
      }
    ];

    this.skuAttrsData = {
      规格: [],
      颜色: [
        {
          id: '',
          text: '黑色'
        },
        {
          id: '',
          text: '白色'
        },
        {
          id: '',
          text: '红色'
        },
        {
          id: '',
          text: '黄色'
        },
        {
          id: '',
          text: '蓝色'
        },
        {
          id: '',
          text: '绿色'
        },
        {
          id: '',
          text: '灰色'
        },
        {
          id: '',
          text: '紫色'
        },
        {
          id: '',
          text: '棕色'
        }
      ],
      尺寸: [
        {
          id: '',
          text: 'XS'
        },
        {
          id: '',
          text: 'S'
        },
        {
          id: '',
          text: 'M'
        },
        {
          id: '',
          text: 'L'
        },
        {
          id: '',
          text: 'XL'
        },
        {
          id: '',
          text: 'XXL'
        }
      ],
      地区: []
    };
  };

  /**
   * 初始化SKU选择器
   */
  SkuInput.prototype.init = function (options) {
    $.extend(this, options);

    var self = this;

    // 显示多规格相关的提示
    //self.form.find('.price-tips, .quantity-tips, .sku-form-group').show();

    // 1. 隐藏单价格商品所需的字段
    $('#quantity, #price').prop('readonly', true);
    $('.single-price-form-group').hide();

    // 2. 初始化select2的文案提示
    $.extend($.fn.select2.defaults, {
      formatNoMatches: function () {
        return '没有可选规格,请直接输入,按回车确认';
      }
    });

    // 加载数据
    if (self.data.skuConfigs.length > 0) {
      for (var i in self.data.skuConfigs) {
        if (Object.prototype.hasOwnProperty.call(self.data.skuConfigs, i)) {
          self.addSkuControl(self.data.skuConfigs[i], false);
        }
      }
    } else {
      self.addSkuControl({}, false);
    }

    // 加载数据后,才进行一次渲染
    self.renderSkuTable();

    // 点击添加SKU选择器
    self.container.on('click', '.add-sku', function () {
      self.addSkuControl();
    });

    // 点击删除SKU选择器
    self.container.on('click', '.delete-sku', function () {
      if (self.container.find('.sku-control').length === 1) {
        $.err('商品至少包含一个规格');
        return;
      }

      $(this).parents('.sku-control').remove();
      self.container.find('.add-sku').parent().show();
      self.renderSkuTable();
    });

    // 更改商品规格的数量,同时更新总数量
    self.skuTable.on('change', '.sku-quantity', function () {
      var total = 0;
      self.skuTable.find('.sku-quantity').each(function () {
        var quantity = parseInt(this.value, 10);
        if (self.isNumber(quantity)) {
          total += quantity;
        }
      });
      $('#quantity').val(total);
    });
    self.skuTable.find('.sku-quantity:first').trigger('change');

    // 更新商品规格的价格,同时更新最低价格
    self.skuTable.on('change', '.sku-price', function () {
      var lowestPrice = Number.MAX_VALUE;
      self.skuTable.find('.sku-price').each(function () {
        var price = parseFloat(this.value);
        if (self.isNumber(price) && price < lowestPrice) {
          lowestPrice = price;
        }
      });
      if (lowestPrice !== Number.MAX_VALUE) {
        $('#price').val(lowestPrice.toFixed(2));
      }
    });
    self.skuTable.find('.sku-price:first').trigger('change');
  };

  SkuInput.prototype.addSkuControl = function (data, render) {
    var self = this;

    data = data || {};
    render = typeof render === 'undefined' && true;
    data.id = data.id || ++$.guid;

    // 最多两个规格
    if (self.container.find('.sku-control').length === 1) {
      self.container.find('.add-sku').parent().hide();
    }

    self.skuControl.append(template.render('sku-form-group-tpl', data));

    var formGroup = $('#sku-control-' + data.id);
    var skuNameInput = formGroup.find('.sku-name');
    skuNameInput
      .select2({
        data: self.skuNameData,
        createSearchChoice: function (term, data) {
          if ($(data).filter(function () {
              return this.text.localeCompare(term) === 0;
            }).length === 0) {
            return {
              id: term,
              text: term
            };
          }
          return null;
        }
      })
      .on('select2-selecting', function (e) {
        // 选择规格名称时,如果选择的是预定义的,或者是自己新建的,后台为其生成唯一ID
        if (e.object.id === '' || e.object.id === e.object.text) {
          // 只有当前ID不存在时,才向后台获取唯一ID
          e.object.id = $(this).select2('val') || $.seq();
        }
      })
      .on('select2-close', function () {
        self.renderSkuTable();
      });

    // 设置默认数据
    if (typeof data.name !== 'undefined') {
      skuNameInput.select2('data', {
        id: data.id,
        text: data.name
      });
    }

    // 将数据表转换为select2要求的数据格式
    var skuAttrs = [];
    for (var i in data.attrs) {
      if (Object.prototype.hasOwnProperty.call(data.attrs, i)) {
        skuAttrs.push({
          id: data.attrs[i].id,
          text: data.attrs[i].value
        });
      }
    }

    formGroup.find('.sku-attrs')
      .select2({
        tags: function () {
          var name = skuNameInput.select2('data');
          if ($.isPlainObject(name) && typeof self.skuAttrsData[name.text] !== 'undefined') {
            return self.skuAttrsData[name.text];
          }

          return [];
        },
        createSearchChoice: function (term, data) {
          if ($(data).filter(function () {
              return this.text.localeCompare(term) === 0;
            }).length === 0) {
            return {
              id: term,
              text: term
            };
          }
          return null;
        }
      })
      .select2('data', skuAttrs)
      .on('select2-selecting', function (e) {
        // 新增规格的值,为其设置唯一ID
        if (e.object.id === '' || e.object.id === e.object.text) {
          e.object.id = $.seq();
        }
      })
      .on('select2-close', function () {
        self.renderSkuTable();
      })
      .on('select2-removed', function () {
        // 删除规格的值,重新渲染规格表格
        self.renderSkuTable();
      });

    // 按需重绘表格
    if (render === true) {
      self.renderSkuTable();
    }
  };

  /**
   * 获取规格配置和规格详细信息
   */
  SkuInput.prototype.getSkuData = function () {
    return {
      skuConfigs: this.getSkuConfigs()
    };
  };

  /**
   * 获取SKU的配置信息
   */
  SkuInput.prototype.getSkuConfigs = function () {
    var self = this;
    var data = [];

    self.container.find('.sku-control').each(function () {
      var skuConfig = {};
      skuConfig.attrs = [];

      // 获取规格名称数据
      var skuConfigData = $(this).find('input.sku-name').select2('data');

      // 如果未选择规格,直接跳过
      if (skuConfigData !== null) {
        skuConfig.id = skuConfigData.id;
        skuConfig.name = skuConfigData.text;

        // 获取规格的值的数据
        var skuAttrs = $(this).find('input.sku-attrs').select2('data');
        for (var i in skuAttrs) {
          if (Object.prototype.hasOwnProperty.call(skuAttrs, i)) {
            skuConfig.attrs.push({
              id: skuAttrs[i].id,
              value: skuAttrs[i].text
            });
          }
        }

        data.push(skuConfig);
      }
    });

    return data;
  };

  /**
   * 渲染商品规格表格
   */
  SkuInput.prototype.renderSkuTable = function () {
    var self = this;
    var data = self.getSkuData();
    if (data.skuConfigs.length === 0) {
      return;
    }

    data.skus = self.getSkus();

    var nameToIds = {};
    var specs = [];
    $.each(data.skuConfigs, function (i, skuConfig) {
      var attrs = [];
      $.each(skuConfig.attrs, function (j, attr) {
        attrs.push(attr.value);
        nameToIds[attr.value] = attr.id;
      });
      specs.push(attrs);
    });

    specs = self.cartesianProductOf.apply(self.cartesianProductOf, specs);

    // 构造规格对应的SKU数据
    var skus = [];
    specs.forEach(function (row) {
      var ids = [];
      row.forEach(function (item) {
        ids.push(nameToIds[item]);
      });
      var id = ids.join('-');
      if (typeof data.skus[id] !== 'undefined') {
        skus.push(data.skus[id]);
      } else {
        skus.push({
          attrIds: ids
        });
      }
    });

    specs = self.mergeRows(specs);

    data.$ = $;
    data.data = self.data;
    data.skus = skus;
    data.specs = specs;
    data.showNo = this.showNo;
    self.skuTable.html(template.render('sku-table-tpl', data));
  };

  /**
   * 获取规格数据
   */
  SkuInput.prototype.getSkus = function () {
    var self = this;

    // 如果.sku-table存在,从其获取,否则,从原始数据获取
    var data = [];
    if (self.skuTable.find('thead').length === 0 || self.skuTable.find('.table-empty-tips').length === 1) {
      data = self.skus;
    } else {
      data = self.skuTable.find(':input').serializeJSON().skus;
    }

    // 使用属性的ID作为索引
    var skus = {};
    $.each(data, function (i, sku) {
      skus[sku.attrIds.join('-')] = sku;
    });

    return skus;
  };

  /**
   * 生成笛卡尔积
   */
  SkuInput.prototype.cartesianProductOf = function () {
    return Array.prototype.reduce.call(arguments, function (a, b) {
      var ret = [];
      a.forEach(function (a) {
        b.forEach(function (b) {
          ret.push(a.concat([b]));
        });
      });
      return ret;
    }, [[]]);
  };

  /**
   * 合并相同的规格的表格行
   */
  SkuInput.prototype.mergeRows = function (specs) {
    var columns = [];
    return $.map(specs, function (row) {
      row = $.map(row, function (item, j) {
        if (typeof columns[j] !== 'undefined' && columns[j][0] === item) {
          columns[j][1]++;
          return null;
        }

        columns[j] = [item, 1]; // [规格值, rowspan]
        return [columns[j]];
      });
      return [row];
    });
  };

  SkuInput.prototype.isNumber = function (n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  };

  return new SkuInput();
});
