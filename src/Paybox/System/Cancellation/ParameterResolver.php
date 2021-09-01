<?php

namespace Antilop\Bundle\PayboxBundle\Paybox\System\Cancellation;

use Antilop\Bundle\PayboxBundle\Paybox\AbstractParameterResolver;

class ParameterResolver extends AbstractParameterResolver
{
    /**
     * @var array
     */
    private $knownParameters = array(
        'VERSION',
        'TYPE',
        'SITE',
        'IDENTIFIANT',
        'MACH',
        'HMAC',
        'TIME',
        'ABONNEMENT',
        'REFERENCE',
    );

    /**
     * @var array
     */
    private $requiredParameters = array(
        'VERSION',
        'TYPE',
        'SITE',
        'IDENTIFIANT',
        'MACH',
        'HMAC',
        'TIME',
    );

    /**
     * {@inheritdoc}
     */
    public function resolve(array $parameters)
    {
        $this->initResolver();

        return $this->resolver->resolve($parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function initResolver()
    {
        $this->resolver->setRequired($this->requiredParameters);
        $this->resolver->setDefined(array_diff($this->knownParameters, $this->requiredParameters));
        $this->initAllowed();
    }

    /**
     * Initialize allowed values for the cancellation OptionResolver.
     */
    protected function initAllowed()
    {
        $this->resolver
            ->setAllowedValues('VERSION', array('001'))
            ->setAllowedValues('TYPE', array('001'))
        ;
    }
}
