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

            $return = "[\n";
            foreach ($scenario->getSteps() as $step) {
                $return .= "\"" . $step->getText() . "\",\n";
            }

            $scenarioTitle = new Convert('Scenario ' . $scenario->getTitle());
            $class->addMethod($scenarioTitle->toCamel())
                ->setReturnType(Type::ARRAY)
                ->setBody($return);
        }

        return $class;
    }
}
