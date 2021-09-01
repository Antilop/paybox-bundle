<?php

namespace Antilop\Bundle\PayboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IpnController extends AbstractController
{
    public function indexAction(): Response
    {
        $payboxResponse = $this->container->get('paybox.response_handler');
        $result = $payboxResponse->verifySignature();

        return new Response($result ? 'OK' : 'KO');
    }
}
