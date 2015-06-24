<?php
use LazyRecord\Testing\ModelTestCase;
use ActionKit\RecordAction\BaseRecordAction;
/**
 * RecordAction
 */
class ProductActionTest extends ModelTestCase
{
    public $driver = 'sqlite';

    public function getModels()
    {
        return array( 'ProductBundle\\Model\\ProductSchema' );
    }


    public function recordProvider() {
        return [ 
            [ new ProductBundle\Model\Product ],
        ];
    }


    /**
     * @dataProvider recordProvider
     */
    public function testCreateRecordAction($product)
    {
        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Create');
        ok($class);

        $create = new $class( array( 'name' => 'A' ), $product);
        ok($create);

        $ret = $create->run();
        ok($ret,'success action');

        $product->delete();
    }



    public function testAsCreateAction() {
        $product = new ProductBundle\Model\Product;
        ok($product, 'object created');
        $create = $product->asCreateAction([ 'name' => 'TestProduct' ]);
        ok( $create->run() , 'action run' );


        $product = $create->getRecord();
        ok($id = $product->id, 'product created');


        $delete = $product->asDeleteAction();
        ok($delete->run());

        $product = new ProductBundle\Model\Product( $id );
        ok( ! $product->id, 'product should be deleted.');
    }




    public function testUpdateRecordAction()
    {
        $product = new ProductBundle\Model\Product;
        ok($product);
        $ret = $product->create(array( 
            'name' => 'B',
        ));
        ok($ret->success,'record created.');

        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Update');
        ok($class);

        $update = new $class( array( 'id' => $product->id, 'name' => 'C' ), $product);
        ok($update);

        $ret = $update->run();
        ok($ret,'success action');

        $ret = $product->load(array( 'name' => 'C' ));
        ok($ret->success);


        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Delete');
        ok($class);

        $delete = new $class(array( 'id' => $product->id ), $product);
        $ret = $delete->run();
        ok($ret);
    }


    public function testNestedFormRendering()
    {
        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Create');
        $create = new $class;
        ok($create);
        $html = $create->asView()->render();
        ok($html);

        $dom = new DOMDocument;
        $dom->load($html);
        ok($dom);
    }

}


