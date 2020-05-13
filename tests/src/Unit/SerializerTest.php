<?php

namespace EdisonLabs\Gherphalizer\Tests;

use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use Nette\PhpGenerator\PsrPrinter;
use PHPUnit\Framework\TestCase;

use EdisonLabs\Gherphalizer\Serializer;

/**
 * Tests for \EdisonLabs\Gherphalizer\Serializer
 */
class SerializerTest extends TestCase
{
    /**
     * A valid composer configuration for the plugin.
     *
     * @var array
     */
    protected $defaultConfig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->defaultConfig = [
            'files' => [
                '*',
            ],
            'locations' => [
                dirname(__FILE__).'/../../fixtures',
            ],
            'output-dir' => '/tmp/gherphalizer',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $files = [
            '/tmp/gherphalizer/ContactForm.php',
            '/tmp/gherphalizer/CommentForm.php',
        ];
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

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
        $feature = $parser->parse(file_get_contents(dirname(__FILE__).'/../../fixtures/contact-form.feature'));

        $serializer = new Serializer($this->defaultConfig['files'], $this->defaultConfig['locations'], $this->defaultConfig['output-dir']);
        $file = $serializer->serialize($feature);
        $this->assertInstanceOf('Nette\PhpGenerator\PhpFile', $file);

        $filename = dirname(__FILE__)."/../../output/FeatureContactForm.php";

        $printer = new PsrPrinter();
        file_put_contents($filename, $printer->printFile($file));

        $this->assertFileEquals('tests/fixtures/FeatureContactForm.php', 'tests/output/FeatureContactForm.php');
    }
}
