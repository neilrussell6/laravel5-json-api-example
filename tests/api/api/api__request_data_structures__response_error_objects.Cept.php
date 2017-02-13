<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * request data structures
// * response error objects
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) Error: 422 UNPROCESSABLE_ENTITY
// ----------------------------------------------------

$I->comment("when we make a request that results in an 'Unprocessable Entity' error (no data or type provided)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPOST('/api/tasks', []);
// TODO: test POST with no type

$I->expect("should return an array of errors");
$I->seeResponseJsonPathType('$.errors', 'array:!empty');

$I->expect("should return a single error object in errors array");
$errors = $I->grabResponseJsonPath('$.errors[*]');
$I->assertSame(count($errors), 1);

$I->expect("error object should contain a status, title and detail member");
$I->seeResponseJsonPathSame('$.errors[0].status', HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseJsonPathType('$.errors[0].title', 'string:!empty');
$I->seeResponseJsonPathType('$.errors[0].detail', 'string:!empty');
