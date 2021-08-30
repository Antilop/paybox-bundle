<?php

namespace Antilop\Bundle\PayboxBundle\Paybox\System\Base;

use Antilop\Bundle\PayboxBundle\Paybox\AbstractParameterResolver;

class ParameterResolver extends AbstractParameterResolver
{
    /**
     * @var array All availables parameters for payments requests.
     */

    private $knownParameters = [
        'PBX_1EURO_CODEEXTERNE',
        'PBX_1EURO_DATA',
        'PBX_2MONTn',
        'PBX_3DS',
        'PBX_ANNULE',
        'PBX_ARCHIVAGE',
        'PBX_ATTENTE',
        'PBX_AUTOSEULE',
        'PBX_CK_ONLY',
        'PBX_CMD',
        'PBX_CODEFAMILLE',
        'PBX_DATEn',
        'PBX_DEVISE',
        'PBX_DIFF',
        'PBX_DISPLAY',
        'PBX_EFFECTUE',
        'PBX_EMPREINTE',
        'PBX_ENTITE',
        'PBX_ERRORCODETEST',
        'PBX_GROUPE',
        'PBX_HASH',
        'PBX_HMAC',
        'PBX_IDABT',
        'PBX_IDENTIFIANT',
        'PBX_LANGUE',
        'PBX_MAXICHEQUE_DATA',
        'PBX_NBCARTESKDO',
        'PBX_NETRESERVE_DATA',
        'PBX_NOM_MARCHAND',
        'PBX_ONEY_DATA',
        'PBX_PAYPAL_DATA',
        'PBX_PORTEUR',
        'PBX_RANG',
        'PBX_REFABONNE',
        'PBX_REFUSE',
        'PBX_REPONDRE_A',
        'PBX_RETOUR',
        'PBX_RUF1',
        'PBX_SITE',
        'PBX_SOURCE',
        'PBX_TIME',
        'PBX_TOTAL',
        'PBX_TYPECARTE',
        'PBX_TYPEPAIEMENT',
    ];

    /**
     * @var array Requireds parameters for a standard payment request.
     */
    private $requiredParameters = array(
        'PBX_SITE',
        'PBX_RANG',
        'PBX_TOTAL',
        'PBX_DEVISE',
        'PBX_CMD',
        'PBX_PORTEUR',
        'PBX_RETOUR',
        'PBX_IDENTIFIANT',
        'PBX_HASH',
        'PBX_HMAC',
        'PBX_TIME',
    );

    /**
     * @var array
     */
    private $currencies;

    /**
     * Constructor initialize all available parameters.
     *
     * @param array $currencies
     */
    public function __construct(array $currencies)
    {
        parent::__construct();

        $this->currencies = $currencies;
    }

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
     * Initialize allowed values.
     *
     * @see http://www.fastwrite.com/resources/core/iso-currency-codes/index.php
     */
    protected function initAllowed()
    {
        $this->resolver
            ->setAllowedValues('PBX_DEVISE', $this->currencies)
            ->setAllowedValues('PBX_RUF1', array(
                'GET',
                'POST',
            ))
            ->setAllowedValues('PBX_TYPECARTE', array(
                'CB',
                'VISA',
                'EUROCARD_MASTERCARD',
                'E_CARD',
                'MAESTRO',
                'AMEX',
                'DINERS',
                'JCB',
                'COFINOGA',
                'SOFINCO',
                'AURORE',
                'CDGP',
                '24H00',
                'RIVEGAUCHE',
                'BCMC',
                'PAYPAL',
                'UNEURO',
                '34ONEY',
                'NETCDGP',
                'SVS',
                'KADEOS',
                'PSC',
                'CSHTKT',
                'LASER',
                'EMONEO',
                'IDEAL',
                'ONEYKDO',
                'ILLICADO',
                'MAXICHEQUE',
                'KANGOUROU',
                'FNAC',
                'CYRILLUS',
                'PRINTEMPS',
                'CONFORAMA',
                'LEETCHI',
                'PAYBUTTING'
            ))
        ;
    }
}
