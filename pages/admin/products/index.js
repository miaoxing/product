import React from 'react';
import {CTableDeleteLink, Table, TableProvider, TableStatusCheckbox, useTable} from '@mxjs/a-table';
import {CEditLink, CNewBtn} from '@mxjs/a-clink';
import {Page, PageActions} from '@mxjs/a-page';
import {LinkActions} from '@mxjs/actions';
import ProductMedia from '@miaoxing/product/components/ProductMedia';

function getPriceText(price, score) {
  let text = '';
  if (price || !score) {
    text += '￥' + price;
  }

  if (price && score) {
    text += ' + ';
  }

  if (score) {
    text += score + ' 积分';
  }

  return text;
}

export default () => {
  const [table] = useTable();

  return (
    <Page>
      <PageActions>
        <CNewBtn/>
      </PageActions>

      <TableProvider>
        <Table
          tableApi={table}
          columns={[
            {
              title: '商品',
              dataIndex: 'id',
              render: (id, row) => <ProductMedia product={row}/>,
            },
            {
              title: '价格',
              dataIndex: 'minPrice',
              render: (cell, row) => getPriceText(cell, row.minScore),
            },
            {
              title: '库存',
              dataIndex: 'stockNum',
            },
            {
              title: '销量',
              dataIndex: 'soldNum',
            },
            {
              title: '上架',
              dataIndex: 'isListing',
              render: (cell, row) => <TableStatusCheckbox row={row} name="isListing"/>,
            },
            {
              title: '创建时间',
              dataIndex: 'createdAt',
              width: 180,
            },
            {
              title: '操作',
              dataIndex: 'id',
              render: (id) => (
                <LinkActions>
                  <CEditLink id={id}/>
                  <CTableDeleteLink id={id}/>
                </LinkActions>
              ),
            },
          ]}
        />
      </TableProvider>
    </Page>
  );
};
