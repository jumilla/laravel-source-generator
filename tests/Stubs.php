<?php

use Jumilla\Generators\Laravel\GeneratorCommand;
use Jumilla\Generators\Laravel\OneFileGeneratorCommand;
use Jumilla\Generators\FileGenerator;

class GeneratorCommandStub extends GeneratorCommand
{
    protected $signature = 'test:generate {name}';

    protected function generate(FileGenerator $generator)
    {

    }
}

class OneFileGeneratorCommandStub extends OneFileGeneratorCommand
{
    protected $signature = 'test:onefilegenerate {name}';

    protected function generateFile(FileGenerator $generator, $path, $fqcn)
    {

    }
}
