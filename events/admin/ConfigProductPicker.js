import { useEffect, useState } from 'react';
import {Table, TableProvider, useTable} from '@mxjs/a-table';
import Media from '@mxjs/a-media';
import {CloseCircleFilled, DownCircleFilled, UpCircleFilled} from '@ant-design/icons';
import $ from 'miaoxing';
import {Avatar, Button, Modal} from 'antd';
import Icon from '@mxjs/icons';
import {PageActions} from '@mxjs/a-page';
import {SearchForm, SearchItem} from '@mxjs/a-form';
import api from '@mxjs/api';
import appendUrl from 'append-url';
import PropTypes from 'prop-types';
import {NewBtn} from '@mxjs/a-button';
import {ProductMedia} from '@miaoxing/product/admin';
import {css, spacing} from '@mxjs/css';

const defaultImage = window.location.origin + $.url('plugins/page/images/default-swiper.svg');

const cardClass = css({
  position: 'relative',
  mb4: true,
  px6: true,
  pt6: true,
  shadowTiny: true,
  border: 1,
  borderColor: 'gray100',
  ':hover': {
    '> .toolbar': {
      display: 'block',
    },
  },
});

const toolbarClass = css({
  display: 'none',
  position: 'absolute',
  top: -spacing(4),
  right: -spacing(2),
  textXL: true,
  '> a': {
    ml1: true,
    gray400: true,
  },
});

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
  const [visible, setVisible] = useState(false);
  const [selectedRowKeys, setSelectedRowKeys] = useState(value);

  useEffect(() => {
    if (!value.length) {
      setProducts([]);
      return;
    }

    api.get(appendUrl('products', {sortField: 'id', search: {id: value}}))
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
          return <Media key={product.id} className={cardClass}>
            <div className={'toolbar ' + toolbarClass}>
              {index !== 0 && <a href="#" onClick={(e) => {
                e.preventDefault();
                move(index, index - 1);
              }}>
                <UpCircleFilled/>
              </a>}
              {index !== products.length - 1 && <a href="#" onClick={(e) => {
                e.preventDefault();
                move(index, index + 1);
              }}>
                <DownCircleFilled/>
              </a>}
              <a href="#" onClick={(e) => {
                e.preventDefault();
                $.confirm('删除后不能还原，确认删除？', result => {
                  if (result) {
                    remove(index);
                  }
                });
              }}>
                <CloseCircleFilled/>
              </a>
            </div>
            <Avatar src={product.image || defaultImage} shape="square" size={48}/>
            <Media.Body>
              {product.name}
            </Media.Body>
          </Media>;
        })}
        <Button block type="dashed" onClick={() => {
          setVisible(true);
        }}>
          <Icon type="mi-popup"/>
          选 择
        </Button>
      </div>
      <Modal
        title="选择商品"
        visible={visible}
        width={800}
        bodyStyle={{
          padding: '1rem',
        }}
        onOk={() => {
          onChange(selectedRowKeys);
          setVisible(false);
        }}
        onCancel={() => {
          setSelectedRowKeys(value);
          setVisible(false);
        }}
      >
        <TableProvider>
          <PageActions>
            <NewBtn href={$.url('admin/products/new')} target="_blank">
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
            url={$.apiUrl('products')}
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
