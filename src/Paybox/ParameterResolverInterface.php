<?php

namespace Antilop\Bundle\PayboxBundle\Paybox;

/**
 * Interface ParameterResolverInterface
 *
 * @package Antilop\Bundle\PayboxBundle\Paybox
 */
interface ParameterResolverInterface
{
    /**
     * Resolves parameters for a payment call.
     *
     * @param array $parameters
     *
     * @return array
     */
    public function resolve(array $parameters);
}
