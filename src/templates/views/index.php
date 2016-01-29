<?php
/* @var $gen \Nvd\Crud\Commands\Crud */
/* @var $fields [] */
?>

@extends('<?=config('crud.layout')?>')

@section('content')

	<h2><?= $gen->titlePlural() ?></h2>

	@include('vendor.crud.common.create-new-link', ['url' => '<?= $gen->route() ?>'])

	<table class="table table-striped grid-view-tbl">
	    
	    <thead>
		<tr class="header-row">
			<?php foreach ( $fields as $field )  { ?>
{!!\Nvd\Crud\Html::sortableTh('<?=$field->name?>','<?= $gen->route() ?>.index','<?=ucwords(str_replace('_',' ',$field->name))?>')!!}
			<?php } ?>
<th></th>
		</tr>
		<tr class="search-row">
			<form class="search-form">
				<?php foreach ( $fields as $field )  { ?>
<td><?=\Nvd\Crud\Db::getSearchInputStr($field)?></td>
				<?php } ?>
<td style="min-width: 6.1em;">@include('vendor.crud.common.search-btn')</td>
			</form>
		</tr>
	    </thead>

	    <tbody>
	    	@forelse ( $records as $record )
		    	<tr>
					<?php foreach ( $fields as $field )  { ?>
<td>{{$record['<?=$field->name?>']}}</td>
					<?php } ?>
@include( 'vendor.crud.common.actions', [ 'url' => '<?= $gen->route() ?>', 'record' => $record ] )
		    	</tr>
			@empty
				@include ('vendor.crud.common.not-found-tr')
	    	@endforelse
	    </tbody>

	</table>

	@include('vendor.crud.common.pagination', [ 'records' => $records ] )

@endsection