<?php

namespace Createlinux\Tests;

use Createlinux\RestfulApiCreator\Restful;
use PHPUnit\Framework\TestCase;

class RestfulTest extends TestCase
{
    public function testStandardObjectName()
    {
        $restfulApi = new Restful('DoctorsUsers');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误1");

        $restfulApi = new Restful('doctor_user');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误2");

        $restfulApi = new Restful('DoctorsUser');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误3");

        $restfulApi = new Restful('_DoctorsUser_');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误4");

        $restfulApi = new Restful('_DoctorsUser_');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误5");
    }
}