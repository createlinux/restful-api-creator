<?php

namespace Createlinux\RestfulApiCreator;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Restful
{
    private string $resourceName;
    private string $pluralResourceName;
    private string $prefix;
    private string $version;
    private Collection $apis;
    private Collection $supportMethods;

    public function __construct(string $resourceName, string $prefix = 'api', string $version = 'v1')
    {
        //TODO 把create改成创建新对象

        $this->resourceName = get_standard_object_name($resourceName);
        $this->pluralResourceName = $this->toSnakePlural();
        $this->prefix = $prefix;
        $this->version = $version;
        $this->apis = new Collection();
        $this->supportMethods = new Collection();
    }

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    protected function createApi(string $path)
    {
        $apiLines = [];
        if ($this->getPrefix()) {
            $apiLines[] = $this->getPrefix();
        }
        if ($this->getVersion()) {
            $apiLines[] = $this->getVersion();
        }
        return implode("/", $apiLines) . "/" . $path;
    }

    protected function toSnakePlural()
    {
        return Str::plural(Str::snake($this->resourceName, "_"));
    }

    public function getPostApiPath()
    {
        return $this->createApi($this->getPluralResourceName());
    }

    public function getDeletePath(string $identify = 'id')
    {
        return $this->createApi($this->getPluralResourceName() . "/{{$identify}}");
    }

    public function getIndexApiPath(array $queries = [])
    {
        $query = $queries ? "?" . http_build_query($queries) : "";
        return $this->createApi($this->getPluralResourceName() . $query);
    }

    public function getSingleApiPath(string $identify = 'id')
    {
        return $this->createApi($this->getPluralResourceName() . "/{{$identify}}");
    }


    public function getUpdateApiPath(string $identify = 'id')
    {
        return $this->createApi($this->getPluralResourceName() . "/{{$identify}}");
    }

    public function getPatchApiPath(string $identify = 'id')
    {
        return $this->createApi($this->getPluralResourceName() . "/{{$identify}}");
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getPluralResourceName(): string
    {
        return $this->pluralResourceName;
    }

    public function getApis(): Collection
    {
        return $this->apis;
    }

    public function getSupportMethods(): Collection
    {
        return $this->supportMethods;
    }
}