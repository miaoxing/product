import SkuPicker from './index';
import {render} from '@testing-library/react';

describe('SkuPicker', () => {
  test('basic', async () => {
    const {container} = render(<SkuPicker product={{
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
      createCartOrOrder: {
        code: 0,
        message: 'ok',
        createCart: {
          code: 0,
          message: 'ok',
        },
        createOrder: {
          code: 0,
          message: 'ok',
        },
      },
    }}/>);

    expect(container).toMatchSnapshot();
  });
});
