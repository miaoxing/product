import SkuPicker from './index';
import {render, fireEvent} from '@testing-library/react';
import {Ret} from 'miaoxing';
import Taro from '@tarojs/taro';

const product = {
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
  skus: [
    {
      id: 1,
      specValueIds: [4],
      stockNum: 10,
    },
  ],
  configs: {
    unit: '件',
  },
  createCartOrOrder: Ret.suc({
    createCart: Ret.suc(),
    createOrder: Ret.suc(),
  }),
};

describe('SkuPicker', () => {
  test('basic', async () => {
    const {container} = render(<SkuPicker product={product}/>);

    expect(container).toMatchSnapshot();
  });

  test('close', () => {
    const handleClose = jest.fn();
    const {getByText} = render(<SkuPicker isOpened={true} onClose={handleClose} product={product}/>);

    const close = getByText('×');
    fireEvent.click(close);

    expect(handleClose).toBeCalledTimes(1);
    expect(handleClose.mock.calls).toMatchSnapshot();
  });

  test('preview image', () => {
    const {container} = render(<SkuPicker product={product}/>);

    // 点击查看图片
    const image = container.querySelector('taro-image-core');

    Taro.previewImage = jest.fn();

    fireEvent.click(image);
    expect(Taro.previewImage).toBeCalled();
    expect(Taro.previewImage.mock.calls).toMatchSnapshot();
  });

  test('hide default spec', () => {
    const newSpec = {...product.spec, isDefault: true};
    const newProduct = {...product, spec: newSpec};

    const {queryByText} = render(<SkuPicker product={newProduct}/>);

    expect(queryByText('默认')).toBeNull();
  });

  test('default selected if only one spec value', () => {
    product.spec.specs[0].values[0].name = '默认值';
    const {getByText} = render(<SkuPicker product={product}/>);

    const first = getByText('默认值');

    expect(first.className).toContain('active');
  });
});
