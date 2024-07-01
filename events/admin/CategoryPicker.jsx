import { useEffect, useState } from 'react';
import {Button, Modal} from 'antd';
import {Table, TableProvider, useTable} from '@mxjs/a-table';
import {SearchForm, SearchItem} from '@mxjs/a-form';
import {PageActions} from '@mxjs/a-page';
import Icon from '@mxjs/icons';
import $ from 'miaoxing';
import PropTypes from 'prop-types';
import {NewBtn} from '@mxjs/a-button';

const CategoryPicker = ({pickerRef, linkPicker, value}) => {
  const [table] = useTable();
  const [id, setId] = useState(value.id);
  const [name, setName] = useState();
  const [open, setOpen] = useState(true);

  const [expandedRowKeys, setExpandedRowKeys] = useState([]);

  // 每次都更新
  pickerRef && (pickerRef.current = {
    show: () => {
      setOpen(true);
    },
  });

  return <Modal
    title="选择商品分类"
    open={open}
    width={800}
    bodyStyle={{
      padding: '1rem',
    }}
    onOk={() => {
      if (id) {
        linkPicker.addValue({id}, {name});
      }
      setOpen(false);
    }}
    onCancel={() => {
      setOpen(false);
    }}
  >
    <TableProvider>
      <PageActions>
        <NewBtn href={$.url('admin/categories/new')} target="_blank">
          添 加{' '}<Icon type="mi-external-link"/>
        </NewBtn>
        <Button onClick={() => {
          table.reload();
        }}>刷新</Button>
      </PageActions>
      <SearchForm>
        <SearchItem label="名称" name={['search', 'name:ct']}/>
      </SearchForm>
      <Table
        tableApi={table}
        url="categories"
        expandedRowKeys={expandedRowKeys}
        expandIcon={({record}) => (record.children ? '' : '└ ')}
        postData={(data) => {
          setExpandedRowKeys(data.map(record => record.id));
          return data;
        }}
        rowSelection={{
          type: 'radio',
          onChange: (selectedRowKeys, selectedRows) => {
            setId(selectedRowKeys[0]);
            setName(selectedRows[0]?.name);
          },
        }}
        columns={[
          {
            title: '名称',
            dataIndex: 'name',
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
  </Modal>;
};

CategoryPicker.propTypes = {
  pickerRef: PropTypes.object,
  linkPicker: PropTypes.object,
  value: PropTypes.object,
};

const CategoryPickerLabel = ({value, extra}) => {
  const [name, setName] = useState('');

  useEffect(() => {
    if (!extra.name) {
      (async () => {
        const {ret} = await $.get('categories/' + value.id);
        setName(ret.data.name);
      })();
    }
  }, [value.id, extra]);

  return extra.name || name;
};

CategoryPicker.Label = CategoryPickerLabel;

export default CategoryPicker;
