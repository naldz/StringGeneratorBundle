<?php

namespace Naldz\Bundle\StringGeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateStringCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('string-generator:generate')
            ->setDescription('Generate a set of strings')
            ->addArgument('count', InputArgument::REQUIRED, 'The number of ids to generate')
            ->addArgument('prefix', InputArgument::OPTIONAL, 'The prefix string to the generated strings')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $generator = $container->get('string_generator.generator');

        $generatedIds = array();
        $count = $input->getArgument('count');
        $prefix = $input->getArgument('prefix');

        for ($i = 0; $i < $count; $i++) {
            $generatedStrings[] = $generator->generate($prefix);
        }
        
        $output->writeln(json_encode($generatedStrings));
        
    }
}