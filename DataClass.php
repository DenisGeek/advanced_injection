<?php

namespace AdvancedInjection;

class DataClass
{
    protected $data;

    public function __construct()
    {
        $this->data = 'secret';
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function load(): array
    {
        return [1, 2, 3];
    }
}
