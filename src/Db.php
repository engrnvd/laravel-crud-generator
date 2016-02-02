<?php
/**
 * Created by naveedulhassan.
 * Date: 1/22/16
 * Time: 2:55 PM
 */

namespace Nvd\Crud;


class Db
{
    public static function fields($table)
    {
        $columns = \DB::select('show fields from '.$table);
        $tableFields = array(); // return value
        foreach ($columns as $column) {
            $column = (array)$column;
            $field = new \stdClass();
            $field->name = $column['Field'];
            $field->defValue = $column['Default'];
            $field->required = $column['Null'] == 'NO';
            $field->key = $column['Key'];
            // type and length
            $field->maxLength = 0;// get field and type from $res['Type']
            $type_length = explode( "(", $column['Type'] );
            $field->type = $type_length[0];
            if( count($type_length) > 1 ) { // some times there is no "("
                $field->maxLength = (int)$type_length[1];
                if( $field->type == 'enum' ) { // enum has some values  'Male','Female')
                    $matches = explode( "'", $type_length[1] );
                    foreach($matches as $match) {
                        if( $match && $match != "," && $match != ")" ){ $field->enumValues[] = $match; }
                    }
                }
            }
            // everything decided for the field, add it to the array
            $tableFields[$field->name] = $field;
        }
        return $tableFields;
    }

    public static function getConditionStr($field)
    {
        if( in_array( $field->type, ['varchar','text'] ) )
            return "'{$field->name}','like','%'.\Request::input('{$field->name}').'%'";
        return "'{$field->name}',\Request::input('{$field->name}')";
    }

    public static function getValidationRule($field)
    {
        // skip certain fields
        if ( in_array( $field->name, static::skippedFields() ) )
            return "";

        $rules = [];
        // required fields
        if( $field->required )
            $rules[] = "required";

        // strings
        if( in_array( $field->type, ['varchar','text'] ) )
        {
            $rules[] = "string";
            if ( $field->maxLength ) $rules[] = "max:".$field->maxLength;
        }

        // dates
        if( in_array( $field->type, ['date','datetime'] ) )
            $rules[] = "date";

        // numbers
        if ( in_array( $field->type, ['int','unsigned_int'] ) )
            $rules [] = "integer";

        // emails
        if( preg_match("/email/", $field->name) ){ $rules[] = "email"; }

        // enums
        if ( $field->type == 'enum' )
            $rules [] = "in:".join( ",", $field->enumValues );

        return "'".$field->name."' => '".join( "|", $rules )."',";
    }

    protected static function skippedFields()
    {
        return ['id','created_at','updated_at'];
    }

    public static function isGuarded($fieldName)
    {
        return in_array( $fieldName, static::skippedFields() );
    }

    public static function getSearchInputStr ( $field )
    {
        // selects
        if ( $field->type == 'enum' )
        {
            $output = "{!!\Nvd\Crud\Html::selectRequested(\n";
            $output .= "\t\t\t\t\t'".$field->name."',\n";
            $output .= "\t\t\t\t\t[ '', '".join("', '",$field->enumValues)."' ],\n"; //Yes', 'No
            $output .= "\t\t\t\t\t['class'=>'form-control']\n";
            $output .= "\t\t\t\t)!!}";
            return $output;
        }

        // input type:
        $type = 'text';
        if ( $field->type == 'date' ) $type = $field->type;
        $output = '<input type="'.$type.'" class="form-control" name="'.$field->name.'" value="{{Request::input("'.$field->name.'")}}">';
        return $output;

    }

    public static function getFormInputMarkup ( $field, $modelName = '' )
    {
        // skip certain fields
        if ( in_array( $field->name, static::skippedFields() ) )
            return "";

        // string that binds the model
        $modelStr = $modelName ? '->model($'.$modelName.')' : '';

        // selects
        if ( $field->type == 'enum' )
        {
            return "{!! \Nvd\Crud\Form::select( '{$field->name}', [ '".join("', '",$field->enumValues)."' ] ){$modelStr}->show() !!}";
        }

        if ( $field->type == 'text' )
        {
            return "{!! \Nvd\Crud\Form::textarea( '{$field->name}' ){$modelStr}->show() !!}";
        }

        // input type:
        $type = 'text';
        if ( $field->type == 'date' ) $type = $field->type;
        return "{!! \Nvd\Crud\Form::input('{$field->name}','{$type}'){$modelStr}->show() !!}";
    }

}