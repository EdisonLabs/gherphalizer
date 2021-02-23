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
    protected function setUp(): void
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
    protected function tearDown(): void
    {
        $files = [
            '/tmp/gherphalizer/FeatureContactForm.php',
            '/tmp/gherphalizer/FeatureCommentForm.php',
        ];

        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        if (is_dir('/tmp/gherphalizer')) {
            rmdir('/tmp/gherphalizer');
        }
    }
}
