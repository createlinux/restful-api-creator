<?php

namespace Createlinux\RestfulApiCreator;

use Createlinux\RestfulApiCreator\enums\DataType;
use Createlinux\RestfulApiCreator\enums\RequestMethod;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HttpMethod
{

    protected string $path = '';
    private string $label = '';
    private RequestMethod $httpMethod;
    private string $methodName;
    protected Collection $queries;
    protected Collection $bodyItems;
    private string $resourceName;

    public function __construct(string $resourceName, RequestMethod $requestMethod, string $methodName, string $label)
    {

        $this->httpMethod = $requestMethod;
        $this->queries = new Collection();
        $this->bodyItems = new Collection();
        $this->methodName = $this->renameMethodName($methodName);
        $this->label = $label;
        $this->resourceName = to_standard_object_name($resourceName);
    }

    protected function renameMethodName(string $methodName)
    {
        if(in_array($methodName,['show','store','index','destroy','update','patch'])){
            return $methodName;
        }
        return lcfirst(Str::plural(to_standard_object_name($methodName)));
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

    public function getHttpMethod(): RequestMethod
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
                'isRequired' => $query->getIsRequired(),
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
                'isRequired' => $bodyItem->getIsRequired(),
                'defaultValue' => $bodyItem->getDefaultValue(),
                'dataType' => $bodyItem->getDataType(),
                'description' => $bodyItem->getDescription(),
                'optionalValues' => $optionalValues2
            ];
        }
        return [
            'httpMethod' => $this->getHttpMethod()->name,
            'methodName' => $this->getMethodName(),
            'methodAlias' => "{$this->getResourceName()}.{$this->getMethodName()}",
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

    public function getResourceName(): string
    {
        return $this->resourceName;
    }
}