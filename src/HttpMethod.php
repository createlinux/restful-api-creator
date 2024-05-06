<?php

namespace Createlinux\RestfulApiCreator;

use Illuminate\Support\Collection;

class HttpMethod
{

    protected string $path = '';
    private string $label = '';
    private string $httpMethod;
    private string $methodName;
    protected Collection $queries;
    protected Collection $bodyItems;

    public function __construct(string $httpMethod, string $methodName, string $label)
    {

        $this->httpMethod = $httpMethod;
        $this->queries = new Collection();
        $this->bodyItems = new Collection();
        $this->methodName = $methodName;
        $this->label = $label;
    }


    public function getPath(): string
    {
        return $this->path;
    }

    public function getApi()
    {
        $queries = [];
        foreach ($this->getQueries() as $field => $query) {
            $queries[$query->getName()] = $query->getDefaultValue();
        }
        return $this->getPath() . "?" . http_build_query($queries);
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function addQuery(string $label, string $name, DataType $dataType, string $defaultValue = ''): HttpMethodQuery
    {
        $query = new HttpMethodQuery($label, $name, $dataType, $defaultValue);
        $this->queries->put($name, $query);
        return $query;
    }

    public function getQueries(): Collection
    {
        return $this->queries;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function addBodyItem(string $name, string $label, DataType $dataType, string $defaultValue = '')
    {
        $bodyItem = new HttpMethodQuery($label, $name, $dataType, "");
        $this->getBodyItems()->put($name, $bodyItem);
        return $bodyItem;
    }

    public function getDocArray()
    {
        $queryArray = [];
        foreach ($this->getQueries() as $query) {
            $optionalValues = [];

            foreach ($query->getOptionalValues() as $optionalValue) {
                $optionalValues[] = [
                    'value' => $optionalValue->getValue(),
                    'description' => $optionalValue->getDescription()
                ];
            }
            $queryArray[] = [
                'name' => $query->getName(),
                'defaultValue' => $query->getDefaultValue(),
                'dataType' => $query->getDataType(),
                'description' => $query->getDescription(),
                'optionalValues' => $optionalValues
            ];
        }

        $bodyItems = [];
        foreach ($this->getBodyItems() as $bodyItem) {
            $optionalValues2 = [];
            foreach ($bodyItem->getOptionalValues() as $optionalValue2) {
                $optionalValues2[] = [
                    'value' => $optionalValue2->getValue(),
                    'description' => $optionalValue2->getDescription()
                ];
            }
            $bodyItems[] = [
                'name' => $bodyItem->getName(),
                'defaultValue' => $bodyItem->getDefaultValue(),
                'dataType' => $bodyItem->getDataType(),
                'description' => $bodyItem->getDescription(),
                'optionalValues' => $optionalValues2
            ];
        }
        return [
            'httpMethod' => $this->getHttpMethod(),
            'methodName' => $this->getMethodName(),
            'label' => $this->getLabel(),
            'path' => $this->getPath(),
            'queries' => $queryArray,
            'body' => $bodyItems
        ];
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getBodyItems(): Collection
    {
        return $this->bodyItems;
    }
}