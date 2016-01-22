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
            $tableFields[] = $field;
        }
        return $tableFields;
    }
}