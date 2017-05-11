<?php
use api\ApiTester;
$I = new ApiTester($scenario,"");

$I->amGoingTo('check that an admin user can login to the ADMIN site.');
$I->loginToSite("ADMIN", "admin", "password");
$I->seeResponseCodeIs(200);

$I->amGoingTo('check that an admin user can not login to the ADMIN site with an invalid password.');
$I->loginToSite("ADMIN", "admin", "invalid_password");
$I->seeResponseCodeIs(401);

$I->amGoingTo('check that an admin user can not login to the ADMIN site with an invalid username.');
$I->loginToSite("ADMIN", "invalid_username", "password");
$I->seeResponseCodeIs(404);

$I->amGoingTo('check that an admin user can login to the ADMIN site and has valid token set.');
$I->loginToSite("ADMIN", "admin", "password");
$I->seeResponseCodeIs(200);
$I->checkForToken("ADMIN");
