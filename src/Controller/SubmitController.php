<?php

namespace Antilop\Bundle\PayboxBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGenerator;

class SubmitController extends AbstractController
{
    public function indexAction($reference, $amount, $email)
    {
        $paybox = $this->container->get('paybox.request_handler');

        $params = [
            'PBX_CMD' => $reference,
            'PBX_DEVISE' => '978',
            'PBX_PORTEUR' => $email,
            'PBX_RETOUR' => 'amount:M;reference:R;auto:A;error:E;idTransaction:S',
            'PBX_TOTAL' => $amount,
            'PBX_TYPEPAIEMENT' => 'CARTE',
            'PBX_TYPECARTE' => 'CB',
            'PBX_EFFECTUE' => $this->generateUrl('paybox_submit_return_success', ['status' => 'success'], UrlGenerator::ABSOLUTE_URL),
            'PBX_REFUSE' => $this->generateUrl('paybox_submit_return_failed', ['status' => 'denied'], UrlGenerator::ABSOLUTE_URL),
            'PBX_ANNULE' => $this->generateUrl('paybox_submit_return_canceled', ['status' => 'canceled'], UrlGenerator::ABSOLUTE_URL),
            'PBX_RUF1' => 'POST',
            'PBX_REPONDRE_A' => $this->generateUrl('paybox_ipn', [], UrlGenerator::ABSOLUTE_URL),
        ];

        $simulateErrorCode = $paybox->getSimulateErrorCode();
        if (!empty($simulateErrorCode)) {
            $params['PBX_ERRORCODETEST'] = intval($simulateErrorCode);
        }

        $paybox->setParameters($params);

        return $this->render(
            '@Paybox/form.html.twig',
            [
                'url'  => $paybox->getUrl(),
                'form' => $paybox->getForm()->createView(),
            ]
        );
    }
}
