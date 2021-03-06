/**
 * @share [id]/edit
 */
import React, {useEffect, useRef, useState} from 'react';
import {CListBtn} from '@mxjs/a-clink';
import {Page, PageActions} from '@mxjs/a-page';
import {Form, FormItem, FormAction, Select} from '@mxjs/a-form';
import {Divider, Radio, Switch, AutoComplete, TreeSelect} from 'antd';
import DateRangePicker from '@mxjs/a-date-range-picker';
import Upload from '@mxjs/upload';
import Sku from '../../../components/Sku';
import api from '@mxjs/api';
import $ from 'miaoxing';
import {FormUeditor} from '@mxjs/ueditor';

export default () => {
  const skuRef = useRef();
  const categoryProductIds = useRef({});
  const form = useRef();

  // 加载运费模板
  const [shippingTpls, setShippingTpl] = useState([]);
  useEffect(() => {
    api.getMax('shipping-tpls', {loading: true}).then(ret => {
      if (ret.isSuc()) {
        setShippingTpl(ret.data);
        if (ret.data.length) {
          form.current.setFieldsValue({shippingTplId: ret.data[0].id});
        }
      } else {
        $.ret(ret);
      }
    });
  }, []);

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

  return (
    <Page>
      <PageActions>
        <CListBtn/>
      </PageActions>

      <Form
        formRef={form}
        afterLoad={ret => {
          // 将商品分类关联表的 id 缓存起来，以便提交时设置回去
          ret.data.categoryIds = ret.data.categoriesProducts.map(categoryProduct => {
            categoryProductIds.current[categoryProduct.categoryId] = categoryProduct.id;
            return categoryProduct.categoryId;
          });

          // 图片组件只传入必要的字段，后台的返回的 type 和图片组件的 type 含义不同，传入将出错
          ret.data.images = ret.data.images.map(image => {
            return {id: image.id, url: image.url};
          });

          // 如果运费模板先加载完，使用第一个值
          if (shippingTpls.length) {
            ret.data.shippingTplId = shippingTpls[0].id;
          }

          // TODO 未加载完成就退出页面，则不调用 afterLoad
          skuRef.current && skuRef.current.setData(ret.data);
        }}
        beforeSubmit={values => {
          values = skuRef.current.getData(values);

          // 将商品分类关联表的 id 设置回去
          values.categoriesProducts = values.categoryIds.map(categoryId => ({
            id: categoryProductIds.current[categoryId] || undefined,
            categoryId: categoryId,
          }));
          delete values.categoryIds;

          if (values.spec.specs.length === 0) {
            $.err('请至少填写一个规格');
            return false;
          }

          return values;
        }}
      >
        <FormItem label="名称" name="name" required/>

        <FormItem label="简短描述" name="intro"/>

        <FormItem
          label="图片"
          name="images"
          extra="支持.jpg .jpeg .bmp .gif .png格式照片，最多上传 9 张图片"
          valuePropName="fileList"
          wrapperCol={{span: 12}}
        >
          <Upload
            url={$.apiUrl('files', {type: 'image'})}
            max={9}
          />
        </FormItem>

        <FormItem label="分类" name="categoryIds">
          <TreeSelect
            showSearch
            showArrow
            allowClear
            multiple
            treeDefaultExpandAll
            placeholder="请选择"
            treeData={categories}
          />
        </FormItem>

        <Sku ref={skuRef}/>

        <FormItem label="运费模板" name="shippingTplId">
          <Select options={shippingTpls} labelKey="name" valueKey="id"/>
        </FormItem>

        <FormUeditor label="描述" name={['detail', 'content']}/>

        <Divider/>

        <FormItem label="是否上架" name="isListing" valuePropName="checked">
          <Switch/>
        </FormItem>

        <FormItem label="上架时间" name="_startAt">
          <DateRangePicker showTime names={['startAt', 'endAt']} format="YYYY-MM-DD HH:mm:00"/>
        </FormItem>

        <FormItem label="最大购买数量" name="maxOrderQuantity" type="number" extra="0 表示无限制"/>

        <FormItem label="“数量”名称" name={['configs', 'quantityName']} extra="例如“人数”"/>

        <FormItem label="单位" name={['configs', 'unit']} extra='默认为"件"'>
          <AutoComplete
            options={[
              {value: '件'},
              {value: '杯'},
              {value: '只'},
              {value: '罐'},
              {value: '盒'},
              {value: '人'},
              {value: '个'},
            ]}
          />
        </FormItem>

        <FormItem label="是否隐藏销量" name={['configs', 'hideSoldNum']} valuePropName="checked">
          <Switch/>
        </FormItem>

        <FormItem label="库存计数" name="decStockMode">
          <Radio.Group>
            <Radio value={1}>付款减库存</Radio>
            <Radio value={2}>拍下减库存</Radio>
          </Radio.Group>
        </FormItem>

        <FormItem label="是否可加入购物车" name="isAllowAddCart" valuePropName="checked" initialValue={true}>
          <Switch/>
        </FormItem>

        <FormItem label="是否可使用优惠券" name="isAllowCoupon" valuePropName="checked" initialValue={true}>
          <Switch/>
        </FormItem>

        <FormItem label="支付时是否需填写地址" name="isRequireAddress" valuePropName="checked" initialValue={true}>
          <Switch/>
        </FormItem>

        <FormItem label="支付时是否允许留言" name="isAllowComment" valuePropName="checked" initialValue={true}>
          <Switch/>
        </FormItem>

        <FormItem label="顺序" name="sort" type="number"/>

        <FormItem name="id" type="hidden"/>

        <FormAction/>
      </Form>
    </Page>
  );
};
