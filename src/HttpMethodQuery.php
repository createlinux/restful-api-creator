<?php

namespace Createlinux\RestfulApiCreator;

use Createlinux\RestfulApiCreator\enums\DataType;
use Illuminate\Support\Collection;

class HttpMethodQuery
{
    private string $label;
    private string $name;
    private string $dataType;
    private string $defaultValue;
    private Collection $optionalValues;
    private string $description = '';
    private int $isRequired = 0;

    /**
     * @param string $label 名称
     * @param string $name 唯一name
     * @param double $dataType 数据类型
     */
    public function __construct(string $label, string $name, DataType $dataType, string $defaultValue = '')
    {

        $this->label = $label;
        $this->name = $name;
        $this->dataType = $dataType->name;
        $this->defaultValue = $defaultValue;
        $this->optionalValues = new Collection();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    public function addOptionalValue(string $value, string $description)
    {
        $optionalValue = new OptionalValue($value, $description);
        $this->getOptionalValues()->put($value, $optionalValue);
        return $this;
    }

    public function getOptionalValues(): Collection
    {
        return $this->optionalValues;
    }

    public function setDescription(string $description): HttpMethodQuery
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setIsRequired(int $isRequired): HttpMethodQuery
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function getIsRequired(): int
    {
        return $this->isRequired;
    }


}