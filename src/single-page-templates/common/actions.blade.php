<?php
/* @var $url */
/* @var $record */
?>
<td class="actions-cell">
<form class="form-inline" action="/{{$url}}/{{$record->id}}" method="POST">
    <a href="/{{$url}}/{{$record->id}}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;

    <a href="/{{$url}}/{{$record->id}}/edit"><i class="fa fa-pencil-square-o"></i></a>

    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button style="outline: none;background: transparent;border: none;"
            onclick="return confirm('Are You Sure?')"
            type="submit" class="fa fa-trash text-danger"></button>
</form>
</td>