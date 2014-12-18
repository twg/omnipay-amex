<?php

namespace Omnipay\Amex\Message;

class Response extends \Omnipay\Common\Message\AbstractResponse
{
    public function getTransactionReference()
    {
        return $this->data['vpc_TransactionNo'];
    }

    public function isApproved()
    {
        return $this->data['vpc_TxnResponseCode'] == 0;
    }

    public function getMessage()
    {
        return $this->data['vpc_Message'];
    }

    public function getCode()
    {
        return $this->data['vpc_TxnResponseCode'];
    }

    public function isSuccessful()
    {
        return $this->data['vpc_TxnResponseCode'] == 0;
    }
}
