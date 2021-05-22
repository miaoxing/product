import React from 'react';
import {SearchOutlined} from '@ant-design/icons';
import ProductSearchPreview from './ProductSearchPreview';
import ProductSearchConfig from './ProductSearchConfig';

export default [
  {
    type: 'product-search',
    name: '商品搜索',
    icon: <SearchOutlined/>,
    preview: ProductSearchPreview,
    config: ProductSearchConfig,
    default: {
      inputShape: 'round',
      style: {
        backgroundColor: '#f8f8f8',
      },
      inputStyle: {
        color: '#aaaaaa',
        backgroundColor: '#ffffff',
      },
    },
  },
];
