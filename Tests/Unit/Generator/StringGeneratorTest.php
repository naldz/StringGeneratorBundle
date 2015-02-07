<?php

namespace  Naldz\Bundle\StringGeneratorBundle\Tests\Unit\Generator;

use Naldz\Bundle\StringGeneratorBundle\Generator\StringGenerator;

class StringGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $prefix = '530f27fa138041.';
    private $stringGenerator;
    
    public function setUp()
    {
        $this->stringGenerator = new StringGenerator();
    }
    
    public function testPrefixIsPresentInGeneratedString()
    {
        $prefix = $this->prefix;

        $id = $this->stringGenerator->generate($this->prefix);
        $this->assertRegExp("/^$prefix/", $id);
        
        $id = $this->stringGenerator->generate($this->prefix, false);
        $this->assertRegExp("/^$prefix/", $id);
    }

    public function testMoreEntropyTrue()
    {
        $moreEntropyLength = 23;
        $id = $this->stringGenerator->generate($this->prefix);
        $this->assertEquals(strlen($this->prefix) + $moreEntropyLength , strlen($id));
    }
    
    public function testMoreEntropyFalse()
    {
        $noEntropyLength = 13;
        $id = $this->stringGenerator->generate($this->prefix, false);
        $this->assertEquals(strlen($this->prefix) + $noEntropyLength , strlen($id));
    }
    
    public function testSingleThreadCollision()
    {
        $generatedStrings = array();
        
        for($i=0; $i<=10; $i++) {
            $generatedStrings[] = $this->stringGenerator->generate($this->prefix);
        }

        $matchCount = array_count_values($generatedStrings);
        
        foreach ($generatedStrings as $index => $id) {
            $this->assertEquals(1, $matchCount[$id]);
        }
        
    }
    
}