<?php

namespace Omnipay\Amex\Message;

class AuthorizeRequest extends AbstractRequest
{
    protected function getCommand()
    {
        return 'pay';
    }
}
