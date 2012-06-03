<?php
namespace ActionKit\RecordAction;
use Exception;
use ActionKit\Action;
use ActionKit\ActionGenerator;

/*
    use ActionKit\RecordAction;

    $a = new User\Action\UpdateUser(array( 'field' => 'new_data' ) , $record );
    $a->run();

Generate CreateRecordAction

    $createAction = BaseRecordAction::generate( 'RecordName' , 'Create' );

Generate UpdateRecordAction

    $updateAction = BaseRecordAction::generate( 'RecordName' , 'Update' );


    XXX: validation should be built-in in Model

*/
abstract class BaseRecordAction extends Action
{
    const TYPE = 'base';

    public $record; // record schema object

    public $recordClass;


    public function __construct( $args = array(), $record = null, $currentUser = null ) 
    {
        // record name is in Camel case
        $class = $this->recordClass;
        $this->record = $record ? $record : new $class;
        $this->initRecord();

        /* run schema , init base action stuff */
        parent::__construct( $args , $currentUser );
        if( ! $this->recordClass ) {
            throw new Exception( sprintf('Record class of "%s" is not specified.' , get_class($this) ));
        }
    }

    protected function useRecordSchema()
    {
        $this->initRecordColumn();
    }


    /**
     * Load record from arguments (by id)
     */
    function initRecord() 
    {
        if( isset( $this->args['id'] ) && ! $this->record->id ) {
            $this->record->load( $this->args['id'] );
        }
    }

    /**
     * Convert model columns to action columns 
     */
    function initRecordColumn()
    {
        if( ! $this->record )
            return;
        foreach( $this->record->getColumns() as $column ) {
            if( ! isset($this->params[$column->name] ) ) {
                $this->params[ $column->name ] = \ActionKit\ColumnConvert::toParam( $column , $this->record );
            }
        }
    }


    public function schema() 
    {
        $this->useRecordSchema();
    }

    function getType() 
    {
        return static::TYPE;
    }

    function getRecord() 
    {
        return $this->record; 
    }

    function setRecord($record)
    {
        $this->record = $record;
    }

    function currentUserCan( $user )
    {
        return true;
    }


    /**
     * Convert record validation object to action validation result.
     *
     * @param LazyRecord\OperationResult $ret
     */
    function convertRecordValidation( $ret ) 
    {
        if( $ret->validations ) {
            foreach( $ret->validations as $vld ) {
                if( $vld->success ) {
                    $this->result->addValidation( $vld->field , array( "valid" => $vld->message )); 
                } else {
                    $this->result->addValidation( $vld->field , array( "invalid" => $vld->message ));
                }
            }
        }
    }



    /**
     * Create CRUD class
     *
     * @param string $recordClass
     * @param string $type
     *
     * @return string class code
     */
    static function createCRUDClass( $recordClass , $type ) 
    {
        $gen = new ActionGenerator(array( 'cache' => true ));
        $ret = $gen->generateClassCode( $recordClass , $type );

        if( class_exists($ret->action_class,true) ) {
            return $ret->action_class;
        }
        eval( $ret->code );
        return $ret->action_class;
    }

}

