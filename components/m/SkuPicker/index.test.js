import SkuPicker from './index';
import {render, fireEvent} from '@testing-library/react';
import {Ret} from 'miaoxing';
import {useState} from 'react';

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
});
