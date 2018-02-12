<?php

namespace Miaoxing\Product\Job;

use Miaoxing\Queue\Job;
use Miaoxing\Queue\Service\BaseJob;

class ProductSave extends Job
{
    public function __invoke(BaseJob $job, $data)
    {
        $product = wei()->product()->findOneById($data['id']);

        wei()->event->trigger('asyncProductSave', [$product]);

        $job->delete();
    }
}
