import {Avatar} from 'antd';
import {PictureOutlined} from '@ant-design/icons';
import propTypes from 'prop-types';
import Media from '@mxjs/a-media';

/**
 * 商品媒体对象
 *
 * 包含了商品小图和名称
 */
const ProductMedia = ({product}) => {
  return (
    <Media>
      <Avatar src={product.image} icon={<PictureOutlined/>} shape="square" size={48}/>
      <Media.Body>
        {product.name}
      </Media.Body>
    </Media>
  );
};

ProductMedia.propTypes = {
  product: propTypes.shape({
    name: propTypes.string.isRequired,
    image: propTypes.string,
  }),
};

export default ProductMedia;
