<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * request headers
// * response codes
// 
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1)
//
// Request headers:
// * JSON API Content-Type
// * no Accept
//
// Response code:
// 200 OK
//
// Specs:
// "Clients MUST send all JSON API data in request
// documents with the header Content-Type:
// application/vnd.api+json without any media type
// parameters."
//
// ----------------------------------------------------

$I->comment("when we make a GET request to the api an include the JSON API Content-Type header, and JSON API media type in the Accept header");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET('/api');

$I->expect("should return 200 HTTP code");
$I->seeResponseCodeIs(HttpCode::OK);

// ----------------------------------------------------
// 2)
//
// Request headers:
// * JSON API Content-Type
// * only JSON API Accept
//
// Response code:
// 200 OK
//
// Specs:
// "Clients that include the JSON API media type in
// their Accept header MUST specify the media type
// there at least once without any media type
// parameters."
//
// ----------------------------------------------------

$I->comment("when we make a GET request to the api an include the JSON API Content-Type header, and JSON API media type in the Accept header");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET('/api');

$I->expect("should return 200 HTTP code");
$I->seeResponseCodeIs(HttpCode::OK);

// ----------------------------------------------------
// 3)
//
// Request headers:
// * JSON API Content-Type
// * JSON API Accept amongst other media types
//
// Response code:
// 200 OK
//
// ----------------------------------------------------

$I->comment("when we make a GET request to the api an include the JSON API Content-Type header, and JSON API media type amongst others in the Accept header");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'text/plain, application/vnd.api+json, application/json');
$I->sendGET('/api');
// TODO: test other methods & endpoints

$I->expect("should return 200 HTTP code");
$I->seeResponseCodeIs(HttpCode::OK);

// ----------------------------------------------------
// 4)
//
// Request headers:
// * JSON API Content-Type but with media type params
//
// Response code:
// 415 UNSUPPORTED_MEDIA_TYPE
//
// Specs:
// "Servers MUST respond with a 415 Unsupported Media
// Type status code if a request specifies the header
// Content-Type: application/vnd.api+json with any
// media type parameters."
//
// ----------------------------------------------------

$I->comment("when we make a GET request to the api an include the JSON API Content-Type header but with media type params");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json; version=1');
$I->sendGET("/api");
// TODO: test other methods & endpoints

$I->expect("should return 415 HTTP code");
$I->seeResponseCodeIs(HttpCode::UNSUPPORTED_MEDIA_TYPE);

// ----------------------------------------------------
// 5)
//
// Request headers:
// * JSON API Content-Type
// * JSON API Accept but with media type params
//
// Response code:
// 406 NOT_ACCEPTABLE
//
// Specs:
// "Servers MUST respond with a 406 Not Acceptable
// status code if a request’s Accept header contains
// the JSON API media type and all instances of that
// media type are modified with media type parameters."
//
// ----------------------------------------------------

$I->comment("when we make a GET request to the api an include the JSON API Content-Type header, and also include the JSON API media type in an Accept header, but with media type params");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json; version=1');
$I->sendGET("/api");
// TODO: test other methods & endpoints

$I->expect("should return 406 HTTP code");
$I->seeResponseCodeIs(HttpCode::NOT_ACCEPTABLE);
