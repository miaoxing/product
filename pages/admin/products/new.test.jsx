import Page from './new';
import {fireEvent, render, screen, waitFor} from '@testing-library/react';
import {MemoryRouter} from 'react-router';
import {app} from '@mxjs/app';
import $, {Ret} from 'miaoxing';
import {bootstrap, createPromise, setUrl, resetUrl} from '@mxjs/test';

bootstrap();

describe('admin/products', () => {
  beforeEach(() => {
    setUrl('admin/products/new');
    app.page = {
      collection: 'admin/products',
      index: false,
    };
  });

  afterEach(() => {
    resetUrl();
    app.page = {};
  });

  test('form', async () => {
    const promise = createPromise();
    const promise2 = createPromise();
    const promise3 = createPromise();
    const promise4 = createPromise();

    $.http = jest.fn()
      // 读取分类
      .mockImplementationOnce(() => promise3.resolve({
        ret: Ret.suc({
          data: [{
            id: 1,
            name: '测试分类',
            children: [],
          }],
        }),
      }))
      // 读取运费模板
      .mockImplementationOnce(() => promise2.resolve({
        ret: Ret.suc({
          data: [{
            id: 1,
            name: '测试模板',
          }],
        }),
      }))
      // 读取默认数据
      .mockImplementationOnce(() => promise.resolve({
        ret: Ret.suc({
          data: {
            images: [],
            categoriesProducts: [],
            skus: [],
            sort: 50,
            shippingTplId: 1, // TODO 前台 API 设置
            spec: {
              specs: [
                {
                  id: 3,
                  name: '默认',
                  values: [
                    {
                      id: 4,
                      name: '默认',
                    },
                  ],
                },
              ],
            },
          },
        }),
      }))
      // 提交
      .mockImplementationOnce(() => promise4.resolve({
        ret: Ret.suc(),
      }));

    const {container, getByLabelText} = render(<MemoryRouter>
      <Page/>
    </MemoryRouter>);

    await Promise.all([promise, promise2]);
    expect($.http).toHaveBeenCalledTimes(3);
    expect($.http).toMatchSnapshot();

    // 看到表单加载了数据
    await waitFor(() => expect(getByLabelText('顺序').value).toBe('50'), {
      timeout: 5000,
      onTimeout: (error) => {
        console.error('waitFor timeout', error);
      },
    });
    expect(getByLabelText('名称').value).toBe('');

    // 提交表单
    fireEvent.change(getByLabelText('名称'), {target: {value: '测试商品'}});
    fireEvent.change(container.querySelector('#skus_4_price'), {target: {value: '1.23'}});
    fireEvent.change(container.querySelector('#skus_4_stockNum'), {target: {value: '3'}});

    fireEvent.click(screen.getByText('提 交'));

    await Promise.all([promise4]);
    expect($.http).toHaveBeenCalledTimes(4);
    expect($.http).toMatchSnapshot();
  });
});
