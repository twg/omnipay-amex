<?php

namespace Omnipay\Amex\Message;

class CaptureRequest extends AbstractRequest
{
    protected function getCommand()
    {
        return 'capture';
    }
}
