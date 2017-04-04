<?php
/* @var $gen \Nvd\Crud\Commands\Crud */
/* @var $fields [] */
?>
<?='<?php'?>

namespace App;

use Illuminate\Database\Eloquent\Model;

class <?=$gen->modelClassName()?> extends Model {

    public $guarded = ["id","created_at","updated_at"];

    public static function findRequested()
    {
        $query = <?=$gen->modelClassName()?>::query();

        // search results based on user input
        @foreach ( $fields as $field )
\Request::input('{{$field->name}}') and $query->where({!! \Nvd\Crud\Db::getConditionStr($field) !!});
        @endforeach

        // sort results
        \Request::input("sort") and $query->orderBy(\Request::input("sort"),\Request::input("sortType","asc"));

        // paginate results
        return $query->paginate(15);
    }

    public static function validationRules( $attributes = null )
    {
        $rules = [
@foreach ( $fields as $field )
@if( $rule = \Nvd\Crud\Db::getValidationRule( $field ) )
            {!! $rule !!}
@endif
@endforeach
        ];

        // no list is provided
        if(!$attributes)
            return $rules;

        // a single attribute is provided
        if(!is_array($attributes))
            return [ $attributes => $rules[$attributes] ];

        // a list of attributes is provided
        $newRules = [];
        foreach ( $attributes as $attr )
            $newRules[$attr] = $rules[$attr];
        return $newRules;
    }

}
