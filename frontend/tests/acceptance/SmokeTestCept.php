<?php use frontend\tests\AcceptanceTester;
//$I = new AcceptanceTester($scenario);
//$I->wantTo('perform actions and see result');

$I = new AcceptanceTester($scenario);
$I->wantTo('Check that MainPage and About are work');
$I->amOnPage('/');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Главная блога'); // ! Тут часть фразы с вашей главной
$I->amOnPage('/about');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Обо мне'); // ! Тут часть фразы с вашей страницы about

