import { useEffect, useState } from 'react';
import { Table, TableProvider, useTable } from '@mxjs/a-table';
import Media from '@mxjs/a-media';
import $ from 'miaoxing';
import { Avatar, Button, Modal } from 'antd';
import Icon from '@mxjs/icons';
import { PageActions } from '@mxjs/a-page';
import { SearchForm, SearchItem } from '@mxjs/a-form';
import appendUrl from 'append-url';
import PropTypes from 'prop-types';
import { NewBtn } from '@mxjs/a-button';
import { ProductMedia } from '@miaoxing/product/admin';
import { ConfigItem } from '@miaoxing/page/admin';
import defaultImage from '../../images/default-image.svg';

const arrayMove = (array, from, to) => {
  array.splice(to, 0, array.splice(from, 1)[0]);
  return [...array];
};

const arrayRemove = (array, index) => {
  array.splice(index, 1);
  return [...array];
};

const ProductPicker = ({value = [], onChange}) => {
  const [table] = useTable();

  // 确定选中的数据
  const [products, setProducts] = useState([]);

  // Modal 中的数据
  // 需要受控，以便打开 modal 选中已选的商品
  const [open, setOpen] = useState(false);
  const [selectedRowKeys, setSelectedRowKeys] = useState(value);

  useEffect(() => {
    if (!value.length) {
      setProducts([]);
      return;
    }

    $.get(appendUrl('products', {sortField: 'id', search: {id: value}}))
      .then(({ret}) => {
        if (ret.isErr()) {
          $.ret(ret);
          return;
        }
        setProducts(ret.data);
      });
  }, [value.join(',')]);

  const move = (from, to) => {
    onChange(arrayMove(value, from, to));
    setProducts(arrayMove(products, from, to));
  };

  const remove = (index) => {
    onChange(arrayRemove(value, index));
    setProducts(arrayRemove(products, index));
  };

  return (
    <>
      <div>
        {products.map((product, index) => {
          return (
            <ConfigItem key={product.id} index={index} length={products.length} operation={{move, remove}}>
              <Media>
                <Avatar src={product.image || defaultImage} shape="square" size={48}/>
                <Media.Body>
                  {product.name}
                </Media.Body>
              </Media>
            </ConfigItem>
          );
        })}
        <Button block type="dashed" onClick={() => {
          setOpen(true);
        }}>
          <Icon type="mi-popup"/>
          选 择
        </Button>
      </div>
      <Modal
        title="选择商品"
        open={open}
        width={800}
        styles={{
          body: {
            paddingBlock: '.5rem',
          }
        }}
        onOk={() => {
          onChange(selectedRowKeys);
          setOpen(false);
        }}
        onCancel={() => {
          setSelectedRowKeys(value);
          setOpen(false);
        }}
      >
        <TableProvider>
          <PageActions>
            <NewBtn to={$.url('admin/products/new')} target="_blank">
              添 加{' '}<Icon type="mi-external-link"/>
            </NewBtn>
            <Button onClick={() => {
              table.reload();
            }}>刷新</Button>
          </PageActions>
          <SearchForm>
            <SearchItem label="标题" name={['search', 'title:ct']}/>
          </SearchForm>
          <Table
            tableApi={table}
            url="products"
            rowSelection={{
              selectedRowKeys,
              onChange: (selectedRowKeys) => {
                setSelectedRowKeys(selectedRowKeys);
              },
            }}
            columns={[
              {
                title: '商品',
                dataIndex: 'id',
                render: (id, row) => <ProductMedia product={row}/>,
              },
              {
                title: '创建时间',
                dataIndex: 'createdAt',
                width: 180,
              },
              {
                title: '最后更改时间',
                dataIndex: 'updatedAt',
                width: 180,
              },
            ]}
          />
        </TableProvider>
      </Modal>
    </>
  );
};

ProductPicker.propTypes = {
  value: PropTypes.array,
  onChange: PropTypes.func,
};

export default ProductPicker;
