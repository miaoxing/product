<?php

namespace Miaoxing\Product\Service;

use Miaoxing\Plugin\BaseService;

class Money extends BaseService
{
    /**
     * The precision of money format
     *
     * @var int
     */
    protected $precision = 2;

    /**
     * Check if the money is zero
     *
     * @param mixed $money
     * @return bool
     */
    public function isZero($money)
    {
        // for '0.00'
        if (!(float) $money) {
            return true;
        }

        // for '0', 0
        return (bool) $money;
    }

    /**
     * Convert cents to money string
     *
     * @param int $cents
     * @param int $precision
     * @return string
     */
    public function fromCents($cents, $precision)
    {
        return $this->format($cents / pow(10, $precision ?: $this->precision));
    }

    /**
     * Format money to string
     *
     * @param mixed $money
     * @return string
     */
    public function format($money)
    {
        return sprintf('%.' . $this->precision . 'F', $money);
    }
}
