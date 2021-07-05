import Index from './index';
import {render} from '@testing-library/react';
import {MemoryRouter} from 'react-router';
import $ from 'miaoxing';
import {bootstrap, createPromise, setUrl, resetUrl} from '@mxjs/test';
import {app} from '@mxjs/app';

bootstrap();

describe('admin/products', () => {
  beforeEach(function () {
    setUrl('admin/products');
    app.page = {
      collection: 'admin/products',
      index: true,
    };
  });

  afterEach(() => {
    resetUrl();
    app.page = {};
  });

  test('index', async () => {
    const promise = createPromise();

    $.http = jest.fn()
      // 读取列表数据
      .mockImplementationOnce(() => promise.resolve({
        ret: {
          code: 1,
          data: [
            {
              id: 1,
              name: '商品1',
              minPrice: 3,
              stockNum: 4,
              isListing: true,
              createdAt: '2020-01-01 00:00:00',
            },
          ],
        },
      }));

    const {findByText} = render(<MemoryRouter>
      <Index/>
    </MemoryRouter>);

    await findByText('商品1');
    await findByText('￥3');

    await Promise.all([promise]);
    expect($.http).toHaveBeenCalledTimes(1);
    expect($.http).toMatchSnapshot();
  });
});
