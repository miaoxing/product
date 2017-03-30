<?php

namespace Miaoxing\Product\Service;

class CategoryDetail extends \miaoxing\plugin\BaseModel
{
    protected $table = 'categoryDetails';

    protected $providers = [
        'db' => 'app.db',
    ];
}
