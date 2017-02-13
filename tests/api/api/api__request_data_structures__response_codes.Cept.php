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
// ----------------------------------------------------

$I->comment("when we make a request that requires data (store, update) but we don't provide it");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPOST("/api/tasks", []);

$I->expect("should return 422 HTTP code");
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
