<?php

namespace Jumilla\Generators\Laravel;

use Jumilla\Generators\FileGenerator;

abstract class OneFileGeneratorCommand extends GeneratorCommand
{
    /**
     * Generate files.
     *
     * @return bool
     */
    protected function generate(FileGenerator $generator)
    {
        $fqcn = $this->convertToFullQualifyClassName($this->getNameInput());

        $path = $this->getRelativePath($fqcn).'.php';

        if ($generator->exists($path)) {
            $this->error($this->type.' already exists!');

            return false;
        }

        return $this->generateFile($generator, $path, $fqcn);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return $this->argument('name');
    }

    /**
     * Generate one file.
     *
     * @param FileGenerator $generator
     * @param string $path
     * @param string $fqcn
     *
     * @return bool
     */
    abstract protected function generateFile(FileGenerator $generator, $path, $fqcn);
}
