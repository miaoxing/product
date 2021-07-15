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

  test('dont disable sold out spec if selected', () => {
    const newProduct = {
      ...product,
      spec: {
        'isDefault': false,
        'specs': [
          {
            'id': 2,
            'name': '尺寸',
            'values': [{'id': 2, 'name': 'S'}, {'id': 3, 'name': 'M'}],
          },
          {
            'id': 3,
            'name': '颜色',
            'values': [{'id': 5, 'name': '红色'}, {'id': 7, 'name': '蓝色'}],
          },
        ],
      },
      skus: [{
        'id': 1,
        'specValueIds': [2, 5],
        'price': '9',
        'marketPrice': '10',
        'score': 0,
        'stockNum': 0,
        'soldNum': 0,
        'image': '',
      }, {
        'id': 2,
        'specValueIds': [2, 7],
        'price': '11',
        'marketPrice': '12',
        'score': 0,
        'stockNum': 0,
        'soldNum': 0,
        'image': '',
      }, {
        'id': 3,
        'specValueIds': [3, 5],
        'price': '12',
        'marketPrice': '13',
        'score': 0,
        'stockNum': 12,
        'soldNum': 0,
        'image': '',
      }, {
        'id': 4,
        'specValueIds': [3, 7],
        'price': '14',
        'marketPrice': '15',
        'score': 0,
        'stockNum': 0,
        'soldNum': 0,
        'image': '',
      }],
    };

    const {getByText} = render(<SkuPicker product={newProduct} selectedValueIds={[2, 5]}/>);

    // 选中的规格，不禁用
    const size = getByText('S');
    expect(size.className).toContain('active');
    expect(size.className).not.toContain('disabled');

    // 取消选择后，被禁用
    fireEvent.click(size);
    expect(size.className).toContain('disabled');

    // 再次点击，不能选中，依然是禁用状态
    fireEvent.click(size);
    expect(size.className).not.toContain('active');
    expect(size.className).toContain('disabled');
  });

  test('maxOrderQuantity', () => {
    product.maxOrderQuantity = 11;

    const {queryByText} = render(<SkuPicker product={product}/>);

    expect(queryByText(/每人限购 11 件/)).not.toBeNull();
  });
});
