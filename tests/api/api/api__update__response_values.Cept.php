<?php

use Codeception\Util\Fixtures;
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
factory(Project::class, 10)->create([ 'status' => Project::STATUS_INCOMPLETE ]);
$I->assertSame(10, Project::all()->count());

$I->comment("given 10 tasks");
factory(Task::class, 10)->create([ 'project_id' => 1,  'status' => Project::STATUS_INCOMPLETE ]);
$I->assertSame(10, Task::all()->count());

///////////////////////////////////////////////////////
//
// Test
//
// * update resource
// * test response values
//
///////////////////////////////////////////////////////

$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');

// ====================================================
// update user
// ====================================================

$user_ids = array_column(User::all()->toArray(), 'id');
$user_1_id = $user_ids[0];
$user = Fixtures::get('user');
$user['data']['attributes']['name'] = "BBB";

// ----------------------------------------------------

$I->comment("when we update user 1");
$I->sendPATCH("/api/users/{$user_1_id}", $user);

// ----------------------------------------------------
// 1) id & type
// ----------------------------------------------------

$I->expect("should return correct type & id values in resource object");
$I->seeResponseJsonPathSame('$.data.type', 'users');
$I->seeResponseJsonPathSame('$.data.id', "{$user_1_id}");

// ----------------------------------------------------
// 2) attributes
// ----------------------------------------------------

$I->expect("should return correct attributes for the entity");
$I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');
$I->seeResponseJsonPathType('$.data.attributes.email', 'string:!empty');

// TODO: implement
//$I->expect("attributes object should not include type or id");
//$I->seeNotResponseJsonPath('$.data.attributes.type');
//$I->seeNotResponseJsonPath('$.data.attributes.id');

// ----------------------------------------------------
// 3) links
// ----------------------------------------------------

$I->expect("should return correct links object containing only a self property");
$I->seeResponseJsonPathType('$.data.links', 'array:!empty');
$I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/users\/1$/');

// ----------------------------------------------------
// 4) meta
// ----------------------------------------------------

// TODO: test

// ----------------------------------------------------
// 5) relationships
// ----------------------------------------------------

// TODO: test

// ====================================================
// update project
// ====================================================

$project_ids = array_column(Project::all()->toArray(), 'id');
$project_1_id = $project_ids[0];
$project = Fixtures::get('project');
$project['data']['attributes']['name'] = "BBB";

// ----------------------------------------------------

$I->comment("when we update project 1");
$I->sendPATCH("/api/projects/{$project_1_id}", $project);

// ----------------------------------------------------
// 1) id & type
// ----------------------------------------------------

$I->expect("should return correct type & id values in resource object");
$I->seeResponseJsonPathSame('$.data.type', 'projects');
$I->seeResponseJsonPathSame('$.data.id', "{$project_1_id}");

// ----------------------------------------------------
// 2) attributes
// ----------------------------------------------------

$I->expect("should return correct attributes for the entity");
$I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');

$I->expect("attributes object should include all those set during creation, even if they were not not included in request");
$I->seeResponseJsonPathSame('$.data.attributes.status', Project::STATUS_INCOMPLETE);

// TODO: implement
//$I->expect("attributes object should not include type or id");
//$I->seeNotResponseJsonPath('$.data.attributes.type');
//$I->seeNotResponseJsonPath('$.data.attributes.id');

// ----------------------------------------------------
// 3) links
// ----------------------------------------------------

$I->expect("should return correct links object containing only a self property");
$I->seeResponseJsonPathType('$.data.links', 'array:!empty');
$I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/projects\/1$/');

// ----------------------------------------------------
// 4) meta
// ----------------------------------------------------

// TODO: test

// ----------------------------------------------------
// 5) relationships
// ----------------------------------------------------

// TODO: test

// ====================================================
// update task
// ====================================================

$task_ids = array_column(Task::all()->toArray(), 'id');
$task_1_id = $task_ids[0];
$task = Fixtures::get('task');
$task['data']['attributes']['name'] = "BBB";

// ----------------------------------------------------

$I->comment("when we update task 1");
$I->sendPATCH("/api/tasks/{$task_1_id}", $task);

// ----------------------------------------------------
// 1) id & type
// ----------------------------------------------------

$I->expect("should return correct type & id values in resource object");
$I->seeResponseJsonPathSame('$.data.type', 'tasks');
$I->seeResponseJsonPathSame('$.data.id', "{$task_1_id}");

// ----------------------------------------------------
// 2) attributes
// ----------------------------------------------------

$I->expect("should return correct attributes for the entity");
$I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');

$I->expect("attributes object should include all those set during creation, even if they were not not included in request");
$I->seeResponseJsonPathSame('$.data.attributes.status', Project::STATUS_INCOMPLETE);

// TODO: implement
//$I->expect("attributes object should not include type or id");
//$I->seeNotResponseJsonPath('$.data.attributes.type');
//$I->seeNotResponseJsonPath('$.data.attributes.id');

// TODO: implement
//$I->expect("attributes object should not include any foreign keys");
//$I->seeNotResponseJsonPath('$.data.attributes.project_id');

// ----------------------------------------------------
// 3) links
// ----------------------------------------------------

$I->expect("should return correct links object containing only a self property");
$I->seeResponseJsonPathType('$.data.links', 'array:!empty');
$I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/tasks\/1$/');

// ----------------------------------------------------
// 4) meta
// ----------------------------------------------------

// TODO: test

// ----------------------------------------------------
// 5) relationships
// ----------------------------------------------------

// TODO: test
