<?php
namespace Nvd\Crud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Html
{
    /**
     * @param $record Model
     * @param $field
     * @return string
     */
    public static function editable($record, $field )
    {
        $attributes = [
            'class' => "editable",
            'data-type' => "select",
            'data-name' => $field->name,
            'data-value' => $record->{$field->name},
            'data-pk' => $record->{$record->getKeyName()},
            'data-url' => "/person/".$record->{$record->getKeyName()},
        ];

        // source for enum
        if ( $field->type == 'enum' ) // "[{'Male':'Male'},{'Female':'Female'}]"
        {
            $items = [];
            foreach ( $field->enumValues as $value )
                $items[] = "{'$value':'$value'}";
            $attributes['data-source'] = 'data-source="['.join( ',', $items ).']"';
        }

        $output = static::startTag('span', $attributes);
        $output .= $record->{$field->name};
        $output .= static::endTag('span');
        return $output;
    }

    public static function getSourceForEnum($field)
    {
        if ( $field->type == 'enum' ) // "[{'Male':'Male'},{'Female':'Female'}]"
        {
            $items = [];
            foreach ( $field->enumValues as $value )
                $items[] = "{'$value':'$value'}";
            return 'data-source="['.join( ',', $items ).']"';
        }
        return "";
    }

    public static function getInputType($field)
    {
        // textarea
        if( in_array( $field->type, ['text'] ) )
            return 'textarea';

        // dates
        if( $field->type == 'date' )
            return "date";

        // date-time
        if( $field->type == 'datetime' )
            return "datetime";

        // numbers
        if ( in_array( $field->type, ['int','unsigned_int'] ) )
            return "number";

        // emails
        if( preg_match("/email/", $field->name) )
            return "email";

        // enums
        if ( $field->type == 'enum' )
            return 'select';

        // default type
        return 'text';
    }

    public static function sortableTh($fieldName, $route, $label=null )
    {
        $output = "<th>";

        $sortType = "asc";
        if( Request::input("sort") == $fieldName and Request::input("sortType") == "asc" )
            $sortType = "desc";
        $params = array_merge(Request::query(),['sort' => $fieldName, 'sortType' => $sortType]);
        $href = route($route,$params);
        $output .= "<a href='{$href}'>";

        $label = $label?:ucwords( str_replace( "_", " ", $fieldName ) );
        $output .= $label;

        if( Request::input("sort") == $fieldName )
            $output .= " <i class='fa fa-sort-alpha-".Request::input("sortType")."'></i>";

        $output .= "</a>";
        $output .= "</th>";
        return $output;
    }

    public static function selectRequested($name, $options, $attributes = [], $useKeysAsValues = false)
    {
        return static::select($name, $options, $attributes, \Request::input($name), $useKeysAsValues);
    }

    public static function select( $name, $options, $attributes = [], $selectedValue = null, $useKeysAsValues = false )
    {
        $output = static::startTag( "select", array_merge( ['name'=>$name], $attributes ) );
        foreach ( $options as $key => $value ){
            if( $useKeysAsValues )
            {
                $selectedAttr = $key === $selectedValue ? " selected" : "";
                $valueAttr = " value='{$key}'";
            }
            else
            {
                $selectedAttr = $value === $selectedValue ? " selected" : "";
                $valueAttr = "";
            }
            $output .= "<option{$selectedAttr}{$valueAttr}>{$value}</option>";
        }
        $output .= static::endTag("select");
        return $output;
    }

    public static function startTag( $tag, $attributes = [], $inline = false )
    {
        $output = "<{$tag}";
        foreach ( $attributes as $attr => $value ){
            $output .= " {$attr}='{$value}'";
        }
        $output .= $inline ? "/" : "";
        $output .= ">";
        return $output;
    }

    public static function endTag( $tag )
    {
        return "</{$tag}>";
    }

}