import React from 'react';
import {ShoppingOutlined, SearchOutlined} from '@ant-design/icons';
import ProductSearchPreview from './ProductSearchPreview';
import ProductSearchConfig from './ProductSearchConfig';
import ProductPreview from './ProductPreview';
import ProductConfig from './ProductConfig';

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
  {
    type: 'product',
    name: '商品列表',
    icon: <ShoppingOutlined/>,
    preview: ProductPreview,
    config: ProductConfig,
    default: {
      source: 'all',
      categoryIds: [],
      productIds: [],
      orderBy: '',
      num: 10,
      tpl: 2,
      showMarketPrice: true,
    },
  },
];
