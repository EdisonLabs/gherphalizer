<?php

namespace EdisonLabs\Gherphalizer;

use Behat\Gherkin\Node\FeatureNode;
use Jawira\CaseConverter\Convert;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Type;

/**
 * Main class for gherphalizer.
 */
class Serializer
{

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
}
