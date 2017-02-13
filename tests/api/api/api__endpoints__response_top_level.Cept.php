<?php

use Codeception\Util\HttpCode;
use App\Models\User;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// before
//
///////////////////////////////////////////////////////

$I->comment("given 10 users");
$users = factory(User::class, 10)->create();

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * various endpoints
// * top level response structure
//
///////////////////////////////////////////////////////

$I->comment("when we make any request");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json'); // this isn't required, but something, I think the Laravel5 Codeception module adds Accept headers, so for tests we need to be explicit.
$I->sendGET('/api');
// TODO: test other methods & endpoints

// ----------------------------------------------------
// 1) jsonapi.version
//
// Specs:
// "A document MAY contain ...
// jsonapi: an object describing the server’s
// implementation."
//
// ----------------------------------------------------

$I->expect("should return jsonapi & version");
$I->seeResponseJsonPath('$.jsonapi');
$I->seeResponseJsonPathSame('$.jsonapi.version', "1.0");

// ----------------------------------------------------
// 2) links
//
// Specs:
// "A document MAY contain ... links: a links object
// related to the primary data."
//
// ----------------------------------------------------

$I->expect("should return links");
$I->seeResponseJsonPath('$.links');

// ----------------------------------------------------
// 3) links.self
//
// Specs:
// "The top-level links object MAY contain ...
// self: the link that generated the current response
// document."
//
// ----------------------------------------------------

$I->expect("should return self link");
$I->seeResponseJsonPathRegex('$.links.self', '/^http\:\/\/[^\/]+\/api/');

// ----------------------------------------------------
// 4) links.related
//
// Specs:
// "The top-level links object MAY contain ...
// related: a related resource link when the primary
// data represents a resource relationship."
//
// ----------------------------------------------------

// TODO: test
//$I->comment("when we make any relationships request");
//$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
//$I->haveHttpHeader('Accept', 'application/vnd.api+json'); // this isn't required, but something, I think the Laravel5 Codeception module adds Accept headers, so for tests we need to be explicit.
//$I->sendGET('/api/users/1/relationships/tasks');
//
//$I->expect("should return related link");
//$I->seeResponseJsonPathRegex('$.links.related', '/^http\:\/\/[^\/]+\/api\/users\/1\/projects/');

// ----------------------------------------------------
// 5) data
//
// Specs:
// "Primary data MUST be either:
//
// * a single resource object,
//   a single resource identifier object,
//   or null, for requests that target single resources
//
// * an array of resource objects,
//   an array of resource identifier objects,
//   or an empty array ([]), for requests that target
//   resource collections"
//
// ----------------------------------------------------

$I->comment("when we make a request that results in a single entity (view, store, update)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET('/api/users/1');
// TODO: test other methods & endpoints

$I->expect("primary data should be an object");
//$I->seeResponseJsonPathType('$.data', 'object:!empty'); // TODO: how can we test this ?

// ----------------------------------------------------

$I->comment("when we make a request that results in multiple entities (index)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET('/api/users');
// TODO: test other endpoints

$I->expect("primary data should be an array");
$I->seeResponseJsonPathType('$.data', 'array:!empty');

// ----------------------------------------------------
