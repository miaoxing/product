import {useEffect, useState} from 'react';
import {Image, Text, View} from '@fower/taro';
import {AtFloatLayout, AtInputNumber} from 'taro-ui';
import './index.scss';
import clsx from 'clsx';
import $ from 'miaoxing';
import Taro from '@tarojs/taro';
import FooterBar from '@mxjs/m-footer-bar';
import ActionButtonGroup from '../ActionButtonGroup';

const contains = function (container, array) {
  for (const el of array) {
    if (!container.includes(el)) {
      return false;
    }
  }
  return true;
};

const getPriceRange = function (validSkus) {
  if (validSkus.length === 1) {
    return validSkus[0].price;
  }

  let min = parseFloat(validSkus[0].price);
  let max = min;
  for (let i in validSkus) {
    if (Object.prototype.hasOwnProperty.call(validSkus, i)) {
      let price = parseFloat(validSkus[i].price);
      if (price < min) {
        min = price;
      }
      if (price > max) {
        max = price;
      }
    }
  }

  if (min === max) {
    return min.toFixed(2);
  } else {
    return min.toFixed(2) + ' ~ ' + max.toFixed(2);
  }
};

const getScoreRange = function (validSkus) {
  if (validSkus.length === 1) {
    return parseInt(validSkus[0].score, 10);
  }

  let min = parseInt(validSkus[0].score, 10);
  let max = min;
  for (let i in validSkus) {
    if (Object.prototype.hasOwnProperty.call(validSkus, i)) {
      let score = parseInt(validSkus[i].score, 10);
      if (score < min) {
        min = score;
      }
      if (score > max) {
        max = score;
      }
    }
  }

  if (min === max) {
    return min;
  } else {
    return min + ' ~ ' + max;
  }
};

const getPriceText = function (validSkus) {
  let text = '';
  let price = getPriceRange(validSkus);
  let score = getScoreRange(validSkus);

  if (price !== '0.00') {
    text += '￥' + price;
  }

  if (price !== '0.00' && score !== 0) {
    text += ' + ';
  }

  if (score !== 0) {
    text += score + '积分';
  }

  return text;
};

// TODO 简化
const calSoldOutValueIds = function (skus, selectedValueIds = []) {
  // 1. 找出所有售罄的规格值编号
  const valueStockNums = {};
  skus.forEach(sku => {
    sku.specValueIds.forEach(valueId => {
      if (!valueStockNums[valueId]) {
        valueStockNums[valueId] = 0;
      }
      valueStockNums[valueId] += sku.stockNum;
    });
  });

  let soldOutValueIds = [];
  for (const valueId in valueStockNums) {
    if (valueStockNums[valueId] === 0) {
      soldOutValueIds.push(parseInt(valueId, 10));
    }
  }

  // 2. 找出选中后，剩下的是售罄的规则值编号
  if (selectedValueIds.length) {
    skus.forEach(sku => {
      if (sku.stockNum !== 0) {
        return;
      }
      selectedValueIds.forEach(selectedValueId => {
        if (sku.specValueIds.includes(selectedValueId)) {
          sku.specValueIds.forEach(valueId => {
            if (valueId === selectedValueId) {
              return;
            }
            soldOutValueIds.push(valueId);
          });
        }
      });
    });
  }

  return soldOutValueIds;
};

const generateSelectedText = (product, selectedValueIds) => {
  const requiredSpecNames = [];
  const selectedValueNames = [];
  product.spec.specs.map(spec => {
    if (!selectedValueIds[spec.id]) {
      requiredSpecNames.push(spec.name);
      return;
    }
    spec.values.forEach(value => {
      if (value.id === selectedValueIds[spec.id]) {
        selectedValueNames.push(value.name);
      }
    });
  });

  // 还有规格没选完，提示没选完的规格名称
  if (requiredSpecNames.length) {
    return requiredSpecNames.join(' / ');
  }

  // 全部规格选完，提示已选的规格值
  return '已选：' + selectedValueNames.join(' / ');
};

const SkuPicker = ({product, isOpened, source, onClose, setSelectedText, updateCartCount}) => {
  // 当前可购买的数量
  const [stockNum, setStockNum] = useState(product.stockNum);

  // 当前的价格范围
  const [priceText, setPriceText] = useState('');
  useEffect(() => {
    setPriceText(getPriceText(product.skus));
  }, []);

  // 购买的数量
  const [quantity, setQuantity] = useState(1);

  // 售罄的规则值编号
  const [soldOutValueIds, setSoldOutValueIds] = useState([]);
  useEffect(() => {
    const soldOutValueIds = calSoldOutValueIds(product.skus);
    if (soldOutValueIds.length) {
      setSoldOutValueIds(soldOutValueIds);
    }
  }, []);

  // 当前选中的规格值编号
  const [selectedValueIds, setSelectedValueIds] = useState({});
  const [skuId, setSkuId] = useState();

  const selectValueId = (i, valueId) => {
    // 不可选择已售罄的规格值
    if (soldOutValueIds.includes(valueId)) {
      return;
    }

    if (selectedValueIds[i] === valueId) {
      delete selectedValueIds[i];
    } else {
      selectedValueIds[i] = valueId;
    }

    const ids = Object.values(selectedValueIds);

    let stockNum = 0;
    let validSkus = [];
    if (ids.length === 0) {
      stockNum = product.stockNum;
      validSkus = product.skus;
    } else {
      product.skus.forEach(sku => {
        if (contains(sku.specValueIds, ids)) {
          stockNum += sku.stockNum;
          validSkus.push(sku);
        }
      });
    }

    if (validSkus.length === 1) {
      setSkuId(validSkus[0].id);
    }
    setSoldOutValueIds(calSoldOutValueIds(product.skus, ids));
    setStockNum(stockNum);
    setQuantity(stockNum < quantity ? stockNum : quantity);
    setPriceText(getPriceText(validSkus));
    setSelectedValueIds({...selectedValueIds});
    setSelectedText && setSelectedText(generateSelectedText(product, selectedValueIds));
  };

  const unit = product.configs.unit || '件';

  const create = (source) => {
    if (source === 'cart') {
      createCart();
    } else {
      createOrder();
    }
  };

  const check = () => {
    for (const spec of product.spec.specs) {
      if (!selectedValueIds[spec.id]) {
        $.err('请选择' + spec.name);
        return false;
      }
    }
    return true;
  };

  const createCart = async () => {
    if (!check()) {
      return;
    }

    const ret = await $.post({
      url: $.apiUrl('carts'),
      data: {
        quantity,
        skuId,
      },
    });

    $.ret(ret).suc(onClose);

    if (ret.exists && updateCartCount) {
      updateCartCount();
    }
  };

  const createOrder = () => {
    if (!check()) {
      return;
    }

    onClose();
    Taro.navigateTo({
      url: $.url('orders/new'),
    });
  };

  const handlePreviewImage = () => {
    Taro.previewImage({
      urls: [product.image],
    });
  };

  return (
    <AtFloatLayout isOpened={isOpened} onClose={onClose} className="sku-picker">
      <View m3 toBetween>
        <View flex>
          <Image w24 h24 rounded1 overflowHidden src={product.image} onClick={handlePreviewImage}/>
          <View>
            <View mx2 textBase>
              {product.name}
              <View brand500>
                {priceText}
              </View>
            </View>
          </View>
        </View>
        <View text3XL fontHairline mt="-1rem" gray500 onClick={onClose}>&times;</View>
      </View>

      <View maxH="60vh" overflowYScroll mb="56px">
        <View m3 column className="border-b">
          {product.spec.specs.map((spec) => {
            return (
              <View className="sku-picker-spec-item">
                <View className="sku-picker-spec-name">{spec.name}</View>
                <View>
                  {spec.values.map(value => {
                    return (
                      <Text
                        className={clsx('sku-picker-spec-value', {
                          active: selectedValueIds[spec.id] === value.id,
                          disabled: soldOutValueIds.includes(value.id),
                        })}
                        onClick={selectValueId.bind(this, spec.id, value.id)}
                      >
                        {value.name}
                      </Text>
                    );
                  })}
                </View>
              </View>
            );
          })}
        </View>

        <View m3 toCenterY toBetween>
          <Text mr2 textSM css={{whiteSpace: 'nowrap'}}>数量</Text>
          <View toCenterY toBetween>
            <Text gray500 textXS mr2>
              {product.maxOrderQuantity > 0 && `每人限购 ${product.maxOrderQuantity} ${unit}`}
              {' '}
              剩下 {stockNum} {unit}
            </Text>
            {' '}
            <AtInputNumber
              type="digit"
              size="large"
              min={1}
              max={stockNum}
              value={quantity}
              onChange={setQuantity}
            />
          </View>
        </View>
      </View>

      <FooterBar>
        <ActionButtonGroup createCartOrOrder={product.createCartOrOrder} source={source} onClick={create}/>
      </FooterBar>
    </AtFloatLayout>
  );
};

export default SkuPicker;
