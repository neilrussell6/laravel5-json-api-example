<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * request methods
// * response codes
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) Error: 405 Method Not Allowed
// ----------------------------------------------------

$I->comment("when we make a request that results in an 'Method Not Allowed' error (delete user)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendDELETE("/api/users");
// TODO: test other methods & endpoints

$I->expect("should return an array of errors");
$I->seeResponseJsonPathType('$.errors', 'array:!empty');

$I->expect("should return a single error object in errors array");
$errors = $I->grabResponseJsonPath('$.errors[*]');
$I->assertSame(count($errors), 1);

$I->expect("error object should contain a status, title and detail member");
$I->seeResponseJsonPathSame('$.errors[0].status', HttpCode::METHOD_NOT_ALLOWED);
$I->seeResponseJsonPathType('$.errors[0].title', 'string:!empty');
$I->seeResponseJsonPathType('$.errors[0].detail', 'string:!empty');
