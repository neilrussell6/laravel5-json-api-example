<?php

use App\Models\Project;
use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * create resource
// * response resource objects
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
$I->seeResponseJsonPathSame('$.data.attributes.name', "AAA");

// ----------------------------------------------------
// 3) attributes (set during creation but not included in request)
// ----------------------------------------------------

$I->expect("attributes object should include all those set during creation, even if they were not not included in request");
$I->seeResponseJsonPathSame('$.data.attributes.status', Project::STATUS_INCOMPLETE);

// ----------------------------------------------------
// 4) attributes (has one ids)
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
// 5) relationships
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
// 6) links
//
// Specs:
// "a resource object MAY contain ...
// links: a links object containing links related to
// the resource."
//
// ----------------------------------------------------

$I->expect("should return a links object containing only a self property");
$I->seeResponseJsonPathType('$.data.links', 'array:!empty');
$I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/projects\/1$/');

// ----------------------------------------------------
// 7) meta
//
// Specs:
// "a resource object MAY contain ...
// meta: a meta object containing non-standard
// meta-information about a resource that can not be
// represented as an attribute or relationship."
//
// ----------------------------------------------------

// TODO: test
