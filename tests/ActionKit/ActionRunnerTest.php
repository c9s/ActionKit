<?php
use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ServiceContainer;
use ActionKit\ActionRunner;

class ActionRunnerTest extends \LazyRecord\Testing\ModelTestCase
{

    public function getModels()
    {
        return array( 
            'User\Model\UserSchema'
        );
    }

    public function testRegisterAction()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate);
        $runner = new ActionRunner($container);
        $runner->registerAutoloader();
        $runner->registerAction('TwigActionTemplate', array(
            'template' => '@ActionKit/RecordAction.html.twig',
            'action_class' => 'User\\Action\\BulkCreateUser',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));

        $result = $runner->run('User::Action::BulkCreateUser',array(
            'email' => 'foo@foo'
        ));
        ok($result);
    }

    public function testRegisterActionWithTwig()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate($container['twig_loader']));
        $runner = new ActionRunner($container);
        $runner->registerAutoloader();
        $runner->registerAction('TwigActionTemplate', array(
            'template' => '@ActionKit/RecordAction.html.twig',
            'action_class' => 'User\\Action\\BulkCreateUser',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));

        $result = $runner->run('User::Action::BulkCreateUser',array(
            'email' => 'foo@foo'
        ));
        ok($result);
    }

    public function testRunAndJsonOutput()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate);
        $runner = new ActionRunner($container);
        ok($runner);
        $runner->registerAutoloader();
        $runner->registerAction('RecordActionTemplate', array(
            'namespace' => 'User',
            'model' => 'User',
            'types' => array(
                [ 'prefix' => 'Create'],
                [ 'prefix' => 'Update'],
                [ 'prefix' => 'Delete'],
            )
        ));

        $result = $runner->run('User::Action::CreateUser',[ 
            'email' => 'foo@foo'
        ]);
        ok($result);

        $json = $result->__toString();
        ok($json,'json output');
        $data = json_decode($json);
        ok($data->success);
        ok($data->data);
        ok($data->data->id);

        $results = $runner->getResults();
        ok($results);
        ok($runner->getResult('User::Action::CreateUser'));
        $runner->setResult('test', 'test message');
        is(true, $runner->hasResult('test'));
        $runner->removeResult('test');
        is(false, $runner->hasResult('test'));
    }

    public function testHandleWith()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);

        $stream = fopen('php://memory', 'rw');
        $result = $runner->handleWith($stream, array(
            'action' => 'User::Action::CreateUser',
            '__ajax_request' => 1,
            'email' => 'foo@foo'
        ));
        is(true, $result);

        fseek($stream, 0);
        $output = stream_get_contents($stream);
        $expected_output = '{"args":{"email":"foo@foo"},"success":true,"message":"User Record is created.","data":{"email":"foo@foo","id":1}}';
        is($expected_output, $output);
    }

    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testHandleWithInvalidActionNameException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array(
            'action' => "_invalid"
        ));
    }

    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testHandleWithInvalidActionNameExceptionWithEmptyActionName()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array());  
        
    }

    /**
    *   @expectedException  ActionKit\Exception\ActionNotFoundException
    */
    public function testHandleWithActionNotFoundException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->handleWith(STDOUT, array(
            'action' => "User::Action::NotFoundAction",
        )); 
    }

    /**
    *   @expectedException  ActionKit\Exception\InvalidActionNameException
    */
    public function testRunnerWithInvalidActionNameException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->run('!afers');
    }

    /**
    *   @expectedException  ActionKit\Exception\ActionNotFoundException
    */
    public function testRunnerWithActionNotFoundException()
    {
        $container = new ServiceContainer;
        $runner = new ActionRunner($container);
        $result = $runner->run('Product::Action::Product');
    }

}
