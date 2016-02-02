<?php
/* @var $gen \Nvd\Crud\Commands\Crud */
/* @var $fields [] */
?>

@extends('<?=config('crud.layout')?>')

@section('content')

    <h2>Update <?=$gen->titleSingular()?>: {{$<?=$gen->modelVariableName()?>-><?=array_values($fields)[1]->name?>}}</h2>

    <form action="/<?=$gen->route()?>/{{$<?=$gen->modelVariableName()?>->id}}" method="post">

        {{ csrf_field() }}

        {{ method_field("PUT") }}
<?php foreach ( $fields as $field )  { ?>
<?php if( $str = \Nvd\Crud\Db::getFormInputMarkup( $field, $gen->modelVariableName() ) ) { ?>

        <?=$str?>

<?php } ?>
<?php } ?>

        <button type="submit" class="btn btn-default">Submit</button>

    </form>

@endsection