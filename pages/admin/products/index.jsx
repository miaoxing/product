import {CTableDeleteLink, Table, TableActions, TableProvider, TableSwitch, useTable} from '@mxjs/a-table';
import {CEditLink, CNewBtn} from '@mxjs/a-clink';
import {Page, PageActions} from '@mxjs/a-page';
import {ProductMedia} from '@miaoxing/product/admin';

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

const Index = () => {
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
              render: (cell, row) => <TableSwitch row={row} name="isListing"/>,
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
                <TableActions>
                  <CEditLink id={id}/>
                  <CTableDeleteLink id={id}/>
                </TableActions>
              ),
            },
          ]}
        />
      </TableProvider>
    </Page>
  );
};

export default Index;
