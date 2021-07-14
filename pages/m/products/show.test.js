import Show from './show';
import {render, waitFor, fireEvent} from '@testing-library/react';
import $, {Ret} from 'miaoxing';
import {createPromise, bootstrap, setUrl, resetUrl} from '@mxjs/test';
import Taro from '@tarojs/taro';

bootstrap();

const product = {
  'id': 81,
  'name': '这公会车车挂这公会车车挂非常防虫扥东这公会车车挂这公会车车挂非常防虫扥东',
  'intro': '欢迎亲临门店体验',
  'minPrice': '9',
  'minMarketPrice': '10',
  'minScore': 0,
  'stockNum': 27,
  'soldNum': 0,
  'image': 'http://dev.test.com/uploads/1/210610/002439539409.png',
  'startAt': '2021-06-24 11:17:00',
  'endAt': null,
  'maxOrderQuantity': 11,
  'isAllowAddCart': true,
  'isAllowCoupon': true,
  'isRequireAddress': true,
  'isAllowComment': true,
  'configs': [],
  'deletedAt': null,
  'images': [{
    'url': 'http://dev.test.com/uploads/1/210610/002439539409.png',
    'description': '',
  }, {
    'url': 'http://dev.test.com/uploads/1/210610/000924427937.jpg',
    'description': '',
  }, {
    'url': 'http://dev.test.com/uploads/1/210610/000918792585.jpg',
    'description': '',
  }, {'url': 'http://dev.test.com/uploads/1/210610/002904468645.png', 'description': ''}],
  'spec': {
    'isDefault': false,
    'specs': [{
      'id': 2,
      'name': '尺寸',
      'values': [{'id': 2, 'name': 'S'}, {'id': 3, 'name': 'M'}, {'id': 4, 'name': 'L'}],
    }, {'id': 3, 'name': '颜色', 'values': [{'id': 5, 'name': '红色'}, {'id': 7, 'name': '蓝色'}]}],
  },
  'detail': {'content': '<p><img src="http://dev.test.com/uploads/images/20210528/1622199614338752.png" alt="14 - 3 (2).png"/></p><p>Alice: he had a cons<span style="color: #548DD4;">ultation about this, and after a few minutes it puffed away without speaking, but at any r</span>ate,&#39; sai<span style="background-color: #F79646;">d Alice: &#39;allo</span>w me to introduce it.&#39; &#39;I don&#39;t know what a long way. So they got.</p>'},
  'skus': [{
    'id': 43,
    'specValueIds': [2, 5],
    'price': '9',
    'marketPrice': '10',
    'score': 0,
    'stockNum': 0,
    'soldNum': 0,
    'image': '',
  }, {
    'id': 45,
    'specValueIds': [2, 7],
    'price': '11',
    'marketPrice': '12',
    'score': 0,
    'stockNum': 0,
    'soldNum': 0,
    'image': '',
  }, {
    'id': 46,
    'specValueIds': [3, 5],
    'price': '12',
    'marketPrice': '13',
    'score': 0,
    'stockNum': 12,
    'soldNum': 0,
    'image': '',
  }, {
    'id': 48,
    'specValueIds': [3, 7],
    'price': '14',
    'marketPrice': '15',
    'score': 0,
    'stockNum': 0,
    'soldNum': 0,
    'image': '',
  }, {
    'id': 49,
    'specValueIds': [4, 5],
    'price': '15',
    'marketPrice': '16',
    'score': 0,
    'stockNum': 15,
    'soldNum': 0,
    'image': '',
  }, {
    'id': 51,
    'specValueIds': [4, 7],
    'price': '17',
    'marketPrice': '18',
    'score': 0,
    'stockNum': 0,
    'soldNum': 0,
    'image': '',
  }],
  'createCartOrOrder': {
    'message': '操作成功',
    'code': 0,
    'createCart': {'message': '可以加入购物车', 'code': 0},
    'createOrder': {'message': '可以购买', 'code': 0},
  },
};

describe('Show', () => {
  beforeEach(() => {
    setUrl('/products/show?id=1');
  });

  afterEach(() => {
    resetUrl();
  });

  test('basic', async () => {
    const promise = createPromise();
    const promise2 = createPromise();
    const promise3 = createPromise();

    $.http = jest.fn()
      .mockImplementationOnce(() => promise.resolve({
        ret: Ret.suc({
          data: product,
        }),
      }))
      .mockImplementationOnce(() => promise2.resolve({
        ret: Ret.suc({
          data: {
            count: 1,
          },
        }),
      }))
      .mockImplementationOnce(() => promise3.resolve({
        ret: Ret.suc({
          data: {
            isFreeShipping: true,
            rules: [],
          },
        }),
      }));

    const {container, getByText} = render(<Show/>);

    await waitFor(() => {
      expect(getByText('包邮')).not.toBeNull();
    });

    expect(getByText(product.name)).not.toBeNull();

    expect(container).toMatchSnapshot();
    expect($.http).toMatchSnapshot();
  });

  test('action', async () => {
    const promise = createPromise();
    const promise2 = createPromise();
    const promise3 = createPromise();
    const promise4 = createPromise();

    $.http = jest.fn()
      .mockImplementationOnce(() => promise.resolve({
        ret: Ret.suc({
          data: product,
        }),
      }))
      .mockImplementationOnce(() => promise2.resolve({
        ret: Ret.suc({
          data: {
            count: 97,
          },
        }),
      }))
      .mockImplementationOnce(() => promise3.resolve({
        ret: Ret.suc({
          data: {
            isFreeShipping: true,
            rules: [],
          },
        }),
      }))
      .mockImplementationOnce(() => promise4.resolve({
        ret: Ret.suc({
          exists: false,
        }),
      }));

    const {container, getByText, findByText, findAllByText} = render(<Show/>);

    await waitFor(() => {
      expect(getByText('包邮')).not.toBeNull();
    });

    // 点击查看图片
    const image = container.querySelector('taro-image-core');

    Taro.previewImage = jest.fn();

    fireEvent.click(image);
    expect(Taro.previewImage).toBeCalled();
    expect(Taro.previewImage.mock.calls).toMatchSnapshot();

    // 点击选择规格
    const selected = getByText('尺寸 / 颜色');
    fireEvent.click(selected);

    const skuValue1 = await findByText('M');
    fireEvent.click(skuValue1);

    const skuValue2 = await findByText('红色');
    fireEvent.click(skuValue2);

    // 加入购物车
    const createCarts = await findAllByText('加入购物车');
    fireEvent.click(createCarts[1]);

    await promise4;
    expect($.http).toMatchSnapshot();

    // 关闭后显示选中的规格
    await findByText('已选：M / 红色');

    // 购物车数量增加
    await findByText('98');
  });
});
