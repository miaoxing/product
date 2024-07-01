import { createRef, Component } from 'react';
import {FormContext} from '@mxjs/a-form';
import {Form, Input, Typography} from 'antd';
import SkuBase from '@mxjs/a-sku';
import {Upload} from '@miaoxing/admin';

/**
 * 规格编辑器
 *
 * @internal 待整理完善
 */
export default class Sku extends Component {
  static context = FormContext;

  constructor(props, context) {
    super(props, context);
    this.state = {
      tmpSkuProps: [],
      skuTableColumns: [],
      skuTableDataSource: [],
      skuPropsList: [
        {propertyName: '默认'},
        {propertyName: '尺寸'},
        {propertyName: '颜色'},
      ],
      propVoList: [],
      skuData: [],
    };
    this.skuRef = createRef();
  }

  async addPropApi() {
    return {id: new Date().getTime()};
  }

  async addTagApi() {
    return {id: new Date().getTime()};
  }

  renderBaseFormItem(props, item = {}) {
    return (
      <Form.Item {...props} style={{marginBottom: '0px'}}>
        {item.el || <Input style={{textAlign: 'center'}}/>}
      </Form.Item>
    );
  }

  renderRequired = (name) => (
    <>
      {name}
      {' '}
      <Typography.Text type="warning">*</Typography.Text>
    </>
  )

  setData = (product) => {
    // TODO 合并为一份
    // 生成数据给 Sku 组件渲染出结构
    this.setState({
      propVoList: product.spec.specs.map(spec => ({
          id: spec.id,
          propertyName: spec.name,
          tagList: spec.values.map(value => ({
            id: value.id,
            tagName: value.name,
          })),
        }
      )),
      skuData: product.skus.map(sku => ({
        skuId: sku.id,
        tags: sku.specValueIds,
        price: sku.price,
        origPrice: sku.marketPrice,
        stockNum: sku.stockNum,
        // NOTE: 此处不经过 inputConverter 需要自行转换
        image: sku.image ? {fileList: [{url: sku.image}]} : '',
      })),
    });

    // 更改为匹配输入框的数据结构，以便正常加载数据
    const newSkus = {};
    product.skus.forEach(sku => {
      newSkus[sku.specValueIds.join('-')] = sku;
    });
    product.skus = newSkus;
  }

  getData = (values) => {
    values.spec = {};
    values.spec.specs = this.state.tmpSkuProps.map(skuProp => ({
      name: skuProp.propertyName,
      values: skuProp.tags.map(tag => ({
        name: tag.tagName,
      })),
    }));

    values.skus = Object.values(values.skus);

    // FIXME 增加/删除规格之后，原来的图片值还存在表单中
    values.skus = values.skus.filter(sku => {
      return typeof sku.price !== 'undefined';
    });

    this.state.skuTableDataSource.forEach((data, index) => {
      values.skus[index].id = data.skuId;
      values.skus[index].specValues = data.tagList.map(tag => {
        return {
          specName: tag.propertyName,
          name: tag.tagName,
        };
      });
    });

    // 删除组件自带的数据
    delete values._skuConfigs;

    return values;
  }

  render() {
    const {
      skuPropsList,
      propVoList,
      skuData,
    } = this.state;

    return (
      <SkuBase
        ref={this.skuRef}
        skuPropsList={skuPropsList}
        skuPropVoList={propVoList}
        addPropApi={this.addPropApi}
        addTagApi={this.addTagApi}
        filterSku={skuTags => {
          let k = skuTags
            .map(tag => tag.id)
            .sort()
            .join(':');
          let sku = skuData.filter(sku => {
            let k1 = sku.tags.sort().join(':');
            return k === k1;
          });
          return sku[0];
        }}
        extras={[
          {
            title: this.renderRequired('价格'),
            dataIndex: 'price',
            align: 'center',
            width: 100,
            render: (v, record) => {
              return this.renderBaseFormItem({
                name: ['skus', record.rowKey, 'price'],
                initialValue: v,
                rules: [{required: true, message: '必填'}],
              });
            },
          },
          {
            title: '划线价',
            dataIndex: 'marketPrice',
            align: 'center',
            width: 100,
            render: (v, record) => {
              return this.renderBaseFormItem({
                name: ['skus', record.rowKey, 'marketPrice'],
                initialValue: v,
              });
            },
          },
          {
            title: '积分',
            dataIndex: 'score',
            align: 'center',
            width: 100,
            render: (v, record) => {
              return this.renderBaseFormItem({
                name: ['skus', record.rowKey, 'score'],
                initialValue: v,
              });
            },
          },
          {
            title: this.renderRequired('库存'),
            dataIndex: 'stockNum',
            align: 'center',
            width: 100,
            render: (v, record) => {
              return this.renderBaseFormItem({
                name: ['skus', record.rowKey, 'stockNum'],
                initialValue: v,
                rules: [{required: true, message: '必填'}],
              });
            },
          },
          {
            title: '货号',
            dataIndex: 'outerId',
            align: 'center',
            width: 100,
            render: (v, record) => {
              return this.renderBaseFormItem({
                name: ['skus', record.rowKey, 'outerId'],
                initialValue: v,
              });
            },
          },
          {
            title: '重量（千克）',
            dataIndex: 'weight',
            align: 'center',
            width: 110,
            render: (v, record) => {
              return this.renderBaseFormItem({
                name: ['skus', record.rowKey, 'weight'],
                initialValue: v,
              });
            },
          },
          {
            title: '图片',
            dataIndex: 'image',
            align: 'center',
            width: 60,
            render: (v, record) => {
              return this.renderBaseFormItem({
                name: ['skus', record.rowKey, 'image'],
                initialValue: v,
              }, {
                el: <Upload max={1} size={60}/>,
              });
            },
          },
          {
            title: '销量',
            dataIndex: 'soldNum',
            align: 'center',
            render: value => value || 0,
          },
        ]}
        onChange={v => {
          this.setState({
            tmpSkuProps: v.tmpSkuProps,
            skuTableColumns: v.skuTableColumns,
            skuTableDataSource: v.skuTableDataSource,
          });
        }}
      />
    );
  }
}
