<?php

namespace Createlinux\RestfulApiCreator;

use Createlinux\RestfulApiCreator\enums\RequestMethod;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Resource
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

        $this->resourceName = to_standard_object_name($resourceName);
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

    /**
     *
     * 转为蛇形复数
     * @param string $string 默认资源名
     * @return string
     */
    protected function toSnakePlural(string $string = '')
    {
        if(!$string){
            $string = $this->getResourceName();
        }
        return to_snake_plural($string);
    }

    public function createStore()
    {
        $post = new HttpMethod($this->getResourceName(), RequestMethod::post, 'store', "创建");
        $post->setPath($this->createApiPath($this->toSnakePlural()));
        $this->supportMethods->put('store', $post);
        return $post;
    }

    public function createShow()
    {
        $get = new HttpMethod($this->getResourceName(), RequestMethod::get, 'show', "详情");
        $get->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");

        $this->supportMethods->put('show', $get);
        return $get;
    }

    public function createDestroy()
    {
        $method = new HttpMethod($this->getResourceName(), RequestMethod::delete, 'destroy', "删除");
        $method->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");
        $this->supportMethods->put('destroy', $method);
        return $method;
    }

    public function createUpdate()
    {
        $method = new HttpMethod($this->getResourceName(), RequestMethod::put, 'update', "更新");
        $method->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");
        $this->supportMethods->put('update', $method);
        return $method;
    }

    public function createPatch()
    {
        $method = new HttpMethod($this->getResourceName(), RequestMethod::patch, 'patch', "补丁");
        $method->setPath($this->createApiPath($this->toSnakePlural()) . "/{{$this->getIdentify()}}");
        $this->supportMethods->put('patch', $method);
        return $method;
    }

    public function createIndex()
    {
        $method = new HttpMethod($this->getResourceName(), RequestMethod::get, 'index', "索引");
        $method->setPath($this->createApiPath($this->toSnakePlural()));
        $this->supportMethods->put('index', $method);
        return $method;
    }

    public function createCustom(RequestMethod $httpMethod, string $methodName, string $methodLabel)
    {
        $method = new HttpMethod($this->getResourceName(), $httpMethod, $methodName, $methodLabel);
        $methodPath = to_snake_plural(to_standard_object_name($methodName));
        $method->setPath($this->createApiPath($this->toSnakePlural() . "/" . $methodPath));
        $this->supportMethods->put($methodName, $method);
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
            'methods' => array_column($support_methods, 'methodName'),
            'support_methods' => $support_methods
        ];
    }

    public function getResourceLabel(): string
    {
        return $this->resourceLabel;
    }
}