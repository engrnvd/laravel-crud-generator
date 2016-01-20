<?php
namespace Nvd\Crud;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class Html
{
    public static function sortableTh($fieldName, $route, $label=null )
    {
        $label = $label?:ucwords( str_replace( "_", " ", $fieldName ) );
        $sortType = Request::input("sortType") == "asc" ? "desc" : "asc";
        $params = array_merge(Input::query(),['sort' => $fieldName, 'sortType' => $sortType]);
        $href = route($route,$params);

        $output = "<th>";
        $output .= "<a href='{$href}'>";
        $output .= $label;
        $output .= "</a>";
        $output .= "</th>";
        return $output;
    }
}