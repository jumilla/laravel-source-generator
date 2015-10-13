<?php

class GeneratorComandTests extends TestCase
{
    public function test_example1()
    {
        $command = new GeneratorCommandStub();

        Assert::notNull($command);
    }

    public function test_example2()
    {
        $command = new OneFileGeneratorCommandStub();

        Assert::notNull($command);
    }
}
