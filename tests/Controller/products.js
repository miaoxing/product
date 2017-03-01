describe('mall/product 检查页面是否可以访问', function () {
  before(function () {
    casper.start();
  });

  ['index', 'show'].forEach(function (action) {
    it('可以访问' + action, function () {
      casper.thenOpen(casper.config.baseUrl + '/mall/product/' + action, function (response) {
        response.status.should.not.equal(500);
      });
    });
  });
});

describe('mall/product/index 查看商品列表', function () {
  before(function () {
    casper.start(casper.config.baseUrl + '/mall/product/index');
  });

  it('显示"所有商品"', function () {
    casper.then(function () {
      '.hm-nav-center'.should.be.inDOM.and.be.visible.and.contain.text('所有商品');
    });
  });
});

describe('mall/product/show?id=1 正常的商品', function () {
  before(function () {
    casper.start(casper.config.baseUrl + '/mall/product/show?id=1');
  });

  it('显示商品信息', function () {
    casper.then(function () {
      '.product-title'.should.contain.text('测试商品1');

      '.product-price'.should.contain.text('0.01');

      '.product-original-price'.should.contain.text('0.02');

      '.product-quantity'.should.contain.text('100');

      '.product-detail'.should.contain.text('测试商品1的详情');

      '.create-cart-and-pay'.should.be.inDOM.and.contain.text('立即购买');

      '.create-cart'.should.be.inDOM.and.contain.text('加入购物车');

      '.spinner-input'.should.have.attr('value').and.contains('1');
    });
  });

  it('点击+数量变大', function () {
    casper.thenClick('.spinner-plus', function () {
      "$('#quantity').val()".should.evaluate.to.equal('2');
    });
  });

  it('点击-数量变小', function () {
    casper.thenClick('.spinner-minus', function () {
      "$('#quantity').val()".should.evaluate.to.equal('1');
    });
  });
});

describe('mall/product/show?id=1 加入购物车', function () {
  it('点击加入购物车,弹出提示', function () {
    casper.start(casper.config.baseUrl + '/index/login');

    casper.thenOpen(casper.config.baseUrl + '/mall/product/show?id=1');

    casper.thenClick('.create-cart');

    casper.waitForSelector('.tips', function() {
      '.tips'.should.contain.text('添加成功');
    });
  });

  it('点击立即购买,跳转到下订单页面', function () {
    casper.thenClick('.create-cart-and-pay');

    casper.waitForUrl(/orders\/new\?cartId=(.+?)&showwxpaytitle=1/);
  });
});

describe('mall/product/show 查看不存在的商品', function () {
  before(function () {
    casper.start(casper.config.baseUrl + '/mall/product/show?id=not-found&weidebug=0');
  });

  it('显示"页面不存在"', function () {
    casper.then(function () {
      'h1'.should.be.inDOM.and.be.visible.and.contain.text('页面不存在');
    });
  });
});

describe('mall/product/show?id=2 未开始商品', function () {
  before(function () {
    casper.start(casper.config.baseUrl + '/mall/product/show?id=2');
  });

  it('显示即将开始', function () {
    casper.then(function () {
      '.product-footer-bar > button'.should.contain.text('即将开始');

      var info = this.getElementInfo('.product-footer-bar > button');

      info.attributes.should.have.property('class').and.contains('disabled');
    });
  });

  it('显示倒计时', function () {
    casper.then(function () {
      '.product-countdown'.should.be.inDOM.contain.text('天');
    })
  });
});

describe('mall/product/show?id=3 数量为0商品', function () {
  before(function () {
    casper.start(casper.config.baseUrl + '/mall/product/show?id=3');
  });

  it('显示抢光了…', function () {
    casper.then(function () {
      '.product-footer-bar > button'.should.contain.text('抢光了…');

      var info = this.getElementInfo('.product-footer-bar > button');

      info.attributes.should.have.property('disabled').and.equal('');
    });
  });
});

describe('mall/product/show?id=4 到了下线时间的商品', function () {
  before(function () {
    casper.start(casper.config.baseUrl + '/mall/product/show?id=4');
  });

  it('显示抢光了…', function () {
    casper.then(function () {
      '.product-footer-bar > button'.should.contain.text('抢光了…');

      var info = this.getElementInfo('.product-footer-bar > button');

      info.attributes.should.have.property('disabled').and.equal('');
    });
  });
});
