<?php

namespace Omnipay\Amex\Message;

/**
 *  E-xact Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $url = 'https://amer.amxvpos.com/vpcdps';

    abstract protected function getCommand();

    public function getData()
    {
        $data = array(
            'vpc_AccessCode'  => $this->getAccessCode(),
            'vpc_Command'     => $this->getCommand(),
            'vpc_Merchant'    => $this->getMerchant(),
            'vpc_User'        => $this->getUsername(),
            'vpc_Password'    => $this->getPassword(),
            'vpc_MerchTxnRef' => $this->getMerchTxnRef(),
            'vpc_OrderInfo'   => $this->getOrderId(),
            'vpc_Amount'      => $this->getAmountInteger(),
            'vpc_Version'     => 1
        );

        if ($card = $this->getCard()) {
            $data['vpc_CardNum']          = $card->getNumber();
            $data['vpc_CardExp']          = $card->getExpiryDate('ym');
            $data['vpc_CardSecurityCode'] = $card->getCvv();
            $data['vpc_AVS_Street01']     = $card->getBillingAddress1();
            $data['vpc_AVS_City']         = $card->getBillingCity();
            $data['vpc_AVS_StateProv']    = $card->getBillingState();
            $data['vpc_AVS_PostCode']     = $card->getBillingPostcode();
            $data['vpc_AVS_Country']      = $card->getBillingCountry();
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post(
            $this->getEndpoint(),
            null,
            $data,
            array(
              'exceptions' => false
            )
        )->send();

        return $this->createResponse($httpResponse);
    }

    public function getEndpoint()
    {
        return $this->url;
    }

    protected function createResponse($httpResponse)
    {
        if ($httpResponse->isError()) {
            return new ErrorResponse($this, $httpResponse);
        } else {
            $responseMap = array();
            $pairArray = explode("&", $httpResponse->getBody());
            foreach ($pairArray as $pair) {
                $param = explode("=", $pair);
                $responseMap[urldecode($param[0])] = urldecode($param[1]);
            }

            return new Response($this, $responseMap);
        }
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

    public function getMerchTxnRef()
    {
        return $this->getParameter('merchTxnRef');
    }

    public function setMerchTxnRef($value)
    {
        return $this->setParameter('merchTxnRef', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
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
}
