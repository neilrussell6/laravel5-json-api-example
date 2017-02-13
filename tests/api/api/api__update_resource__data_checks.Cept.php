<?php

use App\Models\Project;
use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// before
//
///////////////////////////////////////////////////////

$projects = factory(Project::class, 2)->create();
$project_ids = $projects->pluck('id')->all();

$I->comment("given 2 projects");
$I->assertSame(2, Project::all()->count());

$project_1_id = $project_ids[0];

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * update resource
// * response codes
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) update resource
// ----------------------------------------------------

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

$I->expect("should change project 1 name");
$project_1 = Project::find($project_1_id)->toArray();
$I->assertSame("BBB", $project_1['name']);
