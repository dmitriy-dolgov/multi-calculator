<?php
namespace frontend\tests\acceptance;

use frontend\tests\AcceptanceTester;
use yii\helpers\Url;

class HomeCest
{
    public function checkHome(AcceptanceTester $I)
    {
        /*$I->amOnPage(Url::toRoute('http://pizza-customer.local/site/index'));
        $I->see('My Application');

        $I->seeLink('About');
        $I->click('About');
        $I->wait(2); // wait for page to be opened

        $I->see('This is the About page.');*/

        $I->amOnPage(Url::toRoute('https://xn--24-6kc5a8a5ba.ru.com'));
    }
}
