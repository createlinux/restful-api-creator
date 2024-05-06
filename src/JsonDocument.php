<?php

namespace Createlinux\RestfulApiCreator;

class JsonDocument
{


    private Restful $restful;

    public function __construct(Restful $restful)
    {

        $this->restful = $restful;
    }

    public function getRestful(): Restful
    {
        return $this->restful;
    }

    //TODO 生成json文档
    public function getAll()
    {
    }
}