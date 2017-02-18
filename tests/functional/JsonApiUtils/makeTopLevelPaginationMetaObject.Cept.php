<?php

use App\Utils\JsonApiUtils;
use Codeception\Util\Stub;

$I = new FunctionalTester($scenario);

///////////////////////////////////////////////////////
//
// Test: JsonApiUtils::makeTopLevelPaginationMetaObject
//
///////////////////////////////////////////////////////

$I->wantTo("make a top-level pagination meta object for JSON API response");

//-----------------------------------------------------
// valid paginator
//-----------------------------------------------------

$I->comment("given a valid paginator");

$paginator = Stub::makeEmpty('Illuminate\Pagination\LengthAwarePaginator', [
    'count' => function() { return 101; },
    'perPage' => function() { return 102; },
    'currentPage' => function() { return 103; },
    'total' => function() { return 104; },
    'lastPage' => function() { return 105; },
]);

$result = JsonApiUtils::makeTopLevelPaginationMetaObject($paginator);

//-----------------------------------------------------

$I->expect("should return correct pagination meta");
$I->seeJsonPathSame($result, '$.pagination.count', 101);
$I->seeJsonPathSame($result, '$.pagination.limit', 102);
$I->seeJsonPathSame($result, '$.pagination.offset', 103);
$I->seeJsonPathSame($result, '$.pagination.total_items', 104);
$I->seeJsonPathSame($result, '$.pagination.total_pages', 105);
