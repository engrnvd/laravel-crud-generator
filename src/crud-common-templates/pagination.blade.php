<?php
/* @var $records */
?>
@if(count($records))
    {!! $records->appends(Input::query())->render() !!}
@endif