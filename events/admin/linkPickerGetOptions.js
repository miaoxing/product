import Icon from '@mxjs/icons';
import ProductPicker from './ProductPicker';
import CategoryPicker from './CategoryPicker';

export default [
  {
    value: 'product',
    label: '商品',
    children: [
      {
        value: 'articles/[id]',
        label: <>商品详情 <Icon type="mi-popup"/></>,
        inputLabel: '商品详情',
        picker: ProductPicker,
        pickerLabel: ProductPicker.Label,
      },
      {
        value: 'categories/[id]',
        label: <>商品分类 <Icon type="mi-popup"/></>,
        inputLabel: '商品分类',
        picker: CategoryPicker,
        pickerLabel: CategoryPicker.Label,
      },
    ],
  },
];
