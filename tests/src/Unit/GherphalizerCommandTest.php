<?php

namespace EdisonLabs\Gherphalizer\Unit;

use Composer\Composer;
use EdisonLabs\Gherphalizer\GherphalizerCommand;
use PHPUnit\Framework\TestCase;

/**
 * Tests for EdisonLabs\Gherphalizer\GherphalizerCommand
 */
class GherphalizerCommandTest extends TestCase
{
    /**
     * A valid composer configuration for the plugin.
     *
     * @var array
     */
    protected $defaultConfig;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $inputMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $outputMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $packageMock;

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

        $this->inputMock = $this->getMockBuilder('Symfony\Component\Console\Input\InputInterface')->getMock();
        $this->outputMock = $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')->getMock();
        $this->packageMock = $this->getMockBuilder('Composer\Package\RootPackageInterface')->getMock();
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
     * Tests setting up the plugin correctly.
     */
    public function testGherphalizerCommand()
    {
        $gherphalizerCommand = new GherphalizerCommand();
        $this->assertEquals('gherphalizer', $gherphalizerCommand->getName());
        $this->assertEquals('Serializes Gherkin feature files into PHP files.', $gherphalizerCommand->getDescription());

        $composer = new Composer();
        $composer->setPackage($this->packageMock);
        $gherphalizerCommand->setComposer($composer);
        $gherphalizerCommand->execute($this->inputMock, $this->outputMock, $this->defaultConfig);
        $this->assertFileExists('/tmp/gherphalizer/ContactForm.php');
        $this->assertFileExists('/tmp/gherphalizer/CommentForm.php');
    }
}
