<?php

use App\Models\User;
use App\Utils\JsonApiUtils;

$I = new FunctionalTester($scenario);

///////////////////////////////////////////////////////
//
// Test: JsonApiUtils::makeResourceObject
//
///////////////////////////////////////////////////////

$user_model = new User();
$user_model->default_includes = ['projects'];

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

$result = JsonApiUtils::makeResourceObject($data, $user_model, $link_self);

//-----------------------------------------------------

$I->expect("should return id (as string), type, attributes & links as top level members");
$I->seeJsonPathType($result, '$.id', 'string:!empty');
$I->seeJsonPathType($result, '$.type', 'string:!empty');
$I->seeJsonPathType($result, '$.attributes', 'array:!empty');
$I->seeJsonPathType($result, '$.links', 'array:!empty');

$I->expect("should correctly set id & type");
$I->seeJsonPathSame($result, '$.id', '123');
$I->seeJsonPathSame($result, '$.type', 'users');

$I->expect("attributes member should contain all provided data except id");
$I->seeNotJSONPath($result, '$.attributes.id');
$I->seeJsonPathSame($result, '$.attributes.name', "AAA");
$I->seeJsonPathSame($result, '$.attributes.email', "aaa@bbb.ccc");

$I->expect("links member should contain provided self link");
$I->seeJsonPathSame($result, '$.links.self', $link_self);

$I->expect("should include relationships array by default, if default includes are set on the model");
$I->seeJsonPathType($result, '$.relationships', 'array:!empty');

//-----------------------------------------------------
// exclude relationships
//-----------------------------------------------------

$I->comment("given relationships are marked as excluded");

$data = [
    'id' => 123,
    'name' => "AAA",
    'email' => "aaa@bbb.ccc",
];
$link_self = "http://aaa.bbb/ccc/1";
$include_relationships = false;

$result = JsonApiUtils::makeResourceObject($data, $user_model, $link_self, $include_relationships);

//-----------------------------------------------------

$I->expect("should include relationships array by default, if default includes are set on the model");
$I->seeNotJSONPath($result, '$.relationships');

//-----------------------------------------------------
// not type or id
//-----------------------------------------------------

$I->comment("given all required values");

$data = [
    'id' => 123,
    'type' => "something",
    'name' => "AAA",
    'email' => "aaa@bbb.ccc",
    'task_id' => 234,
    'project_id' => 345,
    'parent_record_id' => 456,
];
$link_self = "http://aaa.bbb/ccc/1";

$result = JsonApiUtils::makeResourceObject($data, $user_model, $link_self);

//-----------------------------------------------------

$I->expect("should not include fields name id or type in attributes");
$I->seeNotJSONPath($result, '$.attributes.id');
$I->seeNotJSONPath($result, '$.attributes.type');

$I->expect("should not include any foreign keys in attributes");
$I->seeNotJSONPath($result, '$.attributes.task_id');
$I->seeNotJSONPath($result, '$.attributes.project_id');
$I->seeNotJSONPath($result, '$.attributes.parent_record_id');

