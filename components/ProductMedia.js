import React from 'react';
import {Media} from '@mxjs/bootstrap';
import {Avatar} from 'antd';
import {PictureOutlined} from '@ant-design/icons';
import {sx} from '@mxjs/css';
import propTypes from 'prop-types';

/**
 * 商品媒体对象
 *
 * 包含了商品小图和名称
 */
export default class extends React.Component {
  static propTypes = {
    product: propTypes.shape({
      name: propTypes.string.isRequired,
      image: propTypes.string,
    }),
  }

  render() {
    const product = this.props.product;
    return (
      <Media>
        <Avatar src={product.image} icon={<PictureOutlined/>} shape="square" size={48} css={sx({mr: 3})}/>
        <Media.Body>
          {product.name}
        </Media.Body>
      </Media>
    );
  }
}
