<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * endpoints
// * response error objects
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) Error: Not found
// ----------------------------------------------------

//$I->comment("when we make a request that results in an 404 error (unknown endpoint)");
//$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
//$I->sendGET("/unknown");

// TODO: test error object
