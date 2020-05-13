<?php

namespace EdisonLabs\Gherphalizer;

use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser;
use Jawira\CaseConverter\Convert;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\Type;
use Symfony\Component\Finder\Finder;

/**
 * Main class for gherphalizer.
 */
class Serializer
{
    /**
     * The directory where the serialized files will be placed.
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
    public $sourcePaths;

    /**
     * Serializer constructor.
     *
     * @param array  $files
     *   The files patterns.
     * @param array  $locations
     *   The source paths.
     * @param string $outputDir
     *   Path where the serialized files will be saved.
     */
    public function __construct(array $files, array $locations, $outputDir)
    {
        $this->fileNamePatterns = $files;

        $this->sourcePaths = array();
        foreach ($locations as $path) {
            $this->sourcePaths[] = realpath($path);
        }

        $this->outputDir = $outputDir;
    }

    /**
     * Serialize a feature file into a PHP class.
     *
     * @param \Behat\Gherkin\Node\FeatureNode $feature
     *
     * @return \Nette\PhpGenerator\PhpFile
     *
     * @throws \Exception
     */
    public function serialize(FeatureNode $feature)
    {
        $file = new PhpFile();
        $featureTitle = new Convert('Feature '.$feature->getTitle());
        $namespace = $file->addNamespace('GherphalizerScenarios');
        $class = $namespace->addClass($featureTitle->toPascal());
        $class->addComment('Scenarios for feature '.$feature->getTitle().'.');

        foreach ($feature->getScenarios() as $scenario) {
            $steps = $scenario->getSteps();

            $count = count($steps);
            $i = 0;

            $array = '';
            foreach ($steps as $step) {
                $array .= "\t\"".$step->getKeyword().' '.$step->getText().'",'."\n";
            }

            $scenarioTitle = new Convert('Scenario '.$scenario->getTitle());
            $class->addMethod($scenarioTitle->toCamel())
                ->setReturnType(Type::ARRAY)
                ->setBody("return [\n".$array.'];')
                ->addComment($scenarioTitle->toSentence().'.')
                ->addComment("\n@return array")
                ->addComment("  The scenario's steps.");
        }

        return $file;
    }

    /**
     * Creates the Php files.
     *
     * @return array
     *   Returns the processed files.
     * @throws \Exception
     */
    public function createPhpFiles()
    {
        // Check if the output directory exists and try to create it if it doesn't.
        $this->prepareOutputDir();

        $gherkinFeatureFilePaths = $this->getGherkinFeatureFiles();
        if (empty($gherkinFeatureFilePaths)) {
            // No valid Gherkin feature files were found.
            return [];
        }

        $keywords = new ArrayKeywords([
            'en' => [
                'feature'          => 'Feature',
                'background'       => 'Background',
                'scenario'         => 'Scenario',
                'scenario_outline' => 'Scenario Outline',
                'examples'         => 'Examples',
                'given'            => 'Given',
                'when'             => 'When',
                'then'             => 'Then',
                'and'              => 'And',
                'but'              => 'But',
            ],
        ]);
        $lexer = new Lexer($keywords);
        $parser = new Parser($lexer);
        $printer = new PsrPrinter();

        foreach ($gherkinFeatureFilePaths as $fileName => $filePaths) {
            foreach ($filePaths as $file => $filepath) {
                $feature = $parser->parse(file_get_contents($filepath));
                $featureTitle = new Convert($feature->getTitle());
                $outputFileName = $featureTitle->toPascal().'.php';
                $file = $this->serialize($feature);

                // Save file.
                file_put_contents($this->outputDir.'/'.$outputFileName, $printer->printFile($file));
            }
        }

        return $gherkinFeatureFilePaths;
    }

    /**
     * Gets all gherkin feature files matching fileNamePatterns inside the sourcePaths.
     *
     * @return array
     *   The absolute paths to the valid gherkin feature files.
     */
    public function getGherkinFeatureFiles()
    {
        $featureFiles = array();

        $finder = new Finder();
        $finder->files();
        $finder->followLinks();
        $finder->in($this->sourcePaths);
        $finder->sortByName();

        foreach ($this->fileNamePatterns as $filePattern) {
            $finder->name($filePattern.'.feature');
        }

        if ($finder->count() < 1) {
            return array();
        }

        foreach ($finder as $file) {
            $fileName = str_replace('.feature', '', $file->getFilename());

            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            $featureFiles[$fileName][] = $file->getRealPath();
        }

        return $featureFiles;
    }

    /**
     * Creates the output directory if doesn't exist.
     */
    public function prepareOutputDir()
    {
        // Check if the output directory exists and try to create it if it doesn't.
        if (!is_dir($this->outputDir) && !mkdir($this->outputDir, 0700)) {
            throw new \RuntimeException(sprintf('Output directory does not exist and it was not able to be created: %s.', $this->outputDir));
        }
    }
}
