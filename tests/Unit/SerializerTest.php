<?php

namespace EdisonLabs\Gherphalizer\Tests\Unit;

use EdisonLabs\Gherphalizer\Serializer;
use EdisonLabs\Gherphalizer\Tests\GherphalizerTestBase;

/**
 * Tests for \EdisonLabs\Gherphalizer\Serializer
 */
class SerializerTest extends GherphalizerTestBase
{
    /**
     * Main class for gherphalizer.
     *
     * @var Serializer
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->serializer = new Serializer(
            $this->defaultConfig['files'],
            $this->defaultConfig['locations'],
            $this->defaultConfig['output-dir']
        );
    }

    /**
     * @covers \EdisonLabs\Gherphalizer\Serializer::serialize()
     */
    public function testSerialize()
    {
        $featureFilePath = realpath(dirname(__FILE__).'/../fixtures/contact-form.feature');
        $this->assertFileExists($featureFilePath);

        $feature = $this->serializer->parser->parse(file_get_contents($featureFilePath));
        $file = $this->serializer->serialize($feature);
        $this->assertInstanceOf('Nette\PhpGenerator\PhpFile', $file);

        $this->serializer->createPhpFile($featureFilePath);
        $filename = $this->defaultConfig['output-dir']."/FeatureContactForm.php";
        $this->assertFileExists($filename);
        $this->assertFileEquals('tests/fixtures/FeatureContactForm.php', $filename);
    }

    /**
     * @covers \EdisonLabs\Gherphalizer\Serializer::getGherkinFeatureFiles()
     */
    public function testGetGherkinFeatureFiles()
    {
        $featureFiles = $this->serializer->getGherkinFeatureFiles();
        $this->assertNotEmpty($featureFiles);
        $this->assertCount(2, $featureFiles);
        $this->assertArrayHasKey('comment-form', $featureFiles);
        $this->assertArrayHasKey('contact-form', $featureFiles);
        $this->assertEquals(realpath(dirname(__FILE__).'/../fixtures/contact-form.feature'), $featureFiles['contact-form']);
        $this->assertEquals(realpath(dirname(__FILE__).'/../fixtures/comment-form.feature'), $featureFiles['comment-form']);
    }

    /**
     * @covers \EdisonLabs\Gherphalizer\Serializer::createPhpFiles()
     */
    public function testCreatePhpFiles()
    {
        $phpFiles = $this->serializer->createPhpFiles();
        $this->assertNotEmpty($phpFiles);
        $this->assertCount(2, $phpFiles);
        $this->assertArrayHasKey('comment-form', $phpFiles);
        $this->assertArrayHasKey('contact-form', $phpFiles);
        $this->assertEquals(realpath(dirname(__FILE__).'/../fixtures/contact-form.feature'), $phpFiles['contact-form']);
        $this->assertEquals(realpath(dirname(__FILE__).'/../fixtures/comment-form.feature'), $phpFiles['comment-form']);
        $this->assertFileExists('/tmp/gherphalizer/ContactForm.php');
        $this->assertFileExists('/tmp/gherphalizer/CommentForm.php');
    }

    /**
     * @covers \EdisonLabs\Gherphalizer\Serializer::createPhpFile()
     */
    public function testCreatePhpFile()
    {
        $featureFilePath = realpath(dirname(__FILE__).'/../fixtures/contact-form.feature');
        $this->assertFileExists($featureFilePath);

        $this->serializer->createPhpFile($featureFilePath);
        $filename = $this->defaultConfig['output-dir']."/FeatureContactForm.php";
        $this->assertFileExists($filename);
        $this->assertFileEquals('tests/fixtures/FeatureContactForm.php', $filename);
    }
}
