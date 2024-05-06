<?php

namespace Createlinux\RestfulApiCreator;

class OptionalValue
{
    private string $value;
    private string $description;

    public function __construct(string $value, string $description)
    {

        $this->value = $value;
        $this->description = $description;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}