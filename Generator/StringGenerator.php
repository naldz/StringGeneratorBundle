<?php

namespace Naldz\Bundle\StringGeneratorBundle\Generator;

class StringGenerator
{
    public function generate($prefix = '', $moreEntropy = true)
    {
        $id = uniqid($prefix, $moreEntropy);
        return str_replace('.', '-', $id);
    }
}