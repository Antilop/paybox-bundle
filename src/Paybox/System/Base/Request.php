<?php

namespace Antilop\Bundle\PayboxBundle\Paybox\System\Base;

use Antilop\Bundle\PayboxBundle\Paybox\AbstractRequest;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Kernel;

class Request extends AbstractRequest
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * Constructor.
     *
     * @param array                $parameters
     * @param array                $servers
     * @param FormFactoryInterface $factory
     *
     * @throws InvalidConfigurationException If the hash_hmac() function of PECL hash is not available.
     */
    public function __construct(array $parameters, array $servers, FormFactoryInterface $factory)
    {
        if (!function_exists('hash_hmac')) {
            throw new InvalidConfigurationException('Function "hash_hmac()" unavailable. You need to install "PECL hash >= 1.1".');
        }

        parent::__construct($parameters, $servers['system']);

        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function initGlobals(array $parameters)
    {
        $this->globals = array(
            'production' => isset($parameters['production']) ? $parameters['production'] : false,
            'currencies' => $parameters['currencies'],
            'site' => $parameters['site'],
            'rank' => $parameters['rank'],
            'login' => $parameters['login'],
            'hmac_key' => $parameters['hmac']['key'],
            'hmac_algorithm' => $parameters['hmac']['algorithm'],
            'hmac_signature_name' => $parameters['hmac']['signature_name'],
            'simulate_error_enabled' => isset($parameters['simulate_error']['enabled']) ? $parameters['simulate_error']['enabled'] : false,
        );

        if ($this->simulateErrorIsEnabled()) {
            $this->globals['simulate_error_code'] = $parameters['simulate_error']['code'];
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initParameters()
    {
        $this->setParameter('PBX_SITE', $this->globals['site']);
        $this->setParameter('PBX_RANG', $this->globals['rank']);
        $this->setParameter('PBX_IDENTIFIANT', $this->globals['login']);
        $this->setParameter('PBX_HASH', $this->globals['hmac_algorithm']);
    }

    /**
     * Sets a parameter.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Request
     */
    public function setParameter($name, $value)
    {
        /**
         * PBX_RETOUR have to be ended by ";Sign:K"
         */
        if ('PBX_RETOUR' == $name = strtoupper($name)) {
            $value = $this->verifyReturnParameter($value);
        }

        return parent::setParameter($name, $value);
    }

    /**
     * Parameter PBX_RETOUR must contain the string ";Sign:K" at the end for ipn signature verification.
     *
     * @param  string $value
     *
     * @return string
     */
    protected function verifyReturnParameter($value)
    {
        if (false !== preg_match('`[^\:]+\:k`i', $value)) {
            $vars = explode(';', $value);

            array_walk($vars, function ($value, $key) use (&$vars) {
                if (false !== stripos($value, ':K')) {
                    unset($vars[$key]);
                }
            });

            $value = implode(';', $vars);
        }

        return sprintf(
            '%s;%s:K',
            $value,
            $this->globals['hmac_signature_name']
        );
    }

    /**
     * Returns all parameters set for a payment.
     *
     * @return array
     */
    public function getParameters()
    {
        if (null === $this->getParameter('PBX_HMAC')) {
            $this->setParameter('PBX_TIME', date('c'));
            $this->setParameter('PBX_HMAC', strtoupper($this->computeHmac()));
        }

        $resolver = new ParameterResolver($this->globals['currencies']);

        return $resolver->resolve($this->parameters);
    }

    /**
     * Returns a form with defined parameters.
     *
     * @param  array $options
     *
     * @return Form
     */
    public function getForm($options = array())
    {
        $options['csrf_protection'] = false;

        $parameters = $this->getParameters();
        $builder = $this->factory->createNamedBuilder(
            '',
            'Symfony\Component\Form\Extension\Core\Type\FormType',
            $parameters,
            $options
        );
        foreach ($parameters as $key => $value) {
            $builder->add($key, 'Symfony\Component\Form\Extension\Core\Type\HiddenType');
        }

        return $builder->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        $server = $this->getServer();

        return sprintf(
            '%s://%s%s',
            $server['protocol'],
            $server['host'],
            $server['system_path']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function simulateErrorIsEnabled()
    {
        return filter_var($this->globals['simulate_error_enabled'], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * {@inheritdoc}
     */
    public function getSimulateErrorCode()
    {
        if (!$this->simulateErrorIsEnabled()) {
            return null;
        }

        return $this->globals['simulate_error_code'];
    }
}
