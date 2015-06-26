<?php
use ActionKit\ActionTemplate\SampleActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\FileBasedActionTemplate;
use ActionKit\ActionTemplate\SortRecordActionTemplate;

class ActionTemplate extends PHPUnit_Framework_TestCase
{

    public function testSampleActionTemplate()
    {
        $actionTemplate = new SampleActionTemplate();
        $generatedAction = $actionTemplate->generate('', array(
            'namespace' => 'Core',
            'actionName' => 'GrantAccess'
        ));
        ok( $generatedAction );

        $generatedAction->load();

        is( 'Core\\Action\\GrantAccess' , $generatedAction->className );
        ok( class_exists( 'Core\\Action\\GrantAccess' ) );
    }

    public function testSortRecordActionTemplate()
    {
        $actionTemplate = new SortRecordActionTemplate;
        $runner = new ActionKit\ActionRunner;
        $actionTemplate->register($runner, 'SortRecordActionTemplate', array(
            'namespace' => 'test2',
            'model' => 'Test2Model',   // model's name
            'types' => array('Sort')
        ));
        is(1, count($runner->dynamicActions));

        $className = 'test2\Action\SortTest2Model';
        $actionArgs = $runner->dynamicActions[$className]['actionArgs'];
        $generatedAction = $actionTemplate->generate($className, $actionArgs);
        ok( $generatedAction );

        $generatedAction->load();
        ok( class_exists( $className ) );
    }

    public function testCodeGenBased()
    {
        $actionTemplate = new RecordActionTemplate();
        $runner = new ActionKit\ActionRunner;
        $actionTemplate->register($runner, 'RecordActionTemplate', array(
            'namespace' => 'test2',
            'model' => 'test2Model',   // model's name
            'types' => array(
                [ 'name' => 'Create'],
                [ 'name' => 'Update'],
                [ 'name' => 'Delete'],
                [ 'name' => 'BulkDelete']
            )
        ));
        is(4, count($runner->dynamicActions));

        $className = 'test2\Action\Updatetest2Model';
        $generatedAction = $actionTemplate->generate($className, $runner->dynamicActions[$className]);
        ok( $generatedAction );

        $generatedAction->load();
        ok( class_exists( $className ) );
    }

    public function testFildBasedTemplate()
    {
        $actionTemplate = new FileBasedActionTemplate();

        $runner = new ActionKit\ActionRunner;

        $className = 'User\\Action\\BulkUpdateUser2';

        $actionTemplate->register($runner, 'FileBasedActionTemplate', array(
            'action_class' => $className,
            'template' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        is(1, count($runner->dynamicActions));

        is(true, isset($runner->dynamicActions[$className]));

        $generatedAction = $actionTemplate->generate($className, 
            $runner->dynamicActions[$className]['actionArgs']);
        ok($generatedAction);

        $generatedAction->load();
        ok( class_exists( $className ) );
    }
}
