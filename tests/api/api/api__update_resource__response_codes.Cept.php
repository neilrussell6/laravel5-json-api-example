<?php

use App\Models\Project;
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
// "A server MUST return 404 Not Found when processing
// a request to modify a resource that does not exist."
//
// ----------------------------------------------------

// ----------------------------------------------------
// 1) resource updated -> 200 OK
//
// Specs:
// "A server MUST return a 200 OK status code if an
// update is successful."
//
// ----------------------------------------------------

$projects = factory(Project::class, 2)->create();
$project_ids = $projects->pluck('id')->all();

$I->comment("given 2 projects");
$I->assertSame(2, Project::all()->count());

$project_1_id = $project_ids[0];

$I->comment("when we make a request to update a resource");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendPATCH("/api/projects/{$project_1_id}", [
    'data' => [
        'type' => 'projects',
        'attributes' => [
            'name' => "BBB"
        ]
    ]
]);
// TODO: test other endpoints

$I->expect("should return 200 HTTP code");
$I->seeResponseCodeIs(HttpCode::OK);