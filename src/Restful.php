<?php

namespace Createlinux\RestfulApiCreator;

use Illuminate\Support\Collection;

class Restful
{
    /**
     * @var Collection|array<Resource>
     */
    protected Collection $resources;

    public function __construct()
    {
        $this->resources = new Collection();
    }

    /**
     *
     * 创建资源
     * @param string $resourceName
     * @param string $resourceLabel
     * @param string $identify
     * @param string $prefix
     * @param string $version
     * @return Resource
     */
    public function createResource(string $resourceName, string $resourceLabel, string $identify = 'id', string $prefix = 'api', string $version = 'v1') : Resource
    {
        $args = func_get_args();
        $resource = new Resource(...$args);
        $this->getResources()->put($resourceName, $resource);
        return $resource;
    }

    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function toArray()
    {
        $result = [];
        /** @var Resource $resource */
        foreach ($this->getResources() as $resource){
            $result[] = $resource->toArray();
        }
        return $result;
    }
}