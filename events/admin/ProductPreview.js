import { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import {css} from '@mxjs/css';
import $ from 'miaoxing';
import {Empty} from 'antd';

const defaultImage = window.location.origin + $.url('plugins/page/images/default-swiper.svg');

const space = 4;

const productContainerCss = css({
  display: 'flex',
});

const productItemCss = css({});

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
  height: '40px',
  overflow: 'hidden',
  fontSize: '16px',
  lineHeight: '20px',
});

const productImgContainerCss = css({
  position: 'relative',
  pt: '100%',
});

const productImgCss = css({
  width: '100%',
  height: '100%',
  position: 'absolute',
  top: 0,
  left: 0,
  objectFit: 'cover',
});

const productPriceCss = css({
  color: '#f28c48',
});

const productMarketPriceCss = css({
  color: '#757575',
  fontWeight: 400,
  fontSize: '80%',
  ml: '4px',
});

const productList1Css = css({
  width: '100%',
});

const productItem1Css = css({
  m: space * 2 + 'px',
});

const listPropTypes = {
  products: PropTypes.array,
  showMarketPrice: PropTypes.bool,
};

const Image = ({product}) => (
  <div css={productImgContainerCss}>
    <img css={productImgCss} src={product.image || defaultImage}/>
  </div>
);

Image.propTypes = {
  product: PropTypes.object,
};

const Price = ({product, showMarketPrice}) => (
  <div css={productPriceCss}>
    ￥{product.minPrice}
    {!!(showMarketPrice && product.minMarketPrice) && <del css={productMarketPriceCss}>
      ￥{product.minMarketPrice}
    </del>}
  </div>
);

Price.propTypes = {
  product: PropTypes.object,
  showMarketPrice: PropTypes.bool,
};

const List1 = ({products, showMarketPrice}) => {
  return (
    <div css={productList1Css}>
      {products.map((product) => (
        <div key={product.id} css={[productItemCss, productItem1Css]}>
          <a css={productLinkCss} href="#">
            <Image product={product}/>
            <div css={productDetailCss}>
              <div css={productNameCss}>
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

List1.propTypes = listPropTypes;

const productList2Css = {
  display: 'flex',
  flexWrap: 'wrap',
  padding: space + 'px',
};

const productItem2Css = {
  width: '50%',
  padding: space + 'px',
};

const List2 = ({products, showMarketPrice}) => {
  return (
    <div css={productList2Css}>
      {products.map((product) => (
        <div key={product.id} css={[productItemCss, productItem2Css]}>
          <a css={productLinkCss} href="#">
            <Image product={product}/>
            <div css={productDetailCss}>
              <div css={productNameCss}>
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

const productList3Css = {
  display: 'flex',
  flexWrap: 'wrap',
  padding: space + 'px',
};

const productItem3Css = {
  width: '33.333333%',
  padding: space + 'px',
};

const List3 = ({products, showMarketPrice}) => {
  return (
    <div css={productList3Css}>
      {products.map((product) => (
        <div key={product.id} css={[productItemCss, productItem3Css]}>
          <a css={productLinkCss} href="#">
            <Image product={product}/>
            <div css={productDetailCss}>
              <div css={productNameCss}>
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

const productList4Css = {
  width: '100%',
};

const productItem4Css = {
  display: 'flex',
  margin: space * 2 + 'px',
};

const productLink4Css = {
  display: 'flex',
  width: '100%',
};

const productImg4Css = {
  width: '120px',
  height: '120px',
  objectFit: 'cover',
};

const productDetail4Css = css({
  display: 'flex',
  flexDirection: 'column',
  justifyContent: 'space-between',
  mx: '16px',
  my: '8px',
});

const List4 = ({products, showMarketPrice}) => {
  return (
    <div css={[productList4Css]}>
      {products.map((product) => (
        <div key={product.id} css={[productItemCss, productItem4Css]}>
          <a css={[productLinkCss, productLink4Css]} href="#">
            <img css={[productImg4Css]} src={product.image || defaultImage}/>
            <div css={productDetail4Css}>
              <div css={productNameCss}>
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

List4.propTypes = listPropTypes;

const tpls = {
  1: List1,
  2: List2,
  3: List3,
  4: List4,
};

const emptyContainerCss = css({
  overflow: 'hidden',
});

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
          <div css={[productContainerCss, style]}>
            <Tpl products={products} showMarketPrice={showMarketPrice}/>
          </div>
          :
          <div css={emptyContainerCss}>
            <Empty image={Empty.PRESENTED_IMAGE_SIMPLE}/>
          </div>
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
