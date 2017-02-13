<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * various endpoints
// * response error objects
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) Error: Not found
// ----------------------------------------------------

$I->comment("when we make a request that results in an 404 error (unknown endpoint)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/unknown");
// TODO: test other unknown endpoints

$I->expect("should return an array of errors");
$I->seeResponseJsonPathType('$.errors', 'array:!empty');

$I->expect("should return a single error object in errors array");
$errors = $I->grabResponseJsonPath('$.errors[*]');
$I->assertSame(count($errors), 1);

$I->expect("error object should contain a status, title and detail member");
$I->seeResponseJsonPathSame('$.errors[0].status', HttpCode::NOT_FOUND);
$I->seeResponseJsonPathType('$.errors[0].title', 'string:!empty');
$I->seeResponseJsonPathType('$.errors[0].detail', 'string:!empty');
