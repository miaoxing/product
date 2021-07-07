import ActionButtonGroup from './ActionButtonGroup';
import {fireEvent, render} from '@testing-library/react';

describe('ActionButtonGroup', () => {
  test('basic', async () => {
    const handleClick = jest.fn();
    const {container, queryByText} = render(
      <ActionButtonGroup
        ret={{
          code: 0,
          createCart: {
            code: 0,
          },
          createOrder: {
            code: 0,
          },
        }}
        onClick={handleClick}
      />,
    );
    expect(container).toMatchSnapshot();

    const createCart = queryByText('加入购物车');
    fireEvent.click(createCart);

    const createOrder = queryByText('立即购买');
    fireEvent.click(createOrder);

    expect(handleClick).toMatchSnapshot();
  });

  test('hide create cart', () => {
    const {container} = render(
      <ActionButtonGroup
        ret={{
          code: 0,
          createCart: {
            code: 1,
          },
          createOrder: {
            code: 0,
          },
        }}
        onClick={() => {
        }}
      />,
    );

    expect(container).toMatchSnapshot();
  });

  test('hide create order', () => {
    const {container} = render(
      <ActionButtonGroup
        ret={{
          code: 0,
          createCart: {
            code: 0,
          },
          createOrder: {
            code: 1,
          },
        }}
        onClick={() => {
        }}
      />,
    );

    expect(container).toMatchSnapshot();
  });

  test('update cart', () => {
    const handleClick = jest.fn();

    const {container, queryByText} = render(
      <ActionButtonGroup
        action="updateCart"
        ret={{
          code: 0,
          createCart: {
            code: 0,
          },
          createOrder: {
            code: 0,
          },
        }}
        onClick={handleClick}
      />,
    );
    expect(container).toMatchSnapshot();

    const button = queryByText('确 定');
    fireEvent.click(button);
    expect(handleClick).toMatchSnapshot();
  });

  test('error', () => {
    const {container} = render(
      <ActionButtonGroup
        ret={{
          code: 1,
          message: '商品已下架',
          createCart: {
            code: 1,
            message: '商品已下架',
            shortMessage: '已下架',
          },
          createOrder: {
            code: 1,
          },
        }}
        onClick={() => {
        }}
      />,
    );
    expect(container).toMatchSnapshot();
  });
});
