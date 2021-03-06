<?php

namespace Smoney\Smoney\Client;
use Smoney\Smoney\Facade\PayinFacade;
use Smoney\Smoney\Facade\PayinResultFacade;

/**
 * Class PayinClient
 * @package Smoney\Smoney\Client
 */
class PayinClient extends AbstractClient
{
    /**
     * @param PayinFacade $payinFacade
     * @return PayinResultFacade
     */
    public function createPayin(PayinFacade $payinFacade)
    {
        $uri = 'payins/storedcardpayments';
        $body = $this->serializer->serialize($payinFacade, 'json');
        $res = $this->action('POST', $uri, ['body'=>$body]);

        return $this->serializer->deserialize($res, 'Smoney\Smoney\Facade\PayinResultFacade', 'json');
    }
}
