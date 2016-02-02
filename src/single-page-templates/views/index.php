<?php
/* @var $gen \Nvd\Crud\Commands\Crud */
/* @var $fields [] */
?>

@extends('<?=config('crud.layout')?>')

@section('content')

	<h2><?= $gen->titlePlural() ?></h2>

	@include('<?=$gen->viewsDirName()?>.create')

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
<td style="min-width: 6em;">@include('<?=$gen->templatesDir()?>.common.search-btn')</td>
			</form>
		</tr>
	    </thead>

	    <tbody>
	    	@forelse ( $records as $record )
		    	<tr>
					<?php foreach ( $fields as $field )  { ?>
<td>
						<?php if( !\Nvd\Crud\Db::isGuarded($field->name) ) {?>
<span class="editable"
							  data-type="<?=\Nvd\Crud\Html::getInputType($field)?>"
							  data-name="<?=$field->name?>"
							  data-value="{{ $record-><?=$field->name?> }}"
							  data-pk="{{ $record->{$record->getKeyName()} }}"
							  data-url="/<?=$gen->route()?>/{{ $record->{$record->getKeyName()} }}"
							  <?=\Nvd\Crud\Html::getSourceForEnum($field)?>>{{ $record-><?=$field->name?> }}</span>
						<?php } else { ?>
{{ $record-><?=$field->name?> }}
						<?php } ?>
</td>
					<?php } ?>
@include( '<?=$gen->templatesDir()?>.common.actions', [ 'url' => '<?= $gen->route() ?>', 'record' => $record ] )
		    	</tr>
			@empty
				@include ('<?=$gen->templatesDir()?>.common.not-found-tr',['colspan' => <?=count($fields)+1?>])
	    	@endforelse
	    </tbody>

	</table>

	@include('<?=$gen->templatesDir()?>.common.pagination', [ 'records' => $records ] )

<script>
	$(".editable").editable({ajaxOptions:{method:'PUT'}});
</script>
@endsection