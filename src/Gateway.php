<?php

namespace Omnipay\Amex;

use Omnipay\Common\AbstractGateway;

/**
 * Amex Gateway
 *
 * @link http://amxdocs.amxvpos.com/JAPA/manuals/PVPCIG.zip
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Amex VPC';
    }

    public function getDefaultParameters()
    {
        return array(
            'accessCode' => '',
            'merchant' => '',
            'username' => '',
            'password' => '',
        );
    }

    public function supportsPurchase()
    {
        return false;
    }

    public function getAccessCode()
    {
        return $this->getParameter('accessCode');
    }

    public function setAccessCode($value)
    {
        return $this->setParameter('accessCode', $value);
    }

    public function getMerchant()
    {
        return $this->getParameter('merchant');
    }

    public function setMerchant($value)
    {
        return $this->setParameter('merchant', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Amex\Message\AuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Amex\Message\CaptureRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Amex\Message\RefundRequest', $parameters);
    }
}
