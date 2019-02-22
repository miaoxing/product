/* global Bloodhound */
define([
  plugins/app/libs/artTemplate/template.min,
  'css!comps/typeahead.js-bootstrap3.less/typeahead',
  'comps/typeahead.js/dist/typeahead.bundle.min'
], function (template) {

  var ProductsPicker = function () {
    // do nothing.
  };

  $.extend(ProductsPicker.prototype, {
    $el: $('body'),
    products:[],
    url: 'admin/products.json',
    searchKey: 'search',
    maxItems: 100,
    rows: 10,
    $: function (selector) {
      return this.$el.find(selector);
    },

    init: function(options) {
      $.extend(this, options);
      var that = this;
      that.$el.append('<div class="clearfix"></div><ul class="list-group product-list-group list-unstyled"></ul>');

      // 屏蔽用户信息鼠标点击事件
      that.$el.on('click', '.product-media', function () {
        return false;
      });

      // 显示商品
      for (var i in that.products) {
        if (Object.prototype.hasOwnProperty.call(that.products, i)) {
          that.addProduct(that.products[i]);
        }
      }

      // 初始化搜索建议引擎
      var bestProducts = new Bloodhound({
        datumTokenizer: function (d) {
          return Bloodhound.tokenizers.whitespace(d.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: $.url($.appendUrl(that.url, that.searchKey + '=%QUERY'), {rows: that.rows}),
          ajax: {
            global: false,
            success: function () {
              // 不自动弹出头部提示
            }
          },
          filter: function (result) {
            return result.data;
          }
        }
      });
      bestProducts.initialize();

      // 搜索框增加搜索建议
      $('.product-typeahead').typeahead(null, {
        name: 'best-products',
        source: bestProducts.ttAdapter(),
        displayKey: 'name',
        templates: {
          empty: '<div class="empty-product-message">没有找到相关商品</div>',
          suggestion: template.compile($('#product-tpl').html())
        }
      }).on('typeahead:selected', function (event, suggestion) {
        that.addProduct(suggestion);
        $(this).val('');
      });

      // 删除商品
      $('.product-list-group').on('click', '.remove-product', function () {
        $(this).parents('li:first').fadeOut(function () {
          $(this).remove();
        });
      });
    },

    addProduct: function (product) {
      if ($('.product-list-group').children('.list-group-item').length >= this.maxItems) {
        $.err('超过限定个数');
        return;
      }

      product.content = '单价: ' + product.price;
      var listItem = template.render('product-list-item-tpl', {
        product: product,
        template: template
      });

      $(listItem).prependTo('.product-list-group').fadeIn();
    }
  });

  return new ProductsPicker();
});
