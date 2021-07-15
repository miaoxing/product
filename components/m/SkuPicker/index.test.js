import SkuPicker from './index';
import {render, fireEvent} from '@testing-library/react';
import Taro from '@tarojs/taro';
import {createProduct, createSingleSkuProduct} from '@miaoxing/product/test-utils';

describe('SkuPicker', () => {
  test('basic', async () => {
    const {container} = render(<SkuPicker product={createSingleSkuProduct()}/>);

    expect(container).toMatchSnapshot();
  });

  test('close', () => {
    const handleClose = jest.fn();
    const {getByText} = render(<SkuPicker isOpened={true} onClose={handleClose} product={createSingleSkuProduct()}/>);

    const close = getByText('×');
    fireEvent.click(close);

    expect(handleClose).toBeCalledTimes(1);
    expect(handleClose.mock.calls).toMatchSnapshot();
  });

  test('preview image', () => {
    const {container} = render(<SkuPicker product={createSingleSkuProduct()}/>);

    // 点击查看图片
    const image = container.querySelector('taro-image-core');

    Taro.previewImage = jest.fn();

    fireEvent.click(image);
    expect(Taro.previewImage).toBeCalled();
    expect(Taro.previewImage.mock.calls).toMatchSnapshot();
  });

  test('hide default spec', () => {
    const product = createSingleSkuProduct({
      spec: {
        isDefault: true,
      },
    });

    const {queryByText} = render(<SkuPicker product={product}/>);

    expect(queryByText('默认')).toBeNull();
  });

  test('default selected if only one spec value', () => {
    const {getByText} = render(<SkuPicker product={createSingleSkuProduct()}/>);

    const first = getByText('默认值');

    expect(first.className).toContain('active');
  });

  test('dont disable sold out spec if selected', () => {
    const {getByText} = render(<SkuPicker product={createProduct()} selectedValueIds={[1, 3]}/>);

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
    const {queryByText} = render(<SkuPicker product={createProduct({maxOrderQuantity: 11})}/>);

    expect(queryByText(/每人限购 11 件/)).not.toBeNull();
  });
});
