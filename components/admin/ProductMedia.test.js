import ProductMedia from './ProductMedia';
import {render} from '@testing-library/react';

describe('ProductMedia', () => {
  test('basic', () => {
    const result = render(<ProductMedia product={{
      name: 'name',
      image: 'image',
    }}/>);
    expect(result.container).toMatchSnapshot();
  });
});
