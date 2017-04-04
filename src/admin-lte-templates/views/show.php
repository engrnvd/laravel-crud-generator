<?php
/* @var $gen \Nvd\Crud\Commands\Crud */
/* @var $fields [] */
?>
@extends('<?=config('crud.layout')?>')

@section('content')

    <h2><?= $gen->titleSingular() ?>: {{$<?= $gen->modelVariableName() ?>-><?=array_values($fields)[1]->name?>}}</h2>

    <ul class="list-group">

        <?php foreach ( $fields as $field )  { ?>
<li class="list-group-item">
            <h4><?=ucwords(str_replace('_',' ',$field->name))?></h4>
            <h5>{{$<?= $gen->modelVariableName() ?>-><?=$field->name?>}}</h5>
        </li>
        <?php } ?>

    </ul>

@endsection