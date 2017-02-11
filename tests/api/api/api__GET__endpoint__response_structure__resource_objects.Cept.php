<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * top level response structure
//
///////////////////////////////////////////////////////

//// ----------------------------------------------------
//// 1) id & type
////
//// Specs:
//// "A resource object MUST contain ... id."
////
//// ----------------------------------------------------
//
//$I->comment("when we make a request that results in a single entity (view, store, update)");
//$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
//$I->haveHttpHeader('Accept', 'application/vnd.api+json');
//$I->sendGET("/api/users/1");
//
//$I->expect("should return type value for resource object");
//$I->seeResponseJsonPathSame('$.data.type', 'users');
//
//$I->expect("should return ids for resource object");
//$I->seeResponseJsonPathType('$.data.id', 'string:!empty');
//
//// ----------------------------------------------------
//
//$I->comment("when we make a request that results in multiple entities (index)");
//$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
//$I->haveHttpHeader('Accept', 'application/vnd.api+json');
//$I->sendGET("/api/users");
//
//$I->expect("should return type value for each resource object");
//$I->seeResponseJsonPathSame('$.data[*].type', 'users');
//
//$I->expect("should return ids for each resource object");
//$I->seeResponseJsonPathType('$.data[*].id', 'string:!empty');
