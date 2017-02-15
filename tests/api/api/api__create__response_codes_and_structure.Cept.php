<?php

use Codeception\Util\Fixtures;
use Codeception\Util\HttpCode;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// Test
//
// * create resource
// * test response codes & structure
//
///////////////////////////////////////////////////////

$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');

// ====================================================
// create resource
// ====================================================

$I->comment("when we create a resource");

$requests = [
    [ 'POST', '/api/users', Fixtures::get('user') ],
    [ 'POST', '/api/projects', Fixtures::get('project') ],
    [ 'POST', '/api/tasks', Fixtures::get('task') ],
];

$I->sendMultiple($requests, function($request) use ($I) {

    $I->comment("given we make a {$request[0]} request to {$request[1]}");

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

    $I->expect("should return 201 HTTP code");
    $I->seeResponseCodeIs(HttpCode::CREATED);

    // ----------------------------------------------------
    // 2) id & type
    //
    // Specs:
    // "A resource object MUST contain ... id."
    // "A resource object MUST contain ... type."
    //
    // ----------------------------------------------------

    $I->expect("should return id for resource object as string");
    $I->seeResponseJsonPathType('$.data.id', 'string:!empty');

    $I->expect("should return type for resource object as string");
    $I->seeResponseJsonPathType('$.data.type', 'string:!empty');

    // ----------------------------------------------------
    // 3) attributes
    //
    // Specs:
    // "a resource object MAY contain ... attributes: an
    // attributes object representing some of the
    // resource’s data."
    //
    // ----------------------------------------------------

    $I->expect("should return an attributes object");
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

    // TODO: test

    // ----------------------------------------------------
    // 7) relationships
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
    // 8) links
    //
    // Specs:
    // "a resource object MAY contain ...
    // links: a links object containing links related to
    // the resource."
    //
    // ----------------------------------------------------

    $I->expect("should return a links object containing only a self property");
    $I->seeResponseJsonPathType('$.data.links', 'array:!empty');
    $I->seeResponseJsonPathRegex('$.data.links.self', '/^http\:\/\/[^\/]+\/api\/\w+\/\d+$/');

    // ----------------------------------------------------
    // 9) meta
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
