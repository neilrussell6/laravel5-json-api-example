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

// TODO: test error object

// ----------------------------------------------------
// 2) Error: Not Acceptable
// ----------------------------------------------------

$I->comment("when we make a request that results in an 'Not Acceptable' error (Accept header's JSON API media type includes params)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json; version=1');
$I->sendGET("/api");

// TODO: test error object
