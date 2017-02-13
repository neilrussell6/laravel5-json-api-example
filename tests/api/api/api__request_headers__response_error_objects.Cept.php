<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
// 
// * request headers
// * response error objects
// 
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) Error: Unsupported media type
// ----------------------------------------------------

$I->comment("when we make a request that results in an 'Unsupported media type' error (Content-Type header includes media type params)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json; version=1');
$I->sendGET("/api");

$I->expect("should return an array of errors");
$I->seeResponseJsonPathType('$.errors', 'array:!empty');

$I->expect("should return a single error object in errors array");
$errors = $I->grabResponseJsonPath('$.errors[*]');
$I->assertSame(count($errors), 1);

$I->expect("error object should contain a status, title and detail member");
$I->seeResponseJsonPathSame('$.errors[0].status', HttpCode::UNSUPPORTED_MEDIA_TYPE);
$I->seeResponseJsonPathType('$.errors[0].title', 'string:!empty');
$I->seeResponseJsonPathType('$.errors[0].detail', 'string:!empty');

// ----------------------------------------------------
// 2) Error: Not Acceptable
// ----------------------------------------------------

$I->comment("when we make a request that results in an 'Not Acceptable' error (Accept header's JSON API media type includes params)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json; version=1');
$I->sendGET("/api");

$I->expect("should return an array of errors");
$I->seeResponseJsonPathType('$.errors', 'array:!empty');

$I->expect("should return a single error object in errors array");
$errors = $I->grabResponseJsonPath('$.errors[*]');
$I->assertSame(count($errors), 1);

$I->expect("error object should contain a status, title and detail member");
$I->seeResponseJsonPathSame('$.errors[0].status', HttpCode::NOT_ACCEPTABLE);
$I->seeResponseJsonPathType('$.errors[0].title', 'string:!empty');
$I->seeResponseJsonPathType('$.errors[0].detail', 'string:!empty');
