<?php

namespace EdisonLabs\Gerphalizer;

use Behat\Gherkin\Node\FeatureNode;
use Jawira\CaseConverter\Convert;
use Nette\PhpGenerator\ClassType;
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
        $title = new Convert($feature->getTitle());
        $class = new ClassType($title->toPascal());

        return $class;
    }
}
