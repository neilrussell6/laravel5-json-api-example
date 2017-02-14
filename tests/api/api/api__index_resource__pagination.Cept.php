<?php

use App\Models\User;

$I = new ApiTester($scenario);

///////////////////////////////////////////////////////
//
// before
//
///////////////////////////////////////////////////////

$I->comment("given 10 users");
$users = factory(User::class, 10)->create();
$user_ids = $users->pluck('id')->all();

///////////////////////////////////////////////////////
//
// Test (general API)
//
// * index various resources
// * pagination
//
///////////////////////////////////////////////////////

// ----------------------------------------------------
// 1) no pagination query args
// ----------------------------------------------------

$I->comment("when we make a request that results in multiple entities (index) but do not include and pagination query params");
$I->haveHttpHeader('Content-Type', 'application/vnd.api+json');
$I->haveHttpHeader('Accept', 'application/vnd.api+json');
$I->sendGET('/api/users');
// TODO: test other endpoints

$I->expect("should return ...");
// TODO: test

// ----------------------------------------------------
// Specs:
// "A server MAY choose to limit the number of
// resources returned in a response to a subset (“page”)
// of the whole set available.
//
// A server MAY provide links to traverse a paginated
// data set (“pagination links”).
//
// Pagination links MUST appear in the links object
// that corresponds to a collection. To paginate the
// primary data, supply pagination links in the
// top-level links object. To paginate an included
// collection returned in a compound document, supply
// pagination links in the corresponding links object.
//
// The following keys MUST be used for pagination links:
//
// first: the first page of data
// last: the last page of data
// prev: the previous page of data
// next: the next page of data
//
// Keys MUST either be omitted or have a null value to
// indicate that a particular link is unavailable.
//
// ----------------------------------------------------

// ----------------------------------------------------
// 2) page 1 of 5
// ----------------------------------------------------

$pagination_count       = 2;
$pagination_limit       = 2;
$pagination_offset      = 1;
$pagination_total_items = 10;
$pagination_total_pages = 5;

$I->comment("when we make a request that results in 10 entities (index) and provide a page.limit of 2 and a page.offset for page 1 of 5");
$I->sendGET('/api/users?page[limit]=2&page[offset]=1');

//-----------------------------------------------------

$I->expect("should return pagination link urls for all, except 'prev' link");
$I->seeResponseJsonPathType('$.links.first', 'string:!empty');
$I->seeResponseJsonPathType('$.links.last', 'string:!empty');
$I->seeResponseJsonPathType('$.links.next', 'string:!empty');
$I->seeResponseJsonPathSame('$.links.prev', null);
$I->seeResponseJsonPathType('$.links.self', 'string:!empty');

$I->expect("should include 'limit' query param all link urls, except 'prev' link");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Blimit\%5D\=2/');

$I->expect("should include correct 'offset' query param all link urls, except 'prev' link");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Boffset\%5D\=1/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Boffset\%5D\=5/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Boffset\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Boffset\%5D\=1/');

//-----------------------------------------------------

$I->expect("should return correct pagination meta");
$I->seeResponseJsonPathSame('$.meta.pagination.count', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.limit', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.offset', 1);
$I->seeResponseJsonPathSame('$.meta.pagination.total_items', 10);
$I->seeResponseJsonPathSame('$.meta.pagination.total_pages', 5);

//-----------------------------------------------------

$I->expect("should limit return data");
$data = $I->grabResponseJsonPath('$.data[*]');
$I->assertSame(count($data), $pagination_limit);

//-----------------------------------------------------

$I->expect("should return first 2 entities");
$ids = $I->grabResponseJsonPath('$.data[*].id');
$I->assertContains($user_ids[0], $ids);
$I->assertContains($user_ids[1], $ids);

// ----------------------------------------------------
// 3) page 2 of 5
// ----------------------------------------------------

$pagination_count       = 2;
$pagination_limit       = 2;
$pagination_offset      = 2;
$pagination_total_items = 10;
$pagination_total_pages = 5;

$I->comment("when we make a request that results in 10 entities (index) and provide a page.limit of 2 and a page.offset for page 2 of 5");
$I->sendGET('/api/users?page[limit]=2&page[offset]=2');

//-----------------------------------------------------

$I->expect("should return pagination link urls for all links");
$I->seeResponseJsonPathType('$.links.first', 'string:!empty');
$I->seeResponseJsonPathType('$.links.last', 'string:!empty');
$I->seeResponseJsonPathType('$.links.next', 'string:!empty');
$I->seeResponseJsonPathType('$.links.prev', 'string:!empty');
$I->seeResponseJsonPathType('$.links.self', 'string:!empty');

$I->expect("should include provided 'limit' query param all link urls");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Blimit\%5D\=2/');

$I->expect("should include correct 'offset' query param all link urls");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Boffset\%5D\=1/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Boffset\%5D\=5/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Boffset\%5D\=3/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Boffset\%5D\=1/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Boffset\%5D\=2/');

//-----------------------------------------------------

$I->expect("should return correct pagination meta");
$I->seeResponseJsonPathSame('$.meta.pagination.count', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.limit', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.offset', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.total_items', 10);
$I->seeResponseJsonPathSame('$.meta.pagination.total_pages', 5);

//-----------------------------------------------------

$I->expect("should limit return data");
$data = $I->grabResponseJsonPath('$.data[*]');
$I->assertSame(count($data), $pagination_limit);

//-----------------------------------------------------

$I->expect("should return records 3 & 4");
$ids = $I->grabResponseJsonPath('$.data[*].id');
$I->assertContains($user_ids[2], $ids);
$I->assertContains($user_ids[3], $ids);

// ----------------------------------------------------
// 4) page 3 of 5
// ----------------------------------------------------

$pagination_count       = 2;
$pagination_limit       = 2;
$pagination_offset      = 3;
$pagination_total_items = 10;
$pagination_total_pages = 5;

$I->comment("when we make a request that results in 10 entities (index) and provide a page.limit of 2 and a page.offset for page 3 of 5");
$I->sendGET('/api/users?page[limit]=2&page[offset]=3');

//-----------------------------------------------------

$I->expect("should return pagination link urls for all links");
$I->seeResponseJsonPathType('$.links.first', 'string:!empty');
$I->seeResponseJsonPathType('$.links.last', 'string:!empty');
$I->seeResponseJsonPathType('$.links.next', 'string:!empty');
$I->seeResponseJsonPathType('$.links.prev', 'string:!empty');
$I->seeResponseJsonPathType('$.links.self', 'string:!empty');

$I->expect("should include provided 'limit' query param all link urls");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Blimit\%5D\=2/');

$I->expect("should include correct 'offset' query param all link urls");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Boffset\%5D\=1/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Boffset\%5D\=5/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Boffset\%5D\=4/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Boffset\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Boffset\%5D\=3/');

//-----------------------------------------------------

$I->expect("should return correct pagination meta");
$I->seeResponseJsonPathSame('$.meta.pagination.count', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.limit', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.offset', 3);
$I->seeResponseJsonPathSame('$.meta.pagination.total_items', 10);
$I->seeResponseJsonPathSame('$.meta.pagination.total_pages', 5);

//-----------------------------------------------------

$I->expect("should limit return data");
$data = $I->grabResponseJsonPath('$.data[*]');
$I->assertSame(count($data), $pagination_limit);

//-----------------------------------------------------

$I->expect("should return records 5 & 6");
$ids = $I->grabResponseJsonPath('$.data[*].id');
$I->assertContains($user_ids[4], $ids);
$I->assertContains($user_ids[5], $ids);

// ----------------------------------------------------
// 4) page 4 of 5
// ----------------------------------------------------

$pagination_count       = 2;
$pagination_limit       = 2;
$pagination_offset      = 4;
$pagination_total_items = 10;
$pagination_total_pages = 5;

$I->comment("when we make a request that results in 10 entities (index) and provide a page.limit of 2 and a page.offset for page 4 of 5");
$I->sendGET('/api/users?page[limit]=2&page[offset]=4');

//-----------------------------------------------------

$I->expect("should return pagination link urls for all links");
$I->seeResponseJsonPathType('$.links.first', 'string:!empty');
$I->seeResponseJsonPathType('$.links.last', 'string:!empty');
$I->seeResponseJsonPathType('$.links.next', 'string:!empty');
$I->seeResponseJsonPathType('$.links.prev', 'string:!empty');
$I->seeResponseJsonPathType('$.links.self', 'string:!empty');

$I->expect("should include provided 'limit' query param all link urls");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Blimit\%5D\=2/');

$I->expect("should include correct 'offset' query param all link urls");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Boffset\%5D\=1/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Boffset\%5D\=5/');
$I->seeResponseJsonPathRegex('$.links.next', '/\?.*page\%5Boffset\%5D\=5/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Boffset\%5D\=3/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Boffset\%5D\=4/');

//-----------------------------------------------------

$I->expect("should return correct pagination meta");
$I->seeResponseJsonPathSame('$.meta.pagination.count', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.limit', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.offset', 4);
$I->seeResponseJsonPathSame('$.meta.pagination.total_items', 10);
$I->seeResponseJsonPathSame('$.meta.pagination.total_pages', 5);

//-----------------------------------------------------

$I->expect("should limit return data");
$data = $I->grabResponseJsonPath('$.data[*]');
$I->assertSame(count($data), $pagination_limit);

//-----------------------------------------------------

$I->expect("should return records 7 & 8");
$ids = $I->grabResponseJsonPath('$.data[*].id');
$I->assertContains($user_ids[6], $ids);
$I->assertContains($user_ids[7], $ids);

// ----------------------------------------------------
// 4) page 5 of 5
// ----------------------------------------------------

$pagination_count       = 2;
$pagination_limit       = 2;
$pagination_offset      = 5;
$pagination_total_items = 10;
$pagination_total_pages = 5;

$I->comment("when we make a request that results in 10 entities (index) and provide a page.limit of 2 and a page.offset for page 5 of 5");
$I->sendGET('/api/users?page[limit]=2&page[offset]=5');

//-----------------------------------------------------

$I->expect("should return pagination link urls for all links, except 'next' link");
$I->seeResponseJsonPathType('$.links.first', 'string:!empty');
$I->seeResponseJsonPathType('$.links.last', 'string:!empty');
$I->seeResponseJsonPathNull('$.links.next', null);
$I->seeResponseJsonPathType('$.links.prev', 'string:!empty');
$I->seeResponseJsonPathType('$.links.self', 'string:!empty');

$I->expect("should include provided 'limit' query param all link urls, except 'next' link");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Blimit\%5D\=2/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Blimit\%5D\=2/');

$I->expect("should include correct 'offset' query param all link urls, except 'next' link");
$I->seeResponseJsonPathRegex('$.links.first', '/\?.*page\%5Boffset\%5D\=1/');
$I->seeResponseJsonPathRegex('$.links.last', '/\?.*page\%5Boffset\%5D\=5/');
$I->seeResponseJsonPathRegex('$.links.prev', '/\?.*page\%5Boffset\%5D\=4/');
$I->seeResponseJsonPathRegex('$.links.self', '/\?.*page\%5Boffset\%5D\=5/');

//-----------------------------------------------------

$I->expect("should return correct pagination meta");
$I->seeResponseJsonPathSame('$.meta.pagination.count', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.limit', 2);
$I->seeResponseJsonPathSame('$.meta.pagination.offset', 5);
$I->seeResponseJsonPathSame('$.meta.pagination.total_items', 10);
$I->seeResponseJsonPathSame('$.meta.pagination.total_pages', 5);

//-----------------------------------------------------

$I->expect("should limit return data");
$data = $I->grabResponseJsonPath('$.data[*]');
$I->assertSame(count($data), $pagination_limit);

//-----------------------------------------------------

$I->expect("should return records 9 & 10");
$ids = $I->grabResponseJsonPath('$.data[*].id');
$I->assertContains($user_ids[8], $ids);
$I->assertContains($user_ids[9], $ids);
