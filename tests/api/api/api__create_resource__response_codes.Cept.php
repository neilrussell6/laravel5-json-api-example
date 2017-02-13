<?php

use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * create resource
// * response codes
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) create resource -> 201 Created
//
// Specs:
// "the server MUST return either a 201 Created status
// code and response document (as described above) or
// a 204 No Content status code with no response
// document."
//
// ----------------------------------------------------

$I->comment("when we make a request to create a resource");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPOST('/api/projects', [
    'data' => [
        'type' => 'projects',
        'attributes' => [
            'name' => "AAA"
        ]
    ]
]);
// TODO: test other endpoints

$I->expect("should return 201 HTTP code");
$I->seeResponseCodeIs(HttpCode::CREATED);
