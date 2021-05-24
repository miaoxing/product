import React, {useState, useEffect} from 'react';
import {Card, Radio, Select, TreeSelect, InputNumber, Switch} from 'antd';
import PropTypes from 'prop-types';
import {FormItem, useForm} from '@mxjs/a-form';
import $ from 'miaoxing';
import api from '@mxjs/api';
import ConfigProductPicker from './ConfigProductPicker';
import ColorPicker from '@mxjs/a-color-picker';

const ProductConfig = ({propName}) => {
  // 加载商品分类
  const [categories, setCategories] = useState([]);
  useEffect(() => {
    api.getMax('categories', {loading: true}).then(ret => {
      if (ret.isSuc()) {
        setCategories(ret.data.map(category => ({
          value: category.id,
          title: category.name,
          children: category.children.map(subCategory => ({
            value: subCategory.id,
            title: subCategory.name,
          })),
        })));
      } else {
        $.ret(ret);
      }
    });
  }, []);

  const form = useForm();
  const [source, setSource] = useState(form.getFieldValue(['components'].concat(propName(['source']))));

  return (
    <Card title="商品列表">
      <FormItem label="商品来源" name={propName(['source'])}>
        <Radio.Group onChange={e => {
          setSource(e.target.value);
        }}>
          <Radio value="all">全部</Radio>
          <Radio value="category">指定分类</Radio>
          <Radio value="product">指定商品</Radio>
        </Radio.Group>
      </FormItem>

      {source === 'category' && <FormItem label="选择分类" name={propName(['categoryIds'])}>
        <TreeSelect
          showSearch
          showArrow
          allowClear
          multiple
          treeDefaultExpandAll
          placeholder="请选择"
          treeData={categories}
        />
      </FormItem>}

      {source === 'product' && <FormItem label="选择商品" name={propName(['productIds'])}>
        <ConfigProductPicker/>
      </FormItem>}

      {source !== 'product' && <FormItem label="排列" name={propName(['orderBy'])}>
        <Select>
          <Select.Option value="">顺序从大到小</Select.Option>
          <Select.Option value="soldNum desc">销量从高到底</Select.Option>
          <Select.Option value="minPrice desc">价格从高到低</Select.Option>
          <Select.Option value="minPrice asc">价格从低到高</Select.Option>
        </Select>
      </FormItem>}

      {source !== 'product' && <FormItem label="数量" name={propName(['num'])} extra="最多展示 20 个">
        <InputNumber min={1} max={20} style={{width: '100%'}}/>
      </FormItem>}

      <FormItem label="样式" name={propName(['tpl'])}>
        <Select>
          <Select.Option value={1}>1 个 1 行，大图</Select.Option>
          <Select.Option value={2}>2 个 1 行，中图</Select.Option>
          <Select.Option value={3}>3 个 1 行，小图</Select.Option>
          <Select.Option value={4}>1 个 1 行，小图</Select.Option>
        </Select>
      </FormItem>

      <FormItem label="背景颜色" name={propName(['style', 'backgroundColor'])}>
        <ColorPicker/>
      </FormItem>

      <FormItem label="显示市场价" name={propName(['showMarketPrice'])} valuePropName="checked">
        <Switch/>
      </FormItem>
    </Card>
  );
};

ProductConfig.propTypes = {
  propName: PropTypes.func,
};

export default ProductConfig;
