<?php

namespace Test;

class ExecClassWithInjection
{
    protected $client;

    public function __construct(DataClass $input = null)
    {
        $this->client = !is_null($input) ? $input : new DataClass();
    }

    public function exec(): string
    {
        return $this->client->getData();
    }
}