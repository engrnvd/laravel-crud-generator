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
        //$columns = \DB::select('show fields from '.$table);

/*This query was change for wrok with PostgreSql*/
//SQL para generacion con postgres
    $columns = \DB::select("SELECT  
    f.attnum AS number,  
    f.attname AS field,  
    pg_catalog.format_type(f.atttypid,f.atttypmod) AS type,  

    CASE  
        WHEN f.attnotnull = 't' THEN 'YES'  
    WHEN f.attnotnull = 'f' THEN 'NO'  
    END AS null,  


    CASE  
        WHEN p.contype = 'p' THEN 'PRI'  
    WHEN p.contype = 'u' THEN 'UNI'
    WHEN p.contype = 'f' THEN 'FK'
        ELSE ''  
    END AS key,  
   
    CASE
        WHEN f.atthasdef = 't' THEN d.adsrc
    END AS default

FROM pg_attribute f  
    JOIN pg_class c ON c.oid = f.attrelid  
    JOIN pg_type t ON t.oid = f.atttypid  
    LEFT JOIN pg_attrdef d ON d.adrelid = c.oid AND d.adnum = f.attnum  
    LEFT JOIN pg_namespace n ON n.oid = c.relnamespace  
    LEFT JOIN pg_constraint p ON p.conrelid = c.oid AND f.attnum = ANY (p.conkey)  
    LEFT JOIN pg_class AS g ON p.confrelid = g.oid  
WHERE c.relkind = 'r'::char  
    AND n.nspname = 'public'  -- Replace with Schema name  
    AND c.relname = '$table'  -- Replace with table name  
    AND f.attnum > 0 ORDER BY number;");
/*This query was change for wrok with PostgreSql*/


        $tableFields = array(); // return value
        foreach ($columns as $column) {
            $column = (array)$column;
            $field = new \stdClass();
            $field->name = $column['field'];
            $field->defValue = $column['default'];
            $field->required = $column['null'] == 'NO';
            $field->key = $column['key'];
            // type and length
            $field->maxLength = 0;// get field and type from $res['Type']
            $type_length = explode( "(", $column['type'] );
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
   /*The fields type was change for PostgreSql*/
    public static function getConditionStr($field)
    {
        if( in_array( $field->type, ['character varying','text'] ) )
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
        if( in_array( $field->type, ['character varying','text'] ) )
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
