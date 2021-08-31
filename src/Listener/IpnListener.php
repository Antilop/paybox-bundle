<?php

namespace Antilop\Bundle\PayboxBundle\Listener;

use Antilop\Bundle\PayboxBundle\Event\PayboxResponseEvent;

class IpnListener
{
    public function onPayboxIpnResponse(PayboxResponseEvent $event) {}
}
