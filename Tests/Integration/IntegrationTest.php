<?php

namespace  Naldz\Bundle\StringGeneratorBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use Naldz\Bundle\StringGeneratorBundle\Generator\StringGenerator;
use Naldz\Bundle\StringGeneratorBundle\Tests\Helper\App\AppKernel;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Input\ArgvInput;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{

    protected $appRoot;
    protected $kernel;
    protected $application;
    protected $env = 'dev';
    protected $fs;

    public function setUp()
    {
        $this->fs = new FileSystem();
        $this->fs->remove(array($this->appRoot.'/cache', $this->appRoot.'/logs'));

        $this->appRoot = __DIR__.'/../Helper/App';
        $this->kernel = new AppKernel($this->env, true);
        $this->application = new Application($this->kernel);
        $this->application->setAutoExit(false);
        $this->kernel->boot();
    }

    public function testServiceIsAvailableInContainer()
    {
        $generator = $this->kernel->getContainer()->get('string_generator.generator');
        $this->assertInstanceOf('Naldz\Bundle\StringGeneratorBundle\Generator\StringGenerator', $generator);
    }

    public function testCommandIsRegistered()
    {
        $input = new ArgvInput(array(null, '--version'));
        $output = new StreamOutput(fopen('php://memory', 'w', false));
        $this->application->run($input, $output);
        
        $command = $this->application->find('string-generator:generate');
        $this->assertInstanceOf('Naldz\Bundle\StringGeneratorBundle\Command\GenerateStringCommand', $command);
        
    }

}