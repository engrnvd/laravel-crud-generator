<?php
namespace Nvd\Crud;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class Html
{
    public static function sortableTh($fieldName, $route, $label=null )
    {
        $output = "<th>";

        $sortType = "asc";
        if( Request::input("sort") == $fieldName and Request::input("sortType") == "asc" )
            $sortType = "desc";
        $params = array_merge(Input::query(),['sort' => $fieldName, 'sortType' => $sortType]);
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

    public static function startTag( $tag, $attributes = [] )
    {
        $output = "<{$tag}";
        foreach ( $attributes as $attr => $value ){
            $output .= " {$attr}='{$value}'";
        }
        $output .= ">";
        return $output;
    }

    public static function endTag( $tag )
    {
        return "</{$tag}>";
    }

}