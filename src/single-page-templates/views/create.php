<?php
/* @var $gen \Nvd\Crud\Commands\Crud */
/* @var $fields [] */
?>
<div class="panel-group col-md-6 col-sm-12" id="accordion" style="padding-left: 0">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <i class="fa fa-plus"></i>
                    Add a New <?=$gen->titleSingular()?>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">

                <form action="/<?=$gen->route()?>" method="post">

                    {{ csrf_field() }}
<?php foreach ( $fields as $field )  { ?>
<?php if( $str = \Nvd\Crud\Db::getFormInputMarkup($field) ) { ?>

                    <?=$str?>

<?php } ?>
<?php } ?>

                    <button type="submit" class="btn btn-primary">Create</button>

                </form>

            </div>
        </div>
    </div>
</div>