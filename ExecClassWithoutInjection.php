<?php

namespace AdvancedInjection;

class ExecClassWithoutInjection
{
    protected $status;

    protected $client;

    public function __construct()
    {
        $this->client = new DataClass();

        $this->status = $this->load();

        if (empty($this->status)) {
            throw new \Exception('Unstatused');
        }
    }

    public function exec(): string
    {
        $result = $this->client->getData();

        if (empty($result)) {
            throw new \Exception('Mocked');
        }

        return $result;
    }

    protected function load(): array
    {
        return $this->client->load();
    }
}
