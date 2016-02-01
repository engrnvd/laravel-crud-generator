<?php
/* @var $records */
?>
@if(count($records))
    {!! $records->appends(Request::query())->render() !!}
@endif