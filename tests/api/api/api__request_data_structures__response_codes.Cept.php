<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * request data structures
// * response codes
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) No data -> 422 UNPROCESSABLE_ENTITY
//
// Specs:
// "The request MUST include a single resource object
// as primary data."
//
// ----------------------------------------------------

$I->comment("when we make a request that requires data (store, update) but we don't provide it");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPOST('/api/tasks', []);
// TODO: test other methods & endpoints

$I->expect("should return 422 HTTP code");
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

// ----------------------------------------------------
// 2) No type -> 422 UNPROCESSABLE_ENTITY
//
// Specs:
// "The resource object MUST contain at least a type
// member."
//
// ----------------------------------------------------

$I->comment("when we make a request that requires data (store, update) but we don't provide a type");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPOST('/api/tasks', [
    'data' => [
        'attributes' => [
            'name' => 'AAA'
        ]
    ]
]);
// TODO: test other methods & endpoints

$I->expect("should return 422 HTTP code");
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

// ----------------------------------------------------
// 3) Unknown type -> 422 UNPROCESSABLE_ENTITY
// ----------------------------------------------------

$I->comment("when we make a request that requires data (store, update) and provide an unknown type");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPOST('/api/tasks', [
    'data' => [
        'type' => 'not_tasks',
        'attributes' => [
            'name' => 'AAA'
        ]
    ]
]);
// TODO: test other methods & endpoints

$I->expect("should return 422 HTTP code");
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

// ----------------------------------------------------
// 4) Attribute validation failed -> 422 UNPROCESSABLE_ENTITY
// ----------------------------------------------------

$I->comment("when we make a request that requires data (store, update) and that data does not pass the entities attribute validation");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPOST('/api/users', [
    'data' => [
        'type' => 'users',
        'attributes' => [
            'name' => 'AAA',
            'email' => 'aaa@aaa.aaa',
            'password' => 'Test123!',
//            'password_confirmation' => 'Test123!'
        ]
    ]
]);
// TODO: test other methods & endpoints

$I->expect("should return 422 HTTP code");
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
