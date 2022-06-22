<?php
namespace frontend\tests;
use common\models\Item;

class UnitNewExampleTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    // tests
    public function testSomeFeature()
    {
        $new = new Item();
        $new->image = 'test.jpg';
        $new->name = 'New Item';
        $new->price = '100';
        $new->category_id = 1;
        $new->save();

        $item = Item::findOne(['name' => 'New Item']);
        expect($item->name)->equals('New Item');
        expect($item->price)->equals('100');
        expect($item->category_id)->equals(1);
        expect($item->image)->equals('test.jpg');

    }
}