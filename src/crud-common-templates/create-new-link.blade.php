<?php
/* @var $url */
/* @var $label */
?>
<p>
    <a href="/{{$url}}/create" class="create-link">
        <i class="fa fa-plus"></i>
        Add a New {{$label or ucwords(str_replace("-"," ", $url))}}
    </a>
</p>