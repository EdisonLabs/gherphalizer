<?php

namespace EdisonLabs\Gherphalizer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

/**
 * Class CommandProvider.
 */
class CommandProvider implements CommandProviderCapability
{

    /**
     * {@inheritdoc}
     */
    public function getCommands()
    {
        return array(
            new GherphalizerCommand(),
        );
    }
}
