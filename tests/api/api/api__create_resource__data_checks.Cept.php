<?php

use App\Models\Project;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// before
//
///////////////////////////////////////////////////////

$I->comment("given no projects");
$I->assertSame(0, Project::all()->count());

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * create resource
// * check data is created
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) create resource
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

$I->expect("should create 1 new record");
$I->assertSame(1, Project::all()->count());
