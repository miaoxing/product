<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\HasAppIdTrait;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\SoftDeleteTrait;
use Miaoxing\Product\Metadata\SpecTrait;

/**
 * 规格
 */
class SpecModel extends BaseModel
{
    use HasAppIdTrait;
    use ModelTrait;
    use SoftDeleteTrait;
    use SpecTrait;
}
