/* global Snap */
define(['comps/artTemplate/template.min'], function (template) {
  var Products = function () {
    // do nothing.
  };

  var unauthorized = -401;
  var delayLong = 5000;

  $.extend(Products.prototype, {
    data: {},
    skus: {},
    $el: $('body'),
    $: function (selector) {
      return this.$el.find(selector);
    },

    /**
     * 商品列表
     */
    indexAction: function (options) {
      var that = this;
      $.extend(this, options);

      // 初始化Snapper
      var snapper = new Snap({
        element: document.getElementsByClassName('js-product-snap')[0],
        touchToDrag: false
      });

      snapper.on('open', function () {
        $('.js-product-drawers').show();
      });

      snapper.on('close', function () {
        $('.js-product-drawers').hide();
      });

      // 开启/关闭侧边栏
      this.$('.js-product-drawer-toggle').click(function () {
        var dir = $(this).data('dir');
        if (snapper.state().state === dir) {
          snapper.close();
        } else {
          snapper.open(dir);
        }
      });

      // 关闭侧边栏
      this.$('.js-product-drawer-close').click(function () {
        snapper.close();
        return false;
      });

      // 提交搜索表单,附加当前URL参数
      this.$('.product-search-form form').submit(function (e) {
        window.location = $.appendUrl(window.location, {q: that.$('.js-product-search').val()});
        e.preventDefault();
      });

      // 点击父分类,显示子分类
      this.$('.js-category-title').click(function () {
        $(this).toggleClass('active').next().slideToggle();
      });
    },

    /**
     * 查看商品
     */
    showAction: function (options) {
      $.extend(this, options);

      var that = this;

      this.showCartNum();

      // 点击弹出加入购物车的选择框
      var $showPicker = this.$('.js-picker-show');
      $showPicker.click(function (e) {
        options.e = e;
        that.showPicker(options);
      });

      if ($showPicker.length > 0 && $.req('show-picker')) {
        that.showPicker(options);
      }
    },

    /**
     * 展示购物车数量
     */
    showCartNum: function () {
      var $cartNum = $('.js-product-cart-num');
      if ($cartNum.length) {
        $.getJSON($.url('carts/count'), function (ret) {
          if (ret.count > 0) {
            $cartNum.html(ret.count);
          }
        });
      }
    },

    /**
     * 展示商品选择器
     */
    showPicker: function (options) {
      var picker = new ProductPicker();
      picker.render(options);
    },

    /**
     * 展示购物车的商品选择器
     */
    showCartPicker: function (options) {
      $.ajax({
        url: $.url('products/%s.json', options.productId),
        loading: true,
        dataType: 'json',
        success: function (ret) {
          if (ret.code !== 1) {
            $.msg(ret);
          } else {
            var picker = new ProductPicker();
            picker.render($.extend({
              action: 'updateCart',
              data: ret.data,
              skus: ret.skus
            }, options));
          }
        }
      });
    }
  });

  /**
   * 下单时的商品选择器
   */
  var ProductPicker = function () {
    // do nothing.
  };

  $.extend(ProductPicker.prototype, {
    /**
     * 当前的操作,可以是addCart或updateCart
     * addCart用于在商品页面加入购物车
     * updateCart用于购物车页面重选SKU
     */
    action: 'addCart',

    /**
     * 当商品只有一个SKU配置,且限制购买一个,是否点击就加入购物车
     */
    isQuickCreate: false,

    /**
     * 点击弹出选择器的事件
     */
    e: null,

    /**
     * 选中的SKU
     */
    selectedSkuId: 0,

    /**
     * 数量
     */
    quantity: 1,

    /**
     * 要更新的购物车编号
     */
    cartId: 0,

    /**
     * 商品的信息
     */
    data: {},

    skus: {},

    $el: null,

    $: function (selector) {
      return this.$el.find(selector);
    },

    render: function (options) {
      $.extend(this, options);

      if (this.isQuickCreate && this.canQuickCreate()) {
        this.quickCreate();
        return;
      }

      this.$el = this.renderPicker();
      this.bindEvents();
    },

    /**
     * 快速创建购物车/订单
     */
    quickCreate: function () {
      var type = $(this.e.target).data('type');
      var cart = {skuId: this.skus[0].id};
      if (type === 'cart') {
        this.create(cart);
      } else {
        this.createAndPay(cart);
      }
    },

    /**
     * 渲染选择器
     */
    renderPicker: function () {
      var $modal = $(template.render('productModalTpl', {
        data: this.data,
        action: this.action,
        cartId: this.cartId,
        quantity: this.quantity,
        skus: this.skus,
        priceText: this.getPriceText(this.skus)
      }));

      var e = $.Event('productPicker:show', {
        picker: this,
        $modal: $modal
      });
      $(document).trigger(e);

      $modal.appendTo('body').modal('show');
      return $modal;
    },

    /**
     * 绑定各类事件
     */
    bindEvents: function () {
      this.initPicker();
      this.initCart();
      this.initSkuSelector();
      this.initSpinner();
      this.updateNoQuantity();
      this.initSelectedAttrs();
    },

    /**
     * 初始化选择器事件
     */
    initPicker: function () {
      var $el = this.$el;
      $el.on('hidden.bs.modal', function () {
        $el.remove();
      });
    },

    initSelectedAttrs: function () {
      var attrIds = this.getSelectedAttrIds();
      $.each(attrIds, function (i, attrId) {
        $('.js-sku-attr[data-id=' + attrId + ']').click();
      });
    },

    /**
     * 获取选中SKU对应的属性编号
     */
    getSelectedAttrIds: function () {
      if (!this.selectedSkuId) {
        return [];
      }

      var that = this;
      var selectedAttrIds = [];
      $.each(this.skus, function (i, sku) {
        if (parseInt(sku.id, 10) === that.selectedSkuId) {
          selectedAttrIds = sku.attrIds;
          return false;
        }
        return true;
      });
      return selectedAttrIds;
    },

    /**
     * 关闭选择器
     */
    close: function () {
      if (this.$el) {
        this.$el.modal('hide');
      }
    },

    /**
     * 初始化购物车事件
     */
    initCart: function () {
      var that = this;

      // 点击加入购物车
      this.$('.js-cart-create').click(function () {
        if (!that.checkSkusSelected()) {
          return;
        }

        that.create(that.getCartData());
      });

      // 点击立即购买
      this.$('.js-order-create').click(function () {
        if (!that.checkSkusSelected()) {
          return;
        }

        that.createAndPay(that.getCartData());
      });

      // 点击更新购物车
      this.$('.js-cart-update').click(function () {
        if (!that.checkSkusSelected()) {
          return;
        }

        var data = that.getCartData();
        $.ajax({
          url: $.url('carts/update'),
          data: data,
          type: 'post',
          loading: true,
          dataType: 'json'
        }).done(function (ret) {
          $.msg(ret, function () {
            if (ret.code === 1) {
              window.location.reload();
            }
          });
        });
      });
    },

    /**
     * 初始化SKU选择器
     */
    initSkuSelector: function () {
      var that = this;

      this.$el.on('click', '.sku-attr', function () {
        var sku = $(this);

        // 买完的不能选择
        if (sku.hasClass('sku-attr-sold-out')) {
          return;
        }

        if (sku.hasClass('active')) {
          $(this).removeClass('active');
        } else {
          $(this).addClass('active').siblings('.active').removeClass('active');
        }
        that.updateSkuData();
      });

      // 如果初始化时有选中的SKU,需要更新数量等信息
      if (this.selectedSkuId) {
        this.updateSkuData();
      }
    },

    /**
     * 初始化数量选择器
     */
    initSpinner: function () {
      this.$('.spinner-button').click(function () {
        var btn = $(this);
        var input = btn.parent().find('.spinner-input');
        var oldValue = input.val();
        var newVal = 0;

        if (btn.hasClass('spinner-plus')) {
          newVal = parseInt(oldValue, 10) + 1;
        } else if (oldValue > 1) {
          newVal = parseInt(oldValue, 10) - 1;
        } else {
          newVal = 1;
        }
        input.val(newVal).change();
      });

      this.$('.spinner-input').change(function () {
        var $input = $(this);
        var val = parseInt($input.val(), 10);
        if (isNaN(val) || val < 1) {
          val = 1;
        }

        var maxTarget = $input.data('max-target');
        if (maxTarget) {
          var max = parseInt($(maxTarget).html(), 10);
          if (val > max) {
            val = max;
          }
        }

        $(this).val(val);
      });
    },

    /**
     * 获取加入购物车的数据
     */
    getCartData: function () {
      return this.$el.find(':input').serializeArray();
    },

    /**
     * 加入购物车
     */
    create: function (cart, fn) {
      // 兼容通过serializeArray获取数组的情况
      if ($.isArray(cart)) {
        var data = {};
        $.each(cart, function (i, param) {
          data[param.name] = param.value;
        });
        cart = data;
      }

      var e = $.Event('cart:create', {
        picker: this,
        cart: cart
      });
      $(document).trigger(e);
      if (e.isDefaultPrevented()) {
        return;
      }

      var that = this;
      cart = $.extend({quantity: 1}, cart);
      $.ajax({
        url: $.url('carts/create'),
        data: cart,
        type: 'post',
        loading: true,
        dataType: 'json'
      }).done(function (ret) {
        if (ret.code === unauthorized) {
          window.location = $.url('users/login', {next: window.location.href});
        } else if (fn) {
          fn(ret);
        } else {
          $.msg(ret, delayLong);
        }

        // 关闭选择器
        if (ret.code === 1) {
          that.close();
        }

        // 购物车数量更新
        if (ret.code === 1 && ret.found === false) {
          var $num = $('.js-product-cart-num');
          var num = parseInt($num.html(), 10);
          if (isNaN(num)) {
            num = 0
          }
          $num.html(num + 1);
        }
      });
    },

    /**
     * 加入购物车并进入下单页面
     */
    createAndPay: function (cart) {
      if ($.isArray(cart)) {
        cart.push({
          name: 'temp',
          value: 1
        });
      } else {
        cart.temp = 1;
      }
      this.create(cart, function (ret) {
        if (ret.code > 0) {
          window.location = $.url('orders/new', {
            cartId: ret.data.id,
            showwxpaytitle: '1'
          });
        } else {
          $.msg(ret, delayLong);
        }
      });
    },

    /**
     * 更新剩下库存,价格等信息
     */
    updateSkuData: function () {
      // 获取已选择的参数
      var attrIds = [];
      this.$('.sku-attr.active').each(function () {
        attrIds.push($(this).data('id').toString());
      });

      // 当前选择的规格的总库存
      var quantity = 0;

      // 符合当前选择的规格
      var validSkus = [];

      if (attrIds.length === 0) {
        quantity = this.data.quantity;
      } else {
        for (var j in this.skus) {
          if (this.contains(this.skus[j].attrIds, attrIds)) {
            quantity += parseInt(this.skus[j].quantity, 10);
            validSkus.push(this.skus[j]);
          }
        }
      }

      // 更新剩下库存
      this.$('.js-quantity-left').html(quantity);

      // 如果所选数量超过库存,更改为库存数量
      var $quantity = this.$('.js-quantity');
      var inputQuantity = parseInt($quantity.val(), 10);
      if (inputQuantity > quantity) {
        $quantity.val(quantity);
      }

      // 将库存为0的规格禁用
      this.updateNoQuantity();

      // 一个都没有选中,说明取消了选择
      if (validSkus.length === 0) {
        validSkus = this.skus;
      }

      // 更新价格范围
      $('.js-product-price').html(this.getPriceText(validSkus));

      // 如果只剩下一个规格,说明已经选完了
      this.$('.js-sku-id').val(validSkus[0].id);
    },

    getPriceText: function (validSkus) {
      var text = '';
      var price = this.getPriceRange(validSkus);
      var score = this.getScoreRange(validSkus);

      if (price !== '0.00') {
        text += '￥' + price;
      }

      if (price !== '0.00' && score !== 0) {
        text += ' + ';
      }

      if (score !== 0) {
        text += score + '积分';
      }

      return text;
    },

    getPriceRange: function (validSkus) {
      if (validSkus.length === 1) {
        return validSkus[0].price;
      }

      var min = parseFloat(validSkus[0].price);
      var max = min;
      for (var i in validSkus) {
        if (Object.prototype.hasOwnProperty.call(validSkus, i)) {
          var price = parseFloat(validSkus[i].price);
          if (price < min) {
            min = price;
          }
          if (price > max) {
            max = price;
          }
        }
      }

      if (min === max) {
        return min.toFixed(2);
      } else {
        return min.toFixed(2) + '~' + max.toFixed(2);
      }
    },

    getScoreRange: function (validSkus) {
      if (validSkus.length === 1) {
        return parseInt(validSkus[0].score, 10);
      }

      var min = parseInt(validSkus[0].score, 10);
      var max = min;
      for (var i in validSkus) {
        if (Object.prototype.hasOwnProperty.call(validSkus, i)) {
          var score = parseInt(validSkus[i].score, 10);
          if (score < min) {
            min = score;
          }
          if (score > max) {
            max = score;
          }
        }
      }

      if (min === max) {
        return min;
      } else {
        return min + '~' + max;
      }
    },

    updateNoQuantity: function () {
      $('.js-sku-attr').removeClass('sku-attr-sold-out');

      // 如果某个属性没有库存,禁用该属性
      var attrQuantities = {};
      $.each(this.skus, function (i, sku) {
        $.each(sku.attrIds, function (i, attrId) {
          if (!attrQuantities[attrId]) {
            attrQuantities[attrId] = 0;
          }
          attrQuantities[attrId] += parseInt(sku.quantity, 10);
        });
      });

      $.each(attrQuantities, function (attrId, quantity) {
        if (quantity === 0) {
          $('.js-sku-attr[data-id=' + attrId + ']').addClass('sku-attr-sold-out');
        }
      });

      // 找到选择的
      var selectedAttrIds = [];
      this.$('.js-sku-attr.active').each(function () {
        selectedAttrIds.push($(this).data('id').toString());
      });

      $.each(this.skus, function (i, sku) {
        if (sku.quantity != '0') {
          return;
        }
        $.each(selectedAttrIds, function (i, selectedAttrId) {
          if ($.inArray(selectedAttrId, sku.attrIds) !== -1) {
            $.each(sku.attrIds, function (i, attrId) {
              if (attrId == selectedAttrId) {
                return;
              }
              $('.js-sku-attr[data-id=' + attrId  + ']').addClass('sku-attr-sold-out');
            });
          }
        });
      });
    },

    /**
     * 检查各个SKU是否都选中了
     */
    checkSkusSelected: function () {
      var selected = true;
      this.$('.sku-item').each(function () {
        if ($(this).find('.sku-attr.active').length === 0) {
          $.err('请选择"' + $(this).find('.sku-name').html() + '"');
          selected = false;
          return false;
        }
        return true;
      });
      return selected;
    },

    /**
     * 判断一个数组是否在另一个数组中
     */
    contains: function (container, array) {
      for (var i in array) {
        if ($.inArray(array[i], container) === -1) {
          return false;
        }
      }
      return true;
    },

    /**
     * 判断是否为限制买一个,且单个SKU配置
     *
     * @returns {boolean}
     */
    canQuickCreate: function () {
      return this.skus.length === 1 && this.data.limitation === 1;
    }
  });

  return new Products();
});
