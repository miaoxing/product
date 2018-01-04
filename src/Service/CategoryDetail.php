<?php

namespace Miaoxing\Product\Service;

class CategoryDetail extends \Miaoxing\Plugin\BaseModel
{
    protected $table = 'categoryDetails';

    protected $providers = [
        'db' => 'app.db',
    ];
}
