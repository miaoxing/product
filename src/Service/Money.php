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
        // for '0.00', '0.0'
        if (!(float) $money) {
            return true;
        }

        // for '0', 0
        return !$money;
    }

    /**
     * Convert cents to money string
     *
     * @param int $cents
     * @param null|int $precision
     * @return string
     */
    public function fromCents($cents, $precision = null)
    {
        return $this->format($cents / pow(10, $precision ?: $this->precision));
    }

    /**
     * Format money to string
     *
     * @param mixed $money
     * @param null|int $precision
     * @return string
     */
    public function format($money, $precision = null)
    {
        return sprintf('%.' . ($precision ?: $this->precision) . 'F', $money);
    }
}
