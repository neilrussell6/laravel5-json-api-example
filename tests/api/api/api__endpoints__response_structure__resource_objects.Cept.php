<?php

use App\Models\User;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// before
//
///////////////////////////////////////////////////////

$I->comment('given 10 users');
$users = factory(User::class, 10)->create();

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * resource object response structure
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) id & type
//
// Specs:
// "A resource object MUST contain ... id."
// "A resource object MUST contain ... type."
//
// ----------------------------------------------------

$I->comment("when we make a request that results in a single entity (view, store, update)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/users/1");

$I->expect("should return type value for resource object");
$I->seeResponseJsonPathSame('$.data.type', 'users');

$I->expect("should return id for resource object as string");
$I->seeResponseJsonPathType('$.data.id', 'string:!empty');

// ----------------------------------------------------

$I->comment("when we make a request that results in multiple entities (index)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/users");

$I->expect("should return type value for each resource object");
$I->seeResponseJsonPathSame('$.data[*].type', 'users');

$I->expect("should return ids for each resource object as strings");
$I->seeResponseJsonPathType('$.data[*].id', 'string:!empty');

// ----------------------------------------------------
// 2) attributes
//
// Specs:
// "a resource object MAY contain ... attributes: an
// attributes object representing some of the
// resource’s data."
//
// ----------------------------------------------------

$I->comment("when we make a request that results in a single entity (view, store, update)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/users/1");

$I->expect("should return an attributes object, containing the entity's visible properties");
$I->seeResponseJsonPathType('$.data.attributes', 'array:!empty');
$I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');
$I->seeResponseJsonPathType('$.data.attributes.email', 'string:!empty');

// ----------------------------------------------------

$I->comment("when we make a request that results in multiple entities (index)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/users");

$I->expect("should return an attributes object for each entity, containing it's visible properties");
$I->seeResponseJsonPathType('$.data[*].attributes', 'array:!empty');
$I->seeResponseJsonPathType('$.data[*].attributes.name', 'string:!empty');
$I->seeResponseJsonPathType('$.data[*].attributes.email', 'string:!empty');

// ----------------------------------------------------
// 3) attributes (has one ids)
//
// Specs:
// "Although has-one foreign keys (e.g. author_id) are
// often stored internally alongside other information
// to be represented in a resource object, these keys
// SHOULD NOT appear as attributes."
//
// ----------------------------------------------------

// TODO: test

// ----------------------------------------------------
// 4) relationships
//
// Specs:
// "a resource object MAY contain ...
// relationships: a relationships object describing
// relationships between the resource and other JSON
// API resources."
//
// ----------------------------------------------------

// TODO: test

// ----------------------------------------------------
// 4) links
//
// Specs:
// "a resource object MAY contain ...
// links: a links object containing links related to
// the resource."
//
// ----------------------------------------------------

$I->comment("when we make a request that results in a single entity (view, store, update)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/users/1");

$I->expect("should return a links object containing only a self property");
$I->seeResponseJsonPathType('$.data.links', 'array:!empty');
$I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/users\/1$/');

// ----------------------------------------------------

$I->comment("when we make a request that results in multiple entities (index)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET("/api/users");

$I->expect("should return a links object for each entity containing only a self property");
$I->seeResponseJsonPathType('$.data[*].links', 'array:!empty');
$I->seeResponseJsonPathRegex('$.data[*].links.self', '/^http\:\/\/[^\/]+\/api\/users\/\d+$/');

// ----------------------------------------------------
// 5) meta
//
// Specs:
// "a resource object MAY contain ...
// meta: a meta object containing non-standard
// meta-information about a resource that can not be
// represented as an attribute or relationship."
//
// ----------------------------------------------------

// TODO: test
