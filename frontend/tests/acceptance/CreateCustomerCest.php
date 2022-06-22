<?php
namespace frontend\tests\acceptance;
use frontend\tests\AcceptanceTester;
use yii\helpers\Url;

class CreateCustomerCest
{
    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        // print_r(Url::toRoute('/'));
        $I->amOnPage(Url::toRoute('/'));

        $I->seeLink('About');
        $I->click('About');
        $I->wait(2); // wait for page to be opened
        $I->see('Home');
        $I->seeLink('Login');
        $I->click('Login');
        $I->wait(2); // wai
        $I->fillField('LoginForm[username]', 'defrindr');
        $I->fillField('LoginForm[password]', 'defrindr');
        $I->click('#login-form > div.form-group > button.btn.btn-primary');
        $I->wait(2); // wai
        $I->see('IPA Kelas 6 SD');

    }
}
