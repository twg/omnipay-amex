<?php

namespace Omnipay\Amex\Message;

class RefundRequest extends AbstractRequest
{
    protected function getCommand()
    {
        return 'refund';
    }

    public function getData()
    {
        $this->validate('amount', 'transactionNo');
        $data = parent::getData();
        $data['vpc_TransactionNo'] = $this->getTransactionNo();
        return $data;
    }

    public function getTransactionNo()
    {
        return $this->getParameter('transactionNo');
    }

    public function setTransactionNo($value)
    {
        return $this->setParameter('transactionNo', $value);
    }
}
