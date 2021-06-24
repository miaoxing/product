<?php

namespace Miaoxing\Product\Metadata;

/**
 * @property int|null $id 编号
 * @property int $appId 应用编号
 * @property int $productId 商品编号
 * @property bool $isDefault
 * @property array $specs 规格内容，内容如：[{id,values:[{id,name,image},...]},...]
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property int $createdBy
 * @property int $updatedBy
 * @internal will change in the future
 */
trait ProductSpecTrait
{
}
