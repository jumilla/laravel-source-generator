<?php

namespace LaravelPlus\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Jumilla\Generators\FileGenerator;

abstract class GeneratorCommand extends Command
{
    protected $stub_directory_path;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type;

    public function getOutputDirectory()
    {
        return '.';
    }

    public function getStubDirectory()
    {
        return $this->stub_directory_path;
    }

    public function setStubDirectory($path)
    {
        $this->stub_directory_path = $path;
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
     * Get the root namespace.
     *
     * @return $string
     */
    public function getRootNamespace()
    {
        return trim($this->laravel->getNamespace(), '\\');
    }

    /**
     * Get the default namespace for the class.
     *
     * @return $string
     */
    public function getDefaultNamespace()
    {
        return $this->getRootNamespace();
    }

    public function getRelativePath($classname)
    {
        $relative = substr($classname, strlen($this->getNamespace()) + 1);

        return str_replace('\\', '/', $relative);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $generator = FileGenerator::make($this->getOutputDirectory(), $this->getStubDirectory());

        $classname = $this->convertToFullQualifyClassName($this->getNameInput());

        if ($this->generator($generator, $classname) === false) {
            return false;
        }

        $this->info($this->type.' created successfully.');
    }

    abstract protected function generate(FileGenerator $generator, $name);

    /**
     * Parse the name and format according to the default namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function convertToFullQualifyClassName($name)
    {
        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        if (Str::startsWith($name, '\\')) {
            $name = substr($name, 1);
        }
        else {
            $name = $this->getDefaultNamespace().'\\'.$name;
        }

        return $name;
    }
}
