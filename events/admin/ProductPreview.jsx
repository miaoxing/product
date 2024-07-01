import { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import $ from 'miaoxing';
import { Empty } from 'antd';
import { Box } from '@mxjs/a-box';
import defaultImage from '../../images/default-image.svg';

const ProductLink = (props) => {
  return (
    <Box as="a" display="block" bg="white" color="inherit" {...props}/>
  );
};

const ProductDetail = (props) => {
  return (
    <Box p={2} {...props}/>
  );
};

const ProductName = (props) => {
  return (
    <Box textAlign="left" h="40px" overflow="hidden" fontSize="16px" lineHeight="20px" {...props}/>
  );
};

const ProductImg = (props) => {
  return (
    <Box as="img" w="full" h="full" position="absolute" top={0} left={0} objectFit="cover" {...props}/>
  );
};

const listPropTypes = {
  products: PropTypes.array,
  showMarketPrice: PropTypes.bool,
};

const Image = ({product}) => (
  <Box position="relative" pt="100%">
    <ProductImg src={product.image || defaultImage}/>
  </Box>
);

Image.propTypes = {
  product: PropTypes.object,
};

const Price = ({product, showMarketPrice}) => (
  <Box color="#f28c48">
    ￥{product.minPrice}
    {!!(showMarketPrice && product.minMarketPrice) &&
      <Box as="del" color="#757575" fontWeight="400" fontSize="sm" ml={1}>
        ￥{product.minMarketPrice}
      </Box>}
  </Box>
);

Price.propTypes = {
  product: PropTypes.object,
  showMarketPrice: PropTypes.bool,
};

const List1 = ({products, showMarketPrice}) => {
  return (
    <Box w="100%">
      {products.map((product) => (
        <Box key={product.id} m={2}>
          <ProductLink href="#">
            <Image product={product}/>
            <ProductDetail>
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </ProductDetail>
          </ProductLink>
        </Box>
      ))}
    </Box>
  );
};

List1.propTypes = listPropTypes;

const List2 = ({products, showMarketPrice}) => {
  return (
    <Box display="flex" flexWrap="wrap" p={1}>
      {products.map((product) => (
        <Box key={product.id} w="50%" p={1}>
          <ProductLink href="#">
            <Image product={product}/>
            <ProductDetail>
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </ProductDetail>
          </ProductLink>
        </Box>
      ))}
    </Box>
  );
};

List2.propTypes = listPropTypes;

const List3 = ({products, showMarketPrice}) => {
  return (
    <Box display="flex" flexWrap="wrap" p={1}>
      {products.map((product) => (
        <Box key={product.id} w={1 / 3} p={1}>
          <ProductLink href="#">
            <Image product={product}/>
            <ProductDetail>
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </ProductDetail>
          </ProductLink>
        </Box>
      ))}
    </Box>
  );
};

List3.propTypes = listPropTypes;

const List4 = ({products, showMarketPrice}) => {
  return (
    <Box w="full">
      {products.map((product) => (
        <Box key={product.id} display="flex" m={2}>
          <ProductLink display="flex" w="full" href="#">
            <Box as="img" w="120px" h="120px" objectFit="cover" src={product.image || defaultImage}/>
            <Box display="flex" flexDirection="column" justifyContent="space-between" mx={4} my={2}>
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </Box>
          </ProductLink>
        </Box>
      ))}
    </Box>
  );
};

List4.propTypes = listPropTypes;

const tpls = {
  1: List1,
  2: List2,
  3: List3,
  4: List4,
};

const ProductPreview = (
  {
    source = 'all',
    categoryIds = [],
    productIds = [],
    num = 3,
    tpl = 1,
    orderBy = '',
    style = {},
    showMarketPrice = true,
  },
) => {
  const [products, setProducts] = useState([]);

  useEffect(() => {
    let search = {};
    switch (source) {
      case 'category':
        if (categoryIds.length === 0) {
          setProducts([]);
          return;
        }

        search = {
          categoryId: categoryIds,
        };
        break;

      case 'product':
        if (productIds.length === 0) {
          setProducts([]);
          return;
        }

        num = undefined;
        search = {
          id: productIds,
        };
        break;

      case 'all':
        break;
    }

    const [sort, order] = orderBy.split(' ');

    $.get({
      url: 'products',
      params: {
        sortField: 'id',
        limit: num,
        search,
        sort,
        order,
      },
    }).then(({ret}) => {
      if (ret.isErr()) {
        $.ret(ret);
        return;
      }
      setProducts(ret.data);
    });
  }, [source, num, orderBy, categoryIds.join(), productIds.join()]);

  const Tpl = tpls[tpl || 1];

  return (
    <>
      {
        products.length ?
          <Box display="flex" style={style}>
            <Tpl products={products} showMarketPrice={showMarketPrice}/>
          </Box>
          :
          <Box overflow="hidden">
            <Empty image={Empty.PRESENTED_IMAGE_SIMPLE}/>
          </Box>
      }
    </>
  );
};

ProductPreview.propTypes = {
  source: PropTypes.string,
  categoryIds: PropTypes.array,
  productIds: PropTypes.array,
  num: PropTypes.number,
  tpl: PropTypes.number,
  orderBy: PropTypes.string,
  style: PropTypes.object,
  showMarketPrice: PropTypes.bool,
};

export default ProductPreview;

export { defaultImage };
