<?php
$type = !empty($type) ? $type : "";
$message = !empty($message) ? $message : "";
$class = null;
switch ($type) {
    case "success":
        $class = "success";
        break;
    case "error":
        $class = "danger";
        break;
    case "warning":
        $class = "warning";
        break;
    case "info":
    default:
        $class = "info";
        break;
}
?>
<div class="alert alert-{{$class}} alert-dismissible" role="alert" {{!empty($name) ? "data-name=$name" : ""}}>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{$message}}
</div>
