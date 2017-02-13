<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * update resource
// * response codes
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) resource not found -> 404 Not Found
//
// Specs:
// "A server MUST respond with 404 Not Found when
// processing a request to fetch a single resource that
// does not exist, except when the request warrants a
// 200 OK response with null as the primary data."
//
// ----------------------------------------------------

// TODO: test