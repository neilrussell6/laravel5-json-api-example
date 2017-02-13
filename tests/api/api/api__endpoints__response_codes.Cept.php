<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * endpoints
// * response codes
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1)
//
// Unknown endpoint
//
// Response code:
// 404 NOT FOUND
//
// ----------------------------------------------------

$I->comment("when we make a request that results in an 404 error (unknown endpoint)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/unknown");

$I->expect("should return 404 HTTP code");
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);
