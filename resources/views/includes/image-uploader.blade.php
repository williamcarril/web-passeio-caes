<?php
$name = isset($name) ? $name : "image";
$id = isset($id) ? $id : uniqid($name);
$preview = isset($preview) ? $preview : true;
$width = isset($width) ? $width : "100px";
$height = isset($height) ? $height : "100px";
$placeholder = isset($placeholder) ? $placeholder : true;
$icon = isset($icon) ? $icon : true;
?>
<label class="image-uploader" for="{{$id}}" data-action="image-uploader">
    @if($preview)
    <img alt="Picture" src="{{asset("img/picture.png")}}" data-role="preview" width="{{$width}}" height="{{$height}}"/>
    @endif
    @if($icon)
    <i class="glyphicon glyphicon-upload"></i>
    @endif
    @if($placeholder)
    <span data-role="placeholder">
        Selecione a imagem
    </span>
    @endif
    <input id="{{$id}}" name="{{$name}}" type="file" accept=".jpg, .png, .jpeg, .bmp, .tif, .tiff|images/*">
</label>
