<?php


namespace EdisonLabs\Gherphalizer;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GherphalizerCommand.
 */
class GherphalizerCommand extends BaseCommand
{

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output, array $configParameters = [])
    {
        $configFile = $input->getOption('config');
        if ($configFile && empty($configParameters)) {
            $filePath = realpath($configFile);

            // Checks if the file is valid.
            if (!$filePath || !$configfileContent = file_get_contents($filePath)) {
                throw new \RuntimeException("Unable to load the config file $configFile");
            }

            $configParameters = json_decode($configfileContent, true);
        }

        $serializer = new Serializer($configParameters['files'], $configParameters['locations'], $configParameters['output-dir']);
        $processedFiles = $serializer->createPhpFiles();

        if (empty($processedFiles)) {
            $output->write('>gherphalizer: No php files have been created', true);

            return;
        }

        foreach ($processedFiles as $fileName => $filePath) {
            $output->write("> gherphalizer: Generating $filePath", true);
        }
        $count = count($processedFiles);
        $output->write("> gherphalizer: Generated $count PHP files", true);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('gherphalizer')
            ->setDefinition($this->createDefinition())
            ->setDescription('Serializes Gherkin feature files into PHP files.');
    }

    /**
     * {@inheritdoc}
     */
    private function createDefinition()
    {
        return new InputDefinition(array(
            new InputOption('config', null, InputOption::VALUE_OPTIONAL, 'A json file containing the plugin configuration.'),
        ));
    }
}
