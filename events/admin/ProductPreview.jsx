import { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import $ from 'miaoxing';
import { Empty } from 'antd';
import defaultImage from '../../images/default-image.svg';
import classNames from 'classnames';

const ProductLink = ({className, ...props}) => {
  return (
    <a className={classNames('block bg-white text-inherit', className)} {...props}/>
  );
};

const ProductDetail = (props) => {
  return (
    <div className="p-2" {...props}/>
  );
};

const ProductName = (props) => {
  return (
    <div className="text-left h-10 overflow-hidden text-base" {...props}/>
  );
};

const ProductImg = (props) => {
  return (
    <img className="w-full h-full absolute top-0 left-0 object-cover" {...props}/>
  );
};

const listPropTypes = {
  products: PropTypes.array,
  showMarketPrice: PropTypes.bool,
};

const Image = ({ product }) => (
  <div className="relative pt-[100%]">
    <ProductImg src={product.image || defaultImage}/>
  </div>
);

Image.propTypes = {
  product: PropTypes.object,
};

const Price = ({ product, showMarketPrice }) => (
  <div className="text-[#f28c48]">
    ￥{product.minPrice}
    {!!(showMarketPrice && product.minMarketPrice) &&
      <del className="text-gray-600 text-sm ml-1">
        ￥{product.minMarketPrice}
      </del>}
  </div>
);

Price.propTypes = {
  product: PropTypes.object,
  showMarketPrice: PropTypes.bool,
};

const List1 = ({ products, showMarketPrice }) => {
  return (
    <div className="w-full">
      {products.map((product) => (
        <div key={product.id} className="m-2">
          <ProductLink href="#">
            <Image product={product}/>
            <ProductDetail>
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </ProductDetail>
          </ProductLink>
        </div>
      ))}
    </div>
  );
};

List1.propTypes = listPropTypes;

const List2 = ({ products, showMarketPrice }) => {
  return (
    <div className="flex flex-wrap p-1">
      {products.map((product) => (
        <div key={product.id} className="w-1/2 p-1">
          <ProductLink href="#">
            <Image product={product}/>
            <ProductDetail>
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </ProductDetail>
          </ProductLink>
        </div>
      ))}
    </div>
  );
};

List2.propTypes = listPropTypes;

const List3 = ({ products, showMarketPrice }) => {
  return (
    <div className="flex flex-wrap p-1">
      {products.map((product) => (
        <div key={product.id} className="w-1/3 p-1">
          <ProductLink href="#">
            <Image product={product}/>
            <ProductDetail>
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </ProductDetail>
          </ProductLink>
        </div>
      ))}
    </div>
  );
};

List3.propTypes = listPropTypes;

const List4 = ({ products, showMarketPrice }) => {
  return (
    <div className="w-full">
      {products.map((product) => (
        <div key={product.id} className="flex m-2">
          <ProductLink className="flex w-full" href="#">
            <img className="w-[120px] h-[120px] object-cover" src={product.image || defaultImage}/>
            <div className="flex flex-col justify-between mx-4 my-2">
              <ProductName>
                {product.name}
              </ProductName>
              <Price product={product} showMarketPrice={showMarketPrice}/>
            </div>
          </ProductLink>
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
      url: 'products',
      params: {
        sortField: 'id',
        limit: num,
        search,
        sort,
        order,
      },
    }).then(({ ret }) => {
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
          <div className="flex" style={style}>
            <Tpl products={products} showMarketPrice={showMarketPrice}/>
          </div>
          :
          <div className="overflow-hidden">
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

export { defaultImage };
