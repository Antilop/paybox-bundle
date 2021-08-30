<?php

namespace Antilop\Bundle\PayboxBundle\Transport;

use Antilop\Bundle\PayboxBundle\Paybox\RequestInterface;

interface TransportInterface
{
    /**
     * Prepare and send a message.
     *
     * @param RequestInterface $request Request instance
     *
     * @return string|false The Paybox response
     */
    public function call(RequestInterface $request);

    /**
     * Define the endpoint.
     * It can be an url or a path, depends what control you choose.
     *
     * @param string $endpoint to paybox endpoint
     */
    public function setEndpoint($endpoint);
}
