<?php

namespace Antilop\Bundle\PayboxBundle\Paybox\System;

class Tools
{
    static public function stringify(array $array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            $result[] = sprintf('%s=%s', $key, $value);
        }

        return implode('&', $result);
    }
}
