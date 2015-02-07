<?php

namespace Naldz\Bundle\StringGeneratorBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Naldz\Bundle\DBPatcherBundle\DependencyInjection\Compiler\ConfigurationFilterPass;

class StringGeneratorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
    }
}