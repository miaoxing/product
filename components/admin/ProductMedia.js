import { Component } from 'react';
import {Avatar} from 'antd';
import {PictureOutlined} from '@ant-design/icons';
import propTypes from 'prop-types';
import {Box} from '@fower/react';

/**
 * 商品媒体对象
 *
 * 包含了商品小图和名称
 */
export default class extends Component {
  static propTypes = {
    product: propTypes.shape({
      name: propTypes.string.isRequired,
      image: propTypes.string,
    }),
  }

  render() {
    const product = this.props.product;
    return (
      <Box toTop>
        <Avatar src={product.image} icon={<PictureOutlined/>} shape="square" size={48}/>
        <Box flex={1} ml3>
          {product.name}
        </Box>
      </Box>
    );
  }
}
