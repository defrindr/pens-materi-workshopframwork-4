<?php
namespace frontend\tests\functional;
use frontend\tests\FunctionalTester;
class FunctionNewExampleTestCest
{
    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->amOnPage(['/web-client/index']);
        $I->see('defrindr');
        $I->click('Product');
        $I->see("IPA Kelas 6 SD");
    }
}
