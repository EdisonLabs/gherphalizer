<?php

namespace EdisonLabs\Gherphalizer\Tests;

use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use Nette\PhpGenerator\Printer;
use PHPUnit\Framework\TestCase;

use EdisonLabs\Gherphalizer\Serializer;

class SerializerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
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
                'but'              => 'But'
            ]
        ]);
        $lexer = new Lexer($keywords);
        $parser = new Parser($lexer);
        $feature = $parser->parse(file_get_contents(dirname(__FILE__).'/fixtures/contact-form.feature'));

        $serializer = new Serializer();
        $class = $serializer->serialize($feature);
        $this->assertInstanceOf('Nette\PhpGenerator\ClassType', $class);

        $name = $class->getName();
        $filename = "tests/output/$name.php";

        $printer = new Printer();
        file_put_contents($filename, "<?php\n\n");
        file_put_contents($filename, $printer->printClass($class), FILE_APPEND);

        $this->assertFileEquals('tests/fixtures/FeatureContactForm.php', 'tests/output/FeatureContactForm.php');
    }
}
