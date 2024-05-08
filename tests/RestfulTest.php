<?php

namespace Createlinux\Tests;

use Createlinux\RestfulApiCreator\Resource;
use PHPUnit\Framework\TestCase;

class RestfulTest extends TestCase
{
    public function testStandardObjectName()
    {
        $restfulApi = new Resource('DoctorsUsers');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误1");

        $restfulApi = new Resource('doctor_user');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误2");

        $restfulApi = new Resource('DoctorsUser');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误3");

        $restfulApi = new Resource('_DoctorsUser_');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误4");

        $restfulApi = new Resource('_DoctorsUser_');
        $this->assertEquals("DoctorUser", $restfulApi->getResourceName(), "对象名转换错误5");
    }
}