<?php

namespace Jumilla\Generators\Laravel;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Jumilla\Generators\FileGenerator;

abstract class GeneratorCommand extends Command
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type;

    /**
     * The stub directory path.
     *
     * @var string
     */
    protected $stub_directory;

    /**
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->stub_directory = __DIR__.'/stubs';
    }

    /**
     * Get the stub directory path.
     *
     * @return string
     */
    public function getStubDirectory()
    {
        return $this->stub_directory;
    }

    /**
     * Set the stub directory path.
     *
     * @param string $path
     */
    public function setStubDirectory($path)
    {
        $this->stub_directory = $path;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $generator = FileGenerator::make($this->getRootDirectory(), $this->getStubDirectory());

        if ($this->generate($generator) === false) {
            return false;
        }

        $this->info($this->type.' created successfully.');
    }

    /**
     * Generate files.
     *
     * @param Jumilla\Generators\FileGenerator $generator
     *
     * @return string
     */
    abstract protected function generate(FileGenerator $generator);

    /**
     * Parse the name and format according to the default namespace.
     *
     * @param string $name
     *
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

        return $this->filterFullQualifyClassName($name, function ($part) {
            return ucfirst($part);
        });
    }

    /**
     * Filter FQCN.
     *
     * @param string   $fqcn
     * @param callable $callback
     *
     * @return string
     */
    protected function filterFullQualifyClassName($fqcn, callable $callback)
    {
        $parts = explode('\\', $fqcn);

        foreach ($parts as &$part) {
            $part = call_user_func($callback, $part);
        }

        return implode('\\', $parts);
    }

    /**
     * Split FQCN to namespace & classname.
     *
     * @param string $fqcn
     *
     * @return string
     */
    protected function splitFullQualifyClassName($fqcn)
    {
        $parts = explode('\\', $fqcn);
        $classname = array_pop($parts);
        return [implode('\\', $parts), $classname];
    }

    /**
     * Get the root namespace.
     *
     * @return $string
     */
    protected function getRootNamespace()
    {
        return trim($this->laravel->getNamespace(), '\\');
    }

    /**
     * Get the default namespace for the class.
     *
     * @return $string
     */
    protected function getDefaultNamespace()
    {
        return $this->getRootNamespace();
    }

    /**
     * Get the directory path for root namespace.
     *
     * @return string
     */
    protected function getRootDirectory()
    {
        return $this->laravel['path'];
    }

    /**
     * Get relative path for FQCN.
     *
     * @param string $fqcn
     *
     * @return string
     */
    protected function getRelativePath($fqcn)
    {
        if (strpos($fqcn, $this->getRootNamespace().'\\') === 0) {
            $relative = substr($fqcn, strlen($this->getRootNamespace()) + 1);
        }
        else {
            $relative = $fqcn;
        }

        return str_replace('\\', '/', $relative);
    }

}
