import ProductMedia from './ProductMedia';
import {render} from '@testing-library/react';
import {ConfigProvider} from 'antd';

describe('ProductMedia', () => {
  test('basic', () => {
    const result = render(
      <ConfigProvider theme={{hashed: false}}>
        <ProductMedia product={{
          name: 'name',
          image: 'image',
        }}/>
      </ConfigProvider>
    );
    expect(result.container).toMatchSnapshot();
  });
});
