import React from 'react';
import {Card, Radio} from 'antd';
import PropTypes from 'prop-types';
import ColorPicker from '@mxjs/a-color-picker';
import {FormItem} from '@mxjs/a-form';

const ProductSearchConfig = ({propName}) => {
  return (
    <Card title="商品搜索">
      <FormItem label="提示文字" name={propName(['placeholder'])}/>

      <FormItem label="输入框形状" name={propName(['inputShape'])}>
        <Radio.Group>
          <Radio value="rect">方形</Radio>
          <Radio value="round">圆角</Radio>
          <Radio value="circle">圆形</Radio>
        </Radio.Group>
      </FormItem>

      <FormItem label="文字位置" name={propName(['inputStyle', 'textAlign'])}>
        <Radio.Group>
          <Radio value="left">居左</Radio>
          <Radio value="center">居中</Radio>
        </Radio.Group>
      </FormItem>

      <FormItem label="背景颜色" name={propName(['style', 'backgroundColor'])}>
        <ColorPicker/>
      </FormItem>

      <FormItem label="输入框文字颜色" name={propName(['inputStyle', 'color'])}>
        <ColorPicker/>
      </FormItem>

      <FormItem label="输入框背景颜色" name={propName(['inputStyle', 'backgroundColor'])}>
        <ColorPicker/>
      </FormItem>
    </Card>
  );
};

ProductSearchConfig.propTypes = {
  propName: PropTypes.func,
};

export default ProductSearchConfig;
