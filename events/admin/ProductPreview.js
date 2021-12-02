import { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import $ from 'miaoxing';
import {Empty} from 'antd';
import {css} from '@fower/core';
import {Box} from '@mxjs/box';

const defaultImage = window.location.origin + $.url('plugins/page/images/default-swiper.svg');

const space = 4;

const productLinkCss = css({
  display: 'block',
  background: 'white',
  color: 'inherit',
});

const productDetailCss = css({
  p: '8px',
});

const productNameCss = css({
  textAlign: 'left',
  h: '40px',
  overflow: 'hidden',
  fontSize: '16px',
  lineHeight: '20px',
});

const productImgCss = css({
  w: '100%',
  h: '100%',
  position: 'absolute',
  top: 0,
  left: 0,
  objectFit: 'cover',
});

const productMarketPriceCss = css({
  color: '#757575',
  fontWeight: 400,
  fontSize: '80%',
  ml: '4px',
});

const productItem1Css = css({
  m: space * 2 + 'px',
});

const listPropTypes = {
  products: PropTypes.array,
  showMarketPrice: PropTypes.bool,
};

const Image = ({product}) => (
  <Box relative pt="100%">
    <img className={productImgCss} src={product.image || defaultImage}/>
  </Box>
);

Image.propTypes = {
  product: PropTypes.object,
};

const Price = ({product, showMarketPrice}) => (
  <Box color="#f28c48">
    ￥{product.minPrice}
    {!!(showMarketPrice && product.minMarketPrice) && <del className={productMarketPriceCss}>
      ￥{product.minMarketPrice}
    </del>}
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
        <div key={product.id} className={productItem1Css}>
          <a className={productLinkCss} href="#">
            <Image product={product}/>
            <div className={productDetailCss}>
              <div className={productNameCss}>
                {product.name}
              </div>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </div>
          </a>
        </div>
      ))}
    </Box>
  );
};

List1.propTypes = listPropTypes;

const productList2Css = css({
  display: 'flex',
  flexWrap: 'wrap',
  p: space + 'px',
});

const productItem2Css = css({
  w: '50%',
  p: space + 'px',
});

const List2 = ({products, showMarketPrice}) => {
  return (
    <div className={productList2Css}>
      {products.map((product) => (
        <div key={product.id} className={productItem2Css}>
          <a className={productLinkCss} href="#">
            <Image product={product}/>
            <div className={productDetailCss}>
              <div className={productNameCss}>
                {product.name}
              </div>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </div>
          </a>
        </div>
      ))}
    </div>
  );
};

List2.propTypes = listPropTypes;

const productList3Css = css({
  display: 'flex',
  flexWrap: 'wrap',
  p: space + 'px',
});

const productItem3Css = css({
  w: '33.333333%',
  p: space + 'px',
});

const List3 = ({products, showMarketPrice}) => {
  return (
    <div className={productList3Css}>
      {products.map((product) => (
        <div key={product.id} className={productItem3Css}>
          <a className={productLinkCss} href="#">
            <Image product={product}/>
            <div className={productDetailCss}>
              <div className={productNameCss}>
                {product.name}
              </div>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </div>
          </a>
        </div>
      ))}
    </div>
  );
};

List3.propTypes = listPropTypes;

const productList4Css = css({
  w: '100%',
});

const productItem4Css = css({
  display: 'flex',
  m: space * 2 + 'px',
});

const productImg4Css = css({
  w: '120px',
  h: '120px',
  objectFit: 'cover',
});

const productDetail4Css = css({
  display: 'flex',
  flexDirection: 'column',
  justifyContent: 'space-between',
  mx: '16px',
  my: '8px',
});

const List4 = ({products, showMarketPrice}) => {
  return (
    <div className={productList4Css}>
      {products.map((product) => (
        <div key={product.id} className={productItem4Css}>
          <Box as="a" className={productLinkCss} style={{display: 'flex'}} w="100%" href="#">
            <img className={productImg4Css} src={product.image || defaultImage}/>
            <div className={productDetail4Css}>
              <div className={productNameCss}>
                {product.name}
              </div>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </div>
          </Box>
        </div>
      ))}
    </div>
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
      url: $.apiUrl('products', {
        sortField: 'id',
        limit: num,
        search,
        sort,
        order,
      }),
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
          <Box flex style={style}>
            <Tpl products={products} showMarketPrice={showMarketPrice}/>
          </Box>
          :
          <Box overflowHidden>
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

export {defaultImage};
