<?php

namespace EdisonLabs\Gherphalizer\Tests\Unit;

use Composer\Composer;
use EdisonLabs\Gherphalizer\PluginHandler;
use EdisonLabs\Gherphalizer\Tests\GherphalizerTestBase;

/**
 * Tests for EdisonLabs\Gherphalizer\PluginHandler
 */
class PluginHandlerTest extends GherphalizerTestBase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $io;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->io = $this->getMockBuilder('Composer\IO\IOInterface')->getMock();
    }

    /**
     * Tests setting up the plugin correctly.
     */
    public function testPlugin()
    {
        $gherphalizer = new PluginHandler(new Composer(), $this->io, $this->defaultConfig);
        $this->assertEquals($this->defaultConfig['files'], $gherphalizer->fileNamePatterns);
        $this->assertEquals($this->defaultConfig['locations'], $gherphalizer->sourcePaths);
        $this->assertEquals($this->defaultConfig['output-dir'], $gherphalizer->outputDir);
        $this->assertTrue($gherphalizer->isConfigured);

        $gherphalizer->serializeGherkinFiles();
        $this->assertFileExists('/tmp/gherphalizer/FeatureContactForm.php');
        $this->assertFileExists('/tmp/gherphalizer/FeatureCommentForm.php');
    }

    /**
     * Tests the plugin with missing files configuration.
     */
    public function testMissingFilesConfiguration()
    {
        $configParameters = $this->defaultConfig;
        unset($configParameters['files']);
        $this->expectException(\RuntimeException::class);
        new PluginHandler(new Composer(), $this->io, $configParameters);
    }

    /**
     * Tests the plugin with missing locations configuration.
     */
    public function testEmptyLocationsConfiguration()
    {
        $configParameters = $this->defaultConfig;
        unset($configParameters['locations']);
        $this->expectException(\RuntimeException::class);
        new PluginHandler(new Composer(), $this->io, $configParameters);
    }

    /**
     * Tests the plugin with missing locations configuration.
     */
    public function testMissingLocationsConfiguration()
    {
        $configParameters = $this->defaultConfig;
        $configParameters['locations'][] = 'test/missing/location';
        new PluginHandler(new Composer(), $this->io, $configParameters);
        $this->addToAssertionCount(1);
    }

    /**
     * Tests the plugin with missing output-dir configuration.
     */
    public function testMissingOuptutDirConfiguration()
    {
        $configParameters = $this->defaultConfig;
        unset($configParameters['output-dir']);
        $this->expectException(\RuntimeException::class);
        new PluginHandler(new Composer(), $this->io, $configParameters);
    }
}
