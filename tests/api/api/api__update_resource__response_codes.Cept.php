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
// 1) update resource -> 200 OK
//
// Specs:
// "A server MUST return a 200 OK status code if an
// update is successful."
//
// ----------------------------------------------------

$I->comment("when we make a request to update a resource");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPATCH("/api/tasks/1", [
    'data' => [
        'type' => 'tasks',
        'attributes' => [
            'name' => "BBB"
        ]
    ]
]);
// TODO: test other methods & endpoints

$I->expect("should return 200 HTTP code");
$I->seeResponseCodeIs(HttpCode::OK);
