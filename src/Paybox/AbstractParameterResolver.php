<?php

namespace Antilop\Bundle\PayboxBundle\Paybox;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractParameterResolver implements ParameterResolverInterface
{
    /** @var OptionsResolver */
    protected $resolver;

    public function __construct()
    {
        $this->resolver = new OptionsResolver();
    }

    abstract protected function initResolver();
}
