<?php

use Codeception\Util\HttpCode;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// before
//
///////////////////////////////////////////////////////

$I->comment("given 10 users");
factory(User::class, 10)->create();
$I->assertSame(10, User::all()->count());

$I->comment("given 10 projects");
factory(Project::class, 10)->create();
$I->assertSame(10, Project::all()->count());

$I->comment("given 10 tasks");
factory(Task::class, 10)->create(['project_id' => 1]);
$I->assertSame(10, Task::all()->count());

///////////////////////////////////////////////////////
//
// Test
//
// * view resource
// * test response codes & structure
//
///////////////////////////////////////////////////////

$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');

// ====================================================
// view resource
// ====================================================

$I->comment("when we view a resource");

$user_ids = array_column(User::all()->toArray(), 'id');
$user_1_id = $user_ids[0];

$project_ids = array_column(Project::all()->toArray(), 'id');
$project_1_id = $project_ids[0];

$task_ids = array_column(Task::all()->toArray(), 'id');
$task_1_id = $task_ids[0];

$requests = [
    [ 'GET', "/api/users/{$user_1_id}" ],
    [ 'GET', "/api/projects/{$project_1_id}" ],
    [ 'GET', "/api/tasks/{$task_1_id}" ],
];

$I->sendMultiple($requests, function($request) use ($I) {

    $I->comment("given we make a {$request[0]} request to {$request[1]}");

    // ----------------------------------------------------
    // 1) view resource -> 200 OK
    //
    // Specs:
    // "A server MUST respond to a successful request to
    // fetch an individual resource ... with a 200 OK
    // response."
    //
    // ----------------------------------------------------

    $I->expect("should return 200 HTTP code");
    $I->seeResponseCodeIs(HttpCode::OK);

    // ----------------------------------------------------
    // 2) id & type
    //
    // Specs:
    // "A resource object MUST contain ... id."
    // "A resource object MUST contain ... type."
    //
    // ----------------------------------------------------

    $I->expect("should return type value for resource object");
    $I->seeResponseJsonPathType('$.data.type', 'string:!empty');

    $I->expect("should return ids for resource object as strings");
    $I->seeResponseJsonPathType('$.data.id', 'string:!empty');

    // ----------------------------------------------------
    // 3) attributes
    //
    // Specs:
    // "a resource object MAY contain ... attributes: an
    // attributes object representing some of the
    // resource’s data."
    //
    // ----------------------------------------------------

    $I->expect("should return an attributes object for entity, containing it's visible properties");
    $I->seeResponseJsonPathType('$.data.attributes', 'array:!empty');

    // ----------------------------------------------------
    // 4) attributes (id & type)
    //
    // Specs:
    // "a resource can not have ... an attribute or
    // relationship named type or id"
    //
    // ----------------------------------------------------

    $I->expect("attributes object should not include type or id");
    $I->seeNotResponseJsonPath('$.data.attributes.type');
    $I->seeNotResponseJsonPath('$.data.attributes.id');

    // ----------------------------------------------------
    // 5) attributes (same name)
    //
    // Specs:
    // "a resource can not have an attribute and
    // relationship with the same name."
    //
    // ----------------------------------------------------

    // TODO: test

    // ----------------------------------------------------
    // 6) attributes (foreign keys)
    //
    // Specs:
    // "Although has-one foreign keys (e.g. author_id) are
    // often stored internally alongside other information
    // to be represented in a resource object, these keys
    // SHOULD NOT appear as attributes."
    //
    // ----------------------------------------------------

    $I->expect("attributes object should not include any foreign keys");
    $attributes = $I->grabResponseJsonPath('$.data[*].attributes');
    $unique_attributes = array_reduce($attributes, function ($carry, $obj) {
        return array_unique(array_merge($carry, array_keys($obj)));
    }, []);
    $I->assertNotContainsRegex('/(.*?)\_id$/', $unique_attributes);

    // ----------------------------------------------------
    // 7) links
    //
    // Specs:
    // "a resource object MAY contain ...
    // links: a links object containing links related to
    // the resource."
    //
    // ----------------------------------------------------

    $I->expect("should return a links object for entity containing only a self property");
    $I->seeResponseJsonPathType('$.data.links', 'array:!empty');
    $I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/\w+\/\d+$/');

    // ----------------------------------------------------
    // 8) meta
    //
    // Specs:
    // "a resource object MAY contain ...
    // meta: a meta object containing non-standard
    // meta-information about a resource that can not be
    // represented as an attribute or relationship."
    //
    // ----------------------------------------------------

    $I->expect("should not return meta for entity");
    $I->seeNotResponseJsonPath('$.data.meta');

    // ----------------------------------------------------
    // 9) relationships
    //
    // Specs:
    // "a resource object MAY contain ...
    // relationships: a relationships object describing
    // relationships between the resource and other JSON
    // API resources."
    //
    // ----------------------------------------------------

    // TODO: test relationship response
});
