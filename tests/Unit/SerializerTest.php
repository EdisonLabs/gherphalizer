<?php

namespace EdisonLabs\Gherphalizer\Tests\Unit;

use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use EdisonLabs\Gherphalizer\Serializer;
use EdisonLabs\Gherphalizer\Tests\GherphalizerTestBase;
use Nette\PhpGenerator\PsrPrinter;

/**
 * Tests for \EdisonLabs\Gherphalizer\Serializer
 */
class SerializerTest extends GherphalizerTestBase
{
    /**
     * @covers \EdisonLabs\Gherphalizer\Serializer::serialize
     */
    public function testSerialize()
    {
        $keywords = new ArrayKeywords([
            'en' => [
                'feature'          => 'Feature',
                'background'       => 'Background',
                'scenario'         => 'Scenario',
                'scenario_outline' => 'Scenario Outline',
                'examples'         => 'Examples',
                'given'            => 'Given',
                'when'             => 'When',
                'then'             => 'Then',
                'and'              => 'And',
                'but'              => 'But',
            ],
        ]);
        $lexer = new Lexer($keywords);
        $parser = new Parser($lexer);
        $feature = $parser->parse(file_get_contents(dirname(__FILE__).'/../fixtures/contact-form.feature'));

           $serializer = new Serializer($this->defaultConfig['files'], $this->defaultConfig['locations'], $this->defaultConfig['output-dir']);
        $file = $serializer->serialize($feature);
        $this->assertInstanceOf('Nette\PhpGenerator\PhpFile', $file);

        $filename = $this->defaultConfig['output-dir']."/FeatureContactForm.php";

        $printer = new PsrPrinter();
        file_put_contents($filename, $printer->printFile($file));

        $this->assertFileEquals('tests/fixtures/FeatureContactForm.php', $filename);
    }
}
