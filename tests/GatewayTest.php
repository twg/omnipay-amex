<?php

namespace Omnipay\Amex;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->initialize(array(
          'username' => 'test',
          'password' => 'test'
        ));
    }

    // Override broken test in base class of omnipay-tests lib.
    public function testPurchaseParameters()
    {
        if ($this->gateway->supportsPurchase()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->purchase();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testAuthorizeApproved()
    {
        $this->setMockHttpResponse('AuthorizeApproved.txt');
        $request = $this->gateway->authorize(array(
          'amount' => '1.00',
          'orderId' => '123',
          'merchTxnRef' => '123',
          'card'   => $this->getValidCard()
        ));

        $this->assertInstanceOf('\Omnipay\Amex\Message\AuthorizeRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('3911', $response->getTransactionReference());
        $this->assertTrue($response->isApproved());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testAuthorizeDeclined()
    {
        $this->setMockHttpResponse('AuthorizeDeclined.txt');
        $request = $this->gateway->authorize(array(
          'amount' => '1.00',
          'orderId' => '123',
          'merchTxnRef' => '123',
          'card'   => $this->getValidCard()
        ));

        $this->assertInstanceOf('\Omnipay\Amex\Message\AuthorizeRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('3877', $response->getTransactionReference());
        $this->assertFalse($response->isApproved());
        $this->assertSame('2', $response->getCode());
        $this->assertSame('Declined', $response->getMessage());
    }

    public function testCaptureApproved()
    {
        $this->setMockHttpResponse('CaptureApproved.txt');
        $request = $this->gateway->capture(array(
          'amount' => '1.00',
          'orderId' => '124',
          'merchTxnRef' => '124',
          'transactionNo' => '3000'
        ));

        $this->assertInstanceOf('\Omnipay\Amex\Message\CaptureRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('3922', $response->getTransactionReference());
        $this->assertTrue($response->isApproved());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testRefundApproved()
    {
        $this->setMockHttpResponse('RefundApproved.txt');
        $request = $this->gateway->refund(array(
          'amount' => '1.00',
          'orderId' => '125',
          'merchTxnRef' => '125/3',
          'transactionNo' => '3925'
        ));

        $this->assertInstanceOf('\Omnipay\Amex\Message\RefundRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('3926', $response->getTransactionReference());
        $this->assertTrue($response->isApproved());
        $this->assertSame('Approved', $response->getMessage());
    }
}
