<?php

use App\Models\Project;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// before
//
///////////////////////////////////////////////////////

$projects = factory(Project::class, 10)->create();

$I->comment("given 10 projects");
$I->assertSame(10, Project::all()->count());

$project_ids = $projects->pluck('id')->all();
$project_1_id = $project_ids[0];

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * single resource responses
// * response resource objects
//
///////////////////////////////////////////////////////

$I->comment("when we make a request that results in a single entity (view, store, update)");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');

$project_data = [
    'data' => [
        'type' => 'projects',
        'attributes' => [
            'name' => "AAA"
        ]
    ]
];

$requests = [
    [ 'GET', "/api/projects/{$project_1_id}" ],
    [ 'POST', "/api/projects", $project_data ],
    [ 'PATCH', "/api/projects/{$project_1_id}", $project_data ],
];

$I->sendMultiple($requests, function($request) use ($I) {

    $I->comment("given we make a {$request[0]} request to {$request[1]}");

    // ----------------------------------------------------
    // 1) id & type
    //
    // Specs:
    // "A resource object MUST contain ... id."
    // "A resource object MUST contain ... type."
    //
    // ----------------------------------------------------

    $I->expect("should return type value for resource object");
    $I->seeResponseJsonPathSame('$.data.type', 'projects');

    $I->expect("should return id for resource object as string");
    $I->seeResponseJsonPathType('$.data.id', 'string:!empty');

    // ----------------------------------------------------
    // 2) attributes
    //
    // Specs:
    // "a resource object MAY contain ... attributes: an
    // attributes object representing some of the
    // resource’s data."
    //
    // ----------------------------------------------------

    $I->expect("should return an attributes object, containing the entity's visible properties");
    $I->seeResponseJsonPathType('$.data.attributes', 'array:!empty');
    $I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');
    $I->seeResponseJsonPathType('$.data.attributes.status', 'integer');

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

    $I->expect("should return a links object containing only a self property");
    $I->seeResponseJsonPathType('$.data.links', 'array:!empty');
    $I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/projects\/\d+$/');

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

});
