<?php

namespace EdisonLabs\Gherphalizer\Tests\Unit;

use Composer\Composer;
use EdisonLabs\Gherphalizer\GherphalizerCommand;
use EdisonLabs\Gherphalizer\Tests\GherphalizerTestBase;

/**
 * Tests for EdisonLabs\Gherphalizer\GherphalizerCommand
 */
class GherphalizerCommandTest extends GherphalizerTestBase
{
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
        parent::setUp();

        $this->inputMock = $this->getMockBuilder('Symfony\Component\Console\Input\InputInterface')->getMock();
        $this->outputMock = $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')->getMock();
        $this->packageMock = $this->getMockBuilder('Composer\Package\RootPackageInterface')->getMock();
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
        $this->assertFileExists('/tmp/gherphalizer/FeatureContactForm.php');
        $this->assertFileExists('/tmp/gherphalizer/FeatureCommentForm.php');
    }
}
