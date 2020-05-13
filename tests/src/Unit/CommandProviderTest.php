<?php

namespace EdisonLabs\Gherphalizer\Unit;

use EdisonLabs\Gherphalizer\CommandProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests for EdisonLabs\Gherphalizer\CommandProvider
 */
class CommandProviderTest extends TestCase
{
    /**
     * Tests for EdisonLabs\Gherphalizer\CommandProvider
     */
    public function testCommandProvider()
    {
        $commandProvider = new CommandProvider();
        $commands = $commandProvider->getCommands();
        $this->assertCount(1, $commands);
        $this->assertInstanceOf('EdisonLabs\Gherphalizer\GherphalizerCommand', $commands[0]);
    }
}
