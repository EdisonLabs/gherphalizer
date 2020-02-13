<?php

namespace EdisonLabs\Gerphalizer;

use Behat\Gherkin\Node\FeatureNode;
use Jawira\CaseConverter\Convert;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Type;
use PHPUnit\Exception;

class Serializer
{

    /**
     * Constructor.
     */
    function __construct()
    {

    }

    /**
     * Serialize a feature file into a PHP class.
     *
     * @param \Behat\Gherkin\Node\FeatureNode $feature
     * @return \Nette\PhpGenerator\ClassType
     * @throws \Exception
     */
    public function serialize(FeatureNode $feature)
    {
        $featureTitle = new Convert('Feature ' . $feature->getTitle());
        $class = new ClassType($featureTitle->toPascal());

        foreach ($feature->getScenarios() as $scenario) {
            $steps = $scenario->getSteps();

            $count = count($steps);
            $i = 0;

            $array = '';
            foreach ($steps as $step) {
                $array .= '  "' . $step->getKeyword() . ' ' . $step->getText() . '"';
                if (++$i != $count) {
                    $array .= ",\n";
                }
            }

            $scenarioTitle = new Convert('Scenario ' . $scenario->getTitle());
            $class->addMethod($scenarioTitle->toCamel())
                ->setReturnType(Type::ARRAY)
                ->setBody("return [\n" . $array . "\n];");
        }

        return $class;
    }
}
