<?php

use App\Utils\JsonApiUtils;
use Codeception\Util\HttpCode;

$I = new FunctionalTester($scenario);

///////////////////////////////////////////////////////
//
// Test: JsonApiUtils::makeResourceObject
//
///////////////////////////////////////////////////////

$I->wantTo("make a resource object for JSON API response");

//-----------------------------------------------------
// all required values
//-----------------------------------------------------

$I->comment("given all required values");

$data = [
    'id' => 123,
    'name' => "AAA",
    'email' => "aaa@bbb.ccc",
];
$link_self = "http://aaa.bbb/ccc/1";

$result = JsonApiUtils::makeResourceObject($data, 'users', $link_self);

//-----------------------------------------------------

$I->expect("should return id (as string), type, attributes & links as top level members");

$I->seeJsonPathType($result, '$.id', 'string:!empty');
$I->seeJsonPathType($result, '$.type', 'string:!empty');
$I->seeJsonPathType($result, '$.attributes', 'array:!empty');
$I->seeJsonPathType($result, '$.links', 'array:!empty');

$I->expect("should correctly set id & type");

$I->seeJsonPathSame($result, '$.id', '123');
$I->seeJsonPathSame($result, '$.type', 'users');

$I->expect("attributes member should contain all provided data");

$I->seeJsonPathSame($result, '$.attributes.id', 123);
$I->seeJsonPathSame($result, '$.attributes.name', "AAA");
$I->seeJsonPathSame($result, '$.attributes.email', "aaa@bbb.ccc");

$I->expect("links member should contain provided self link");

$I->seeJsonPathSame($result, '$.links.self', $link_self);
