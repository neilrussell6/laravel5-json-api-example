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
// 1) invalid method -> 405 Method Not Allowed
// ----------------------------------------------------

$I->comment("when we make a request that results in an 'Method Not Allowed' error (delete user)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendDELETE('/api/users');
// TODO: test other methods & endpoints

$I->expect("should return 405 HTTP code");
$I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED);
