<?php

namespace Naldz\Bundle\StringGeneratorBundle\Tests\Unit\Command;

use Naldz\Bundle\StringGeneratorBundle\Command\GenerateStringCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateStringCommandTest extends \PHPUnit_Framework_TestCase
{
    private $application;
    private $definition;
    private $kernel;
    private $container;
    private $idGenerator;
    private $command;
    
    protected function setUp()
    {

        if (!class_exists('Symfony\Component\Console\Application')) {
            $this->markTestSkipped('Symfony Console is not available.');
        }

        //mock the input definition    
        $this->definition = $this->getMockBuilder('Symfony\\Component\\Console\\Input\\InputDefinition')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->definition->expects($this->any())
            ->method('getArguments')
            ->will($this->returnValue(array()));

        $this->definition->expects($this->any())
            ->method('getOptions')
            ->will($this->returnValue(array(
                new InputOption('--verbose', '-v', InputOption::VALUE_NONE, 'Increase verbosity of messages.'),
                new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'),
                new InputOption('--no-debug', null, InputOption::VALUE_NONE, 'Switches off debug mode.'),
            )));

        //mock the kernel
        $this->kernel = $this->getMock('Symfony\\Component\\HttpKernel\\KernelInterface');
        
        //mock the helperset
        $this->helperSet = $this->getMock('Symfony\\Component\\Console\\Helper\\HelperSet');
        
        //mock the idgenerator
        $this->idGenerator = $this->getMockBuilder('Naldz\Bundle\StringGeneratorBundle\Generator\StringGenerator')
            ->disableOriginalConstructor()
            ->getMock();
        $this->idGenerator
            ->expects($this->any())
            ->method('generate')
            ->will($this->returnValue('xxx-yyy-zzz'));
        
        //mock the container
        $this->container = $this->getMock('Symfony\\Component\\DependencyInjection\\ContainerInterface');
        $this->container->expects($this->once())
            ->method('get')
            ->with('string_generator.generator')
            ->will($this->returnValue($this->idGenerator));
        
        //mock the application
        $this->application = $this->getMockBuilder('Symfony\\Bundle\\FrameworkBundle\\Console\\Application')
            ->disableOriginalConstructor()
            ->getMock();
        $this->application->expects($this->any())
            ->method('getDefinition')
            ->will($this->returnValue($this->definition));
        $this->application->expects($this->any())
            ->method('getKernel')
            ->will($this->returnValue($this->kernel));
        $this->application->expects($this->once())
            ->method('getHelperSet')
            ->will($this->returnValue($this->helperSet));
        $this->kernel->expects($this->any())
            ->method('getContainer')
            ->will($this->returnValue($this->container));

        $this->command = new GenerateStringCommand();
        $this->command->setApplication($this->application);
    }
    
    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(array('count' => 3));
        $outputString = $commandTester->getDisplay();
        $jsonDecode = json_decode($outputString);        
        $this->assertCount(3, $jsonDecode);
    }
}
