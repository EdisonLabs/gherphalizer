<?php

namespace EdisonLabs\Gherphalizer\Unit;

use Composer\Composer;
use EdisonLabs\Gherphalizer\PluginHandler;
use PHPUnit\Framework\TestCase;

/**
 * Tests for EdisonLabs\Gherphalizer\PluginHandler
 */
class PluginHandlerTest extends TestCase
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
    protected $io;

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

        $this->io = $this->getMockBuilder('Composer\IO\IOInterface')->getMock();
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
    public function testPlugin()
    {
        $gherphalizer = new PluginHandler(new Composer(), $this->io, $this->defaultConfig);
        $this->assertEquals($this->defaultConfig['files'], $gherphalizer->fileNamePatterns);
        $this->assertEquals($this->defaultConfig['locations'], $gherphalizer->sourcePaths);
        $this->assertEquals($this->defaultConfig['output-dir'], $gherphalizer->outputDir);
        $this->assertTrue($gherphalizer->isConfigured);

        $gherphalizer->serializeGherkinFiles();
        $this->assertFileExists('/tmp/gherphalizer/ContactForm.php');
        $this->assertFileExists('/tmp/gherphalizer/CommentForm.php');
    }

    /**
     * Tests the plugin with missing files configuration.
     */
    public function testMissingFilesConfiguration()
    {
        $configParameters = $this->defaultConfig;
        unset($configParameters['files']);
        $this->expectException(\RuntimeException::class);
        $gherphalizer = new PluginHandler(new Composer(), $this->io, $configParameters);
    }

    /**
     * Tests the plugin with missing locations configuration.
     */
    public function testMissingLocationsConfiguration()
    {
        $configParameters = $this->defaultConfig;
        unset($configParameters['locations']);
        $this->expectException(\RuntimeException::class);
        $gherphalizer = new PluginHandler(new Composer(), $this->io, $configParameters);
    }

    /**
     * Tests the plugin with missing output-dir configuration.
     */
    public function testMissingOuptutDirConfiguration()
    {
        $configParameters = $this->defaultConfig;
        unset($configParameters['output-dir']);
        $this->expectException(\RuntimeException::class);
        $gherphalizer = new PluginHandler(new Composer(), $this->io, $configParameters);
    }
}
