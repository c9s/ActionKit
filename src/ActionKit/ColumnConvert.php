<?php
namespace ActionKit;
use ActionKit\Param;

/**
 * Convert LazyORM column to Action param, 
 * so that we can render with widget (currently).
 */
class ColumnConvert 
{

    static function toParam( $column , $record = null )
    {
        $name = $column->name;

        $param = new Param( $name );

        foreach( $column->attributes as $k => $v ) {
            $param->$k = $v;
        }

        $param->name  = $name;

        // load record value
        if( $record ) {
            $param->value = $record->{$name};
        }

        /**
         * Convert column type to param type
         *
         * set default render widget
         */
        if( $param->validValues || $param->validPairs ) {
            $param->renderAs( 'SelectInput' );
        } elseif( $param->name === 'id' ) {
            $param->renderAs( 'HiddenInput' );
        } elseif( $param->renderAs ) {
            $param->renderAs($param->renderAs );
        } else {
            // guess input widget from data type
            $typeMapping = array(
                'date' => 'DateInput',
                'datetime' => 'DateTimeInput',
                'text' => 'TextareaInput',
            );
            if( isset($typeMapping[ $param->type ]) ) {
                $widgetType = $typeMapping[$param->type];
                $param->renderAs($widgetType);
            } else {
                $param->renderAs( 'TextInput' );
            }
        }
        return $param;
    }


}



