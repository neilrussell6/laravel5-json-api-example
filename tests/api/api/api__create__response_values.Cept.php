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

$I->comment("given no users");
$I->assertSame(0, User::all()->count());

$I->comment("given no projects");
$I->assertSame(0, Project::all()->count());

$I->comment("given no tasks");
$I->assertSame(0, Task::all()->count());

///////////////////////////////////////////////////////
//
// Test
//
// * create resource
// * test response values
//
///////////////////////////////////////////////////////

$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');

// ====================================================
// create user
// ====================================================

$I->comment("when we create a user");
$I->sendPOST('/api/users', Fixtures::get('user'));

// ----------------------------------------------------
// 1) id & type
// ----------------------------------------------------

$I->expect("should return correct type & id values in resource object");
$I->seeResponseJsonPathSame('$.data.type', 'users');
$I->seeResponseJsonPathSame('$.data.id', '1');

// ----------------------------------------------------
// 2) attributes
// ----------------------------------------------------

$I->expect("should return correct attributes for the entity");
$I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');

$I->expect("attributes object should not include type or id");
$I->seeNotResponseJsonPath('$.data.attributes.type');
$I->seeNotResponseJsonPath('$.data.attributes.id');

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
// create project
// ====================================================

$I->comment("when we create a project");
$I->sendPOST('/api/projects', Fixtures::get('project'));

// ----------------------------------------------------
// 1) id & type
// ----------------------------------------------------

$I->expect("should return correct type & id values in resource object");
$I->seeResponseJsonPathSame('$.data.type', 'projects');
$I->seeResponseJsonPathSame('$.data.id', '1');

// ----------------------------------------------------
// 2) attributes
// ----------------------------------------------------

$I->expect("should return correct attributes for the entity");
$I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');

$I->expect("attributes object should include all those set during creation, even if they were not not included in request");
$I->seeResponseJsonPathSame('$.data.attributes.status', Project::STATUS_INCOMPLETE);

$I->expect("attributes object should not include type or id");
$I->seeNotResponseJsonPath('$.data.attributes.type');
$I->seeNotResponseJsonPath('$.data.attributes.id');

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
// create task
// ====================================================

$I->comment("when we create a task");
$I->sendPOST('/api/tasks', Fixtures::get('task'));

// ----------------------------------------------------
// 1) id & type
// ----------------------------------------------------

$I->expect("should return correct type & id values in resource object");
$I->seeResponseJsonPathSame('$.data.type', 'tasks');
$I->seeResponseJsonPathSame('$.data.id', '1');

// ----------------------------------------------------
// 2) attributes
// ----------------------------------------------------

$I->expect("should return correct attributes for the entity");
$I->seeResponseJsonPathType('$.data.attributes.name', 'string:!empty');

$I->expect("attributes object should include all those set during creation, even if they were not not included in request");
$I->seeResponseJsonPathSame('$.data.attributes.status', Task::STATUS_INCOMPLETE);

$I->expect("attributes object should not include type or id");
$I->seeNotResponseJsonPath('$.data.attributes.type');
$I->seeNotResponseJsonPath('$.data.attributes.id');

$I->expect("attributes object should not include any foreign keys");
$I->seeNotResponseJsonPath('$.data.attributes.project_id');

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
