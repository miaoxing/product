import PropTypes from 'prop-types';
import {createFromIconfontCN} from '@ant-design/icons';

const Icon = createFromIconfontCN({
  scriptUrl: '//at.alicdn.com/t/font_16508_zhquepi1h9.js',
});

const formStyle = {
  height: '48px',
  padding: '6px 10px',
  backgroundColor: '#f8f8f8',
};

const formInputStyle = {
  backgroundColor: '#fffff',
  textAlign: 'center',
  borderRadius: '4px',

  fontSize: '14px',
  height: '36px',
  lineHeight: '36px',
  paddingLeft: '16px',
  paddingRight: '16px',
};

const iconStyle = {
  fontSize: '16px',
  verticalAlign: 'middle',
};

const borderRadii = {
  rect: 0,
  round: 4,
  circle: 20,
};

const ProductSearchPreview = ({placeholder, inputShape, style = {}, inputStyle = {}}) => {
  const formCss = {...formStyle, ...style};
  const inputCss = {...formInputStyle, ...inputStyle};

  inputCss.borderRadius = borderRadii[inputShape];

  return (
    <div css={formCss}>
      <div css={inputCss}>
        <Icon type="icon-search" style={iconStyle}/>
        {' '}
        {placeholder || '搜索'}
      </div>
    </div>
  );
};

ProductSearchPreview.propTypes = {
  placeholder: PropTypes.string,
  inputShape: PropTypes.string,
  style: PropTypes.object,
  inputStyle: PropTypes.object,
};

export default ProductSearchPreview;
