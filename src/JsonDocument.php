<?php

namespace Createlinux\RestfulApiCreator;

class JsonDocument
{


    private Resource $restful;

    public function __construct(Resource $restful)
    {

        $this->restful = $restful;
    }

    public function getRestful(): Resource
    {
        return $this->restful;
    }

    //TODO 生成json文档
    public function getAll()
    {
    }
}