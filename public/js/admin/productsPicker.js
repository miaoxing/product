define([
  'css!comps/typeahead.js-bootstrap3.less/typeahead',
  'template',
  'comps/typeahead.js/dist/typeahead.bundle.min'
], function () {
  template.helper('$', $);

  var ProductsPicker = function () {
  };

  $.extend(ProductsPicker.prototype, {
    $el: $('body'),
    products:[],
    maxItems: 100,
    $: function (selector) {
      return this.$el.find(selector);
    },

    init: function(options) {
      $.extend(this, options);
      var self = this;
      self.$el.append('<div class="clearfix"></div><ul class="list-group product-list-group list-unstyled"></ul>');

      // 屏蔽用户信息鼠标点击事件
      self.$el.on('click', '.product-media', function (e) {
        return false;
      });

      // 显示商品
      for (var i in self.products) {
        self.addProduct(self.products[i]);
      }

      // 初始化搜索建议引擎
      var bestProducts = new Bloodhound({
        datumTokenizer: function (d) {
          return Bloodhound.tokenizers.whitespace(d.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: $.url('admin/products.json?search=%QUERY', {rows: 10}),
          ajax: {
            global: false,
            success: function () {
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
      }).on('typeahead:selected', function (event, suggestion, name) {
        self.addProduct(suggestion);
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
      if($('.product-list-group').children('.list-group-item').length >= this.maxItems) {
        $.msg({code:-1, message:'超过限定个数'});
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
