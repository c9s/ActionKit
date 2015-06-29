<?php
use ActionKit\Action;
use ActionKit\MixinAction;

class FakeMixin extends MixinAction {

    public function schema() {
        $this->param('handle')->type('string');
    }

}

class ImageParamTestAction extends Action {

    public function mixins() {
        return array( 
            new FakeMixin($this),
        );
    }

    public function schema() {
        $this->param('image','Image');
    }
}

class TestTakeFilterAction extends Action {

    public function schema() {
        $this->param('extra1');
        $this->param('extra2');
        $this->param('extra3');
        $this->param('only_this1');
        $this->param('only_this2');
        $this->takes('only_this1','only_this2');
    }

}

class LoginTestAction extends Action 
{

    public function schema() 
    {
        $this->param('username');
        $this->param('password');
        $this->filterOut(array('token'));
    }

    public function run() {
        // test filterOut
        if ( $this->arg('token') ) {
            return $this->error('token should be filter out.');
        }

        if ( $this->arg('username') == 'admin' &&
            $this->arg('password') == 's3cr3t' ) {
                return $this->success('Login');
        }
        return $this->error('Error');
    }
}

class ActionTest extends PHPUnit_Framework_TestCase
{

    public function testGetParamsWithTakeFilter()
    {
        $take = new TestTakeFilterAction;
        $params = $take->getParams();
        ok($params,'get params');

        $keys = $take->takeFields;
        foreach( $keys as $k ) {
            ok( isset($params[$k]), "has key $k");
        }
        ok( ! isset($params['extra1']) );
        ok( ! isset($params['extra2']) );
        ok( ! isset($params['extra3']) );
    }

    public function testRender()
    {
        $login = new LoginTestAction;

        $result = $login->renderField('username');
        ok($result);

        $result = $login->renderLabel('password');
        ok($result);

        $result = $login->renderWidgets(['username', 'password']);
        ok($result);

        $result = $login->renderSubmitWidget();
        ok($result);

        $result = $login->renderButtonWidget();
        ok($result);

        $result = $login->renderSignatureWidget();
        ok($result);
    }

    public function testGetParamsWithFilterOut() 
    {
        $login = new LoginTestAction;
        ok($login);

        $params = $login->getParams(); // get params with param filter
        ok( $params );

        count_ok(3, array_keys($params));
        ok( !isset($params['token']) , 'Should not include token param.' );
        ok( isset($params['username']) , 'Should have username param' );
        ok( isset($params['password']) , 'Should have password param' );
    }

    /*
    public function testImageParam() 
    {
        $action = new ImageParamTestAction(array(
            'image' => 1,
        ));
        ok($action);
    }
     */

    public function testFilterOut()
    {
        $action = new LoginTestAction(array(
            'username' => 'admin',
            'password' => 's3cr3t',
            'token' => 'blah',
        ));
        ok($action,'Got action');

        $success = $action->invoke();
        ok($success, $action->result->message);

        $result = $action->result;
        ok($result,'Got Result');

        is('Login', $result->message);
        ok( $result->isSuccess() );
    }

    public function testMethods()
    {
        $login = new LoginTestAction;
        is($login->getName(), 'LoginTestAction');
    }
}

