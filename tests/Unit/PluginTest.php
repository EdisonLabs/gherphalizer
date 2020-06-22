<?php

namespace EdisonLabs\Gherphalizer\Tests\Unit;

use EdisonLabs\Gherphalizer\Plugin;
use Composer\Composer;
use Composer\Script\ScriptEvents;
use EdisonLabs\Gherphalizer\Tests\GherphalizerTestBase;

/**
 * Tests for EdisonLabs\Gherphalizer\Plugin
 */
class PluginTest extends GherphalizerTestBase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $packageMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->packageMock = $this->getMockBuilder('Composer\Package\RootPackage')
            ->disableOriginalConstructor()
            ->setMethods(['getExtra', 'setExtra'])
            ->getMock();
        $this->packageMock->expects($this->once())
            ->method('getExtra')
            ->will($this->returnValue(['gherphalizer' => $this->defaultConfig]));

        $this->eventMock = $this->getMockBuilder('Composer\Script\Event')
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventMock->expects($this->once())
            ->method('isDevMode')
            ->will($this->returnValue(true));
    }

    /**
     * Tests for EdisonLabs\Gherphalizer\Plugin
     */
    public function testPlugin()
    {
        $plugin = new Plugin();

        $capabilities = $plugin->getCapabilities();
        $this->assertEquals(['Composer\Plugin\Capability\CommandProvider' => 'EdisonLabs\Gherphalizer\CommandProvider'], $capabilities);

        $events = $plugin->getSubscribedEvents();
        $this->assertCount(2, $events);
        $this->assertArrayHasKey(ScriptEvents::POST_INSTALL_CMD, $events);
        $this->assertArrayHasKey(ScriptEvents::POST_UPDATE_CMD, $events);
        $this->assertEquals(['postCmd', -1], $events[ScriptEvents::POST_INSTALL_CMD]);
        $this->assertEquals(['postCmd', -1], $events[ScriptEvents::POST_UPDATE_CMD]);

        $io = $this->getMockBuilder('Composer\IO\IOInterface')->getMock();

        $composer = new Composer();
        $composer->setPackage($this->packageMock);
        $plugin->activate($composer, $io);
        $this->assertInstanceOf('\EdisonLabs\Gherphalizer\PluginHandler', $plugin->getPluginHandler());

        $plugin->postCmd($this->eventMock);
        $this->assertFileExists('/tmp/gherphalizer/FeatureContactForm.php');
        $this->assertFileExists('/tmp/gherphalizer/FeatureCommentForm.php');
    }
}
