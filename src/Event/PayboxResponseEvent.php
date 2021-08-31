<?php

namespace Antilop\Bundle\PayboxBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PayboxResponseEvent extends Event
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var boolean
     */
    private $verified;

    /**
     * Constructor.
     *
     * @param array   $data
     * @param boolean $verified
     */
    public function __construct(array $data, $verified = false)
    {
        $this->data = $data;
        $this->verified = (bool) $verified;
    }

    /**
     * Returns all parameters sent on IPN.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns true if signature verification was successful.
     *
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }
}
