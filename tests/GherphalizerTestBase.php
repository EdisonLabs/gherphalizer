<?php

namespace EdisonLabs\Gherphalizer\Tests;

use PHPUnit\Framework\TestCase;

/**§
 * Tests for \EdisonLabs\Gherphalizer\Serializer
 */
class GherphalizerTestBase extends TestCase
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
                dirname(__FILE__).'/fixtures',
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
}
