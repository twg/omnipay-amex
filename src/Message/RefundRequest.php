<?php

namespace Omnipay\Amex\Message;

class RefundRequest extends AbstractRequest
{
    protected function getCommand()
    {
        return 'refund';
    }
}
