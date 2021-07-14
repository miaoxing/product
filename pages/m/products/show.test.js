import Show from './show';
import {render, waitFor} from '@testing-library/react';
import $, {Ret} from 'miaoxing';
import {createPromise, bootstrap, setUrl, resetUrl} from '@mxjs/test';

bootstrap();

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

    const product = {
      id: 82,
      name: 'alias commodi ipsam',
      intro: '',
      'minPrice': '16.64',
      'minMarketPrice': '0',
      'minScore': 0,
      'stockNum': 51,
      'soldNum': 0,
      'image': 'https://via.placeholder.com/640x640.png/005544?text=eos',
      'startAt': null,
      'endAt': null,
      'maxOrderQuantity': 0,
      'isAllowAddCart': true,
      'isAllowCoupon': true,
      'isRequireAddress': true,
      'isAllowComment': true,
      'configs': [],
      'deletedAt': null,
      'images': [{'url': 'https://via.placeholder.com/640x640.png/005544?text=eos', 'description': ''}],
      'spec': {'isDefault': true, 'specs': [{'id': 1, 'name': '默认', 'values': [{'id': 1, 'name': '默认'}]}]},
      'detail': {'content': '<p>Alice in a hurried nervous manner, smiling at everything about her, to pass away the moment she quite forgot you didn&#39;t like cats.&#39; &#39;Not like cats!&#39; cried the Gryphon. &#39;It&#39;s all his fancy, that: he.</p>'},
      'skus': [{
        'id': 2,
        'specValueIds': [1],
        'price': '16.64',
        'marketPrice': '0',
        'score': 0,
        'stockNum': 51,
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
});
