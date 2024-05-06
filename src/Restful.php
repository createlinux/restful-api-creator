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
    private Collection $supportMethods;
    private string $identify;
    private string $resourceLabel;

    public function __construct(string $resourceName, string $resourceLabel, string $identify = 'id', string $prefix = 'api', string $version = 'v1')
    {
        //TODO 把create改成创建新对象

        $this->resourceName = get_standard_object_name($resourceName);
        $this->pluralResourceName = $this->toSnakePlural();
        $this->prefix = $prefix;
        $this->version = $version;
        $this->supportMethods = new Collection();
        $this->identify = $identify;
        $this->resourceLabel = $resourceLabel;
    }

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    protected function createApiPath(string $path)
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

    public function createPost()
    {
        $post = new HttpMethod('post', 'store', "创建");
        $post->setPath($this->createApiPath($this->toSnakePlural()));
        $this->supportMethods->put('store', $post);
        return $post;
    }

    public function createShow()
    {
        $get = new HttpMethod('get', 'show', "详情");
        $get->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");

        $this->supportMethods->put('show', $get);
        return $get;
    }

    public function createDestroy()
    {
        $method = new HttpMethod('delete', 'destroy', "删除");
        $method->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");
        $this->supportMethods->put('destroy', $method);
        return $method;
    }

    public function createUpdate()
    {
        $method = new HttpMethod('put', 'update', "更新");
        $method->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");
        $this->supportMethods->put('update', $method);
        return $method;
    }

    public function createPatch()
    {
        $method = new HttpMethod('patch', 'patch', "补丁");
        $method->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");
        $this->supportMethods->put('patch', $method);
        return $method;
    }

    public function createIndex()
    {
        $method = new HttpMethod('get', 'index', "索引");
        $method->setPath($this->createApiPath($this->toSnakePlural()));
        $this->supportMethods->put('index', $method);
        return $method;
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

    public function getSupportMethods(): Collection
    {
        return $this->supportMethods;
    }

    public function getIdentify(): string
    {
        return $this->identify;
    }

    public function toArray()
    {
        $support_methods = [];
        foreach ($this->getSupportMethods() as $supportMethod) {
            $support_methods[] = $supportMethod->getDocArray();
        }
        return [
            'resourceName' => $this->getResourceName(),
            'resourceLabel' => $this->getResourceLabel(),
            'support_methods' => $support_methods
        ];
    }

    public function getResourceLabel(): string
    {
        return $this->resourceLabel;
    }
}