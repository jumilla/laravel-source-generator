<?php

namespace LaravelPlus\Generators;

use Jumilla\Generators\FileGenerator;

abstract class OneFileGeneratorCommand extends GeneratorCommand
{
    protected function generate(FileGenerator $generator, $classname)
    {
        $path = $this->filterPath($this->getRelativePath($classname));

        if ($generator->exists($path)) {
            $this->error($this->type.' already exists!');

            return false;
        }

        return $this->generateFile($generator, $path);
    }

    protected function filterPath($path)
    {
        $parts = explode('/', $path);

        foreach ($parts as &$part) {
            $part = ucfirst(came_case($part));
        }

        return implode('/', $path);
    }

    abstract protected function generateFile(FileGenerator $generator, $path);
}
