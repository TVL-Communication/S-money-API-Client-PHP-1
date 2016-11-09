<?php

namespace Smoney\Smoney\Client;

use Smoney\Smoney\Client\AbstractClient;
use Smoney\Smoney\Facade\CardPaymentFacade;

/**
 * Class CardPaymentClient
 */
class CardPaymentClient extends AbstractClient
{
    /**
     * @param CardPaymentFacadeFacade $payment
     *
     * @return string
     */
    public function create(CardPaymentFacade $payment)
    {
        $uri = 'payins/cardpayments';
        $body = $this->serializer->serialize($payment, 'json');
        
        return $this->action('POST', $uri, ['body' => $body]);
    }

    /**
     * @param CardPaymentFacadeFacade $payment
     *
     * @return string
     */
    public function createEcv(CardPaymentFacade $payment)
    {
        $payment->availableCards = 'CB;E_CV';
        
        return $this->create($payment);
    }

    /**
     * @param CardPaymentFacadeFacade $payment
     *
     * @return string
     */
    public function createSofort(CardPaymentFacade $payment)
    {
        $payment->availableCards = 'SOFORT_BANKING';
        
        return $this->create($payment);
    }

    /**
     * @param int $orderId
     *
     * @return CardPaymentFacade
     */
    public function get($orderId)
    {
        $uri = 'payins/cardpayments/'.$orderId;
        $res = $this->action('GET', $uri);

        return $this->serializer->deserialize($res, 'Smoney\Smoney\Facade\CardPaymentFacade', 'json');
    }

    /**
     * @param string $appUserId
     *
     * @return ArrayCollection
     */
    public function index()
    {
        $uri = 'payins/cardpayments';
        $res = $this->action('GET', $uri);

        return $this->serializer->deserialize($res, 'ArrayCollection<Smoney\Smoney\Facade\CardPaymentFacade>', 'json');
    }

    /**
     * @param string $uri
     * @param string $orderId
     * @param int    $amount
     * @param int    $fee
     *
     * @return int    $fee
     */
    public function baseRefund($uri, $orderId, $amount, $fee)
    {
        $refund = [
            'orderId' => $orderId,
            'amount' => $amount,
            'fee' => $fee
        ];
        $body = $this->serializer->serialize($refund, 'json');
        $res = $this->action('POST', $uri, ['body' => $body]);
        
        return $this->serializer->deserialize($res, 'Smoney\Smoney\Facade\CardPaymentFacade', 'json');
    }

    /**
     * @param string $originalOrderId
     * @param string $newOrderId
     * @param int    $amount
     * @param int    $fee
     */
    public function refund($originalOrderId, $newOrderId, $amount, $fee = 0)
    {
        $uri = 'payins/cardpayments/'.$originalOrderId.'/refunds';
        
        return $this->baseRefund($uri, $newOrderId, $amount, $fee);
    }

    /**
     * @param string $orderIdGlobal
     * @param string $originalOrderId
     * @param string $newOrderId
     * @param int    $amount
     * @param int    $fee
     */
    public function refundOneFromMultiBeneficiary($orderIdGlobal, $originalSubOrderId, $newOrderId, $amount, $fee = 0)
    {
        $uri = '/payins/cardpayments/'.$orderIdGlobal.'/payments/'.$originalSubOrderId.'/refunds';

        return $this->baseRefund($uri, $newOrderId, $amount, $fee);
    }


    /**
     * @param string $orderIdGlobal
     * @param string $originalOrderId
     */
    public function getOneFromMultiBeneficiary($orderIdGlobal, $originalOrderId)
    {
        $uri = '/payins/cardpayments/'.$orderIdGlobal.'/payments/'.$originalOrderId;

        $res = $this->action('GET', $uri);

        return $this->serializer->deserialize($res, 'Smoney\Smoney\Facade\CardPaymentFacade', 'json');
    }
}
