<?php
require_once __DIR__ . '/vendor/autoload.php';

use Createlinux\RestfulApiCreator\enums\DataType;

$restful = new \Createlinux\RestfulApiCreator\Restful();

$user = $restful->createResource('user',"用户");
$index = $user->createIndex();
$index->addQuery('搜索', 'search', DataType::string)
    ->setDescription("输入关键词搜索");

$index->addQuery('格式', 'format', DataType::string, 'list')
    ->setIsRequired(1)
    ->setDescription("根据不同参数返回不同的格式")
    ->addOptionalValue("tree", "格式化为树形格式")
    ->addOptionalValue("list", "返回列表格式");


$store = $user->createStore();
$store->addQuery("令牌", 'access_token', DataType::string)
    ->setDescription("访问令牌");

$store->addBodyItem("last_name", "姓", DataType::string);
$store->addBodyItem("first_name", "名", DataType::string);
$store->addBodyItem("status", "状态", DataType::string, "draft")
    ->addOptionalValue("draft", "草稿")
    ->addOptionalValue("publish", "发布");

$show = $user->createShow();


$user->createCustom(\Createlinux\RestfulApiCreator\enums\HttpMethodType::get,"UsersComments","自定义方法");

echo json_encode($restful->toArray());


