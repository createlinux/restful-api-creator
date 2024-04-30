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

    public function testPostApi()
    {
        $restfulApi = new Restful("DoctorUser");
        $this->assertEquals("api/v1/doctor_users", $restfulApi->getPostApiPath());
    }

    public function testDeleteApi()
    {
        $restfulApi = new Restful("DoctorUser");
        $this->assertEquals("api/v1/doctor_users/{id}", $restfulApi->getDeletePath());
        $this->assertEquals("api/v1/doctor_users/{doctorId}", $restfulApi->getDeletePath("doctorId"));
    }

    public function testSingleApi()
    {
        $restfulApi = new Restful("DoctorUser");
        $this->assertEquals("api/v1/doctor_users/{id}", $restfulApi->getSingleApiPath());
        $this->assertEquals("api/v1/doctor_users/{doctorId}", $restfulApi->getSingleApiPath("doctorId"));
    }

    public function testUpdatePathApi()
    {
        $restfulApi = new Restful("DoctorUser");
        $this->assertEquals("api/v1/doctor_users/{id}", $restfulApi->getUpdateApiPath());
        $this->assertEquals("api/v1/doctor_users/{doctorId}", $restfulApi->getUpdateApiPath("doctorId"));

        $this->assertEquals("api/v1/doctor_users/{id}", $restfulApi->getPatchApiPath());
        $this->assertEquals("api/v1/doctor_users/{doctorId}", $restfulApi->getPatchApiPath("doctorId"));
    }

    public function testIndexApi()
    {
        $restfulApi = new Restful("DoctorUser");
        $this->assertEquals("api/v1/doctor_users", $restfulApi->getIndexApiPath());
        $this->assertEquals("api/v1/doctor_users?userId=1", $restfulApi->getIndexApiPath([
            'userId' => 1
        ]));
        $this->assertEquals("api/v1/doctor_users?userId=1&clientId=123123", $restfulApi->getIndexApiPath([
            'userId' => 1,
            'clientId' => 123123
        ]));
    }
}