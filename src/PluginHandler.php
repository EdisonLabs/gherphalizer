<?php

namespace EdisonLabs\Gherphalizer;

use Composer\Composer;
use Composer\IO\IOInterface;

/**s
 * Class PluginHandler.
 */
class PluginHandler
{

    /**
     * IO object.
     *
     * @var \Composer\IO\IOInterface
     */
    protected $io;

    /**
     * The directory where the generated files will be placed.
     *
     * @var string
     */
    public $outputDir;

    /**
     * The file name patterns to scan for.
     *
     * @var array
     */
    public $fileNamePatterns;

    /**
     * The paths to scan recursively for gherkin feature files.
     *
     * @var array
     */
    public $sourcePaths = array();

    /**
     * Flag indicating whether the plugin has configuration or not.
     *
     * @var bool
     */
    public $isConfigured = false;

    /**
     * PluginHandler constructor.
     *
     * @param \Composer\Composer       $composer
     *   The Composer object.
     * @param \Composer\IO\IOInterface $io
     *   The IO object.
     * @param array                    $configParameters
     *   The config parameters to override the extra config from composer.json.
     */
    public function __construct(Composer $composer, IOInterface $io, array $configParameters = array())
    {
        $this->io = $io;

        $config = $configParameters;
        if (!$config) {
            $extra = $composer->getPackage()->getExtra();

            if (!isset($extra['gherphalizer'])) {
                return;
            }

            $config = $extra['gherphalizer'];
        }

        // Get files.
        if (empty($config['files'])) {
            throw new \RuntimeException('Please configure gherphalizer files in your composer.json');
        }
        $this->fileNamePatterns = $config['files'];

        // Get locations.
        if (empty($config['locations']) || !is_array($config['locations'])) {
            throw new \RuntimeException('Please configure gherphalizer locations in your composer.json');
        }

        $this->sourcePaths = $config['locations'];

        // Get output dir.
        if (empty($config['output-dir'])) {
            throw new \RuntimeException('Please configure gherphalizer output-dir in your composer.json');
        }
        $this->outputDir = $config['output-dir'];

        $this->isConfigured = true;
    }

    /**
     * Serializes Gherkin files into PHP classes.
     */
    public function serializeGherkinFiles()
    {
        if (!$this->isConfigured) {
            $this->io->write('> WARNING: Gherphalizer is not configured', true);

            return;
        }

        foreach ($this->sourcePaths as $sourcePath) {
            if (!is_dir($sourcePath)) {
                $this->io->write('> WARNING: one or more source locations do not exist, make sure all configured source locations exist', true);

                return;
            }
        }

        $serializer = new Serializer($this->fileNamePatterns, $this->sourcePaths, $this->outputDir);

        $processedFiles = $serializer->createPhpFiles();

        if (empty($processedFiles)) {
            $this->io->write('> Gherphalizer: No php files have been created.', true);

            return;
        }

        foreach ($processedFiles as $fileName => $filePath) {
            $this->io->write("> Gherphalizer: Processing $filePath.", true);
        }

        $count = count($processedFiles);
        $this->io->write("> Gherphalizer: Generated $count PHP files.", true);
    }
}
