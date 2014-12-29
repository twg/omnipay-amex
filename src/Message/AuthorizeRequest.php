<?php

namespace Omnipay\Amex\Message;

class AuthorizeRequest extends AbstractRequest
{
    protected function getCommand()
    {
        return 'pay';
    }

    public function getData()
    {
        $this->validate('amount', 'card');
        return parent::getData();
    }
}
