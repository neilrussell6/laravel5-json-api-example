<?php

use App\Utils\UrlUtils;

$I = new FunctionalTester($scenario);

///////////////////////////////////////////////////////
//
// Test: UrlUtils::containsId
//
///////////////////////////////////////////////////////

$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/1'), "http://www.aaa.com/1 contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/1'), "http://www.aaa.com/bbb/1 contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/11'), "http://www.aaa.com/bbb/11 contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/1/ccc/2'), "http://www.aaa.com/bbb/1/ccc/2 contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/1/ccc/22'), "http://www.aaa.com/bbb/1/ccc/22 contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/11/ccc/2'), "http://www.aaa.com/bbb/11/ccc/2 contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/11/ccc/22'), "http://www.aaa.com/bbb/11/ccc/22 contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/1?ddd=eee'), "http://www.aaa.com/bbb/1?ddd=eee contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/11?ddd=eee'), "http://www.aaa.com/bbb/11?ddd=eee contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/1/ccc/2?ddd=eee'), "http://www.aaa.com/bbb/1/ccc/2?ddd=eee contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/1/ccc/22?ddd=eee'), "http://www.aaa.com/bbb/1/ccc/22?ddd=eee contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/11/ccc/2?ddd=eee'), "http://www.aaa.com/bbb/11/ccc/2?ddd=eee contains ID");
$I->assertTrue(UrlUtils::containsId('http://www.aaa.com/bbb/11/ccc/22?ddd=eee'), "http://www.aaa.com/bbb/11/ccc/22?ddd=eee contains ID");

$I->assertFalse(UrlUtils::containsId('http://www.aaa.com'), "http://www.aaa.com does not contain ID");
$I->assertFalse(UrlUtils::containsId('http://www.aaa.com/bbb'), "http://www.aaa.com/bbb does not contain ID");
$I->assertFalse(UrlUtils::containsId('http://www.aaa.com/bbb?ddd=eee'), "http://www.aaa.com/bbb?ddd=eee does not contain ID");
$I->assertFalse(UrlUtils::containsId('http://www.aaa.com/bbb/1/ccc'), "http://www.aaa.com/bbb/1/ccc does not contain ID");
$I->assertFalse(UrlUtils::containsId('http://www.aaa.com/bbb/11/ccc'), "http://www.aaa.com/bbb/11/ccc does not contain ID");
$I->assertFalse(UrlUtils::containsId('http://www.aaa.com/bbb/1/ccc?ddd=eee'), "http://www.aaa.com/bbb/1/ccc?ddd=eee does not contain ID");
$I->assertFalse(UrlUtils::containsId('http://www.aaa.com/bbb/11/ccc?ddd=eee'), "http://www.aaa.com/bbb/11/ccc?ddd=eee does not contain ID");
