<?php
$title = isset($title) ? $title : "Local";
$urlLocalDetalhes = route('local.detalhes.get', ['slug' => '']);
?>    

@extends("admin.layouts.default", ["hasMap" => true])

@section("title") {{$title}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>{{$title}}</h1>
    <form id="form-manter-local" role="form" method="POST" action="{{route("admin.local.salvar.post")}}" enctype="multpart/form-data">
        {!! csrf_field() !!}
        @if(!empty($local))
        <input type="hidden" name="id" value="{{$local->idLocal}}"/>
        @endif
        <fieldset>
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label class="control-label" for="local-nome">Nome *</label>
                <input tabindex='1' value="{{!empty($local->nome) ? $local->nome : ""}}" required name="nome" id="local-nome" type="text" class="form-control" placeholder="Informe o nome do local">
            </div>
            <div class="form-group">
                <label class="control-label" for="local-slug">Slug *</label>
                <input tabindex='2' value="{{!empty($local->slug) ? $local->slug : ""}}" required name="slug" id="local-slug" type="text" class="form-control" placeholder="Informe o nome do local">
            </div>
            <?php
            $urlFinal = $urlLocalDetalhes . (!empty($local->slug) ? "/{$local->slug}" : "");
            ?>
            <div class="form-group">
                <b>Url seria: </b>
                <span data-role="url-final">{{$urlFinal}}</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="local-descricao">Descrição *</label>
                <textarea id="local-descricao" class="form-control" tabindex='3' name="descricao" placeholder="Informe a descrição do local">{{!empty($local->descricao) ? $local->descricao : ""}}</textarea>
            </div>
        </fieldset>
        <fieldset>
            <legend>Fotos</legend>
            <div class="form-group">
                Parei aqui...
                <ol class="draggable-list" data-action="drag-and-drop">
                    @if(!empty($imagens))
                    @foreach($imagens as $imagem)
                    <li class="image">
                        <input type="hidden" name="idImagem[]" value="{{$imagem->idImagem}}"/>
                        @include("includes.image-uploader", ["image" => $imagem->getUrl(), "imageDescription" => $imagem->descricao, "width" => "100", "height" => "100", "placeholder" => false, "icon" => false, "name" => "imagem[]"])
                    </li>
                    @endforeach
                    @endif
                    <li class="image -new">
                        <input type="hidden" name="idImagem[]" value=""/>
                        @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem[]", "image" => asset("img/picture-black.png")])
                    </li>
                </ol>
            </div>
        </fieldset>
        <?php
        $mapFieldsetData = [
            "idPrefix" => "local",
            "values" => !empty($local) ? $local : null,
            "required" => [
                "numero" => false
            ],
            "tabIndex" => 4,
            "postPrependTabIndex" => 6,
            "prepend" => "<div class='form-group'>"
            . "<label class='control-label' for='local-raio-atuacao'>Raio de atuação (em metros) *</label>"
            . "<input " . (!empty($local) ? "" : "disabled") . " type='number' "
            . "min='0' tabindex='5' id='local-raio-atuacao' "
            . "class='form-control' name='raioAtuacao' "
            . "placeholder='Informe o raio de atuação do local (em metros)' "
            . "value='" . (!empty($local->raioAtuacao) ? $local->raioAtuacao : "") . "'/>"
            . "</div>",
            "postMapLoad" => "drawActionRadius"
        ];
        ?>
        @include("includes.form.map-fieldset", $mapFieldsetData)
        <button tabindex="11" type="submit" class="btn btn-default btn-lg pull-right">Salvar</button>
    </form>
</section>
@endsection

@section("templates")
<li class="image -new" data-template="nova-imagem">
    <input type="hidden" name="idImagem[]" value=""/>
    @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem[]", "image" => asset("img/picture-black.png")])
</li>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
(function () {
    var $form = $("#form-manter-local");
    var $slug = $form.find("input[name='slug']");
    var $urlFinal = $form.find("[data-role='url-final']")
    var urlSemSlug = "{!! $urlLocalDetalhes !!}";
    @if(!empty($ignoreOnChecks))
        var ignoreOnChecks = $.parseJSON('{!! json_encode($ignoreOnChecks) !!}');
    @else
        var ignoreOnChecks = {};
    @endif
    $form.on("blur", "input[name='nome']", function (ev) {
        var $this = $(this);
        var nome = $this.val();
        if (validate.empty(nome)) {
            setInputStatus($this, "error");
            return;
        }
        $this.ajaxValidation(
            "{!!route('admin.local.check.nome.get')!!}",
            "GET",
            {"nome": nome, "ignore": ignoreOnChecks.nome || null},
            null,
            function(response) {
                if (response.status) {
                    setInputStatus($this, "error");
                    showAlert("O nome informado já está sendo utilizado.", "error");
                } else {
                    setInputStatus($this, "success");
                    if (!$slug.val()) {
                        $slug.val(nome.slugify());
                        $slug.trigger("change");
                    }
                }
            }
        );
    });
    $form.on("blur change", "input[name='slug']", function(ev) {
        var $this = $(this);
        var slug = $this.val();
        var urlFinal = urlSemSlug + "/" + slug;
        $urlFinal.text(urlFinal);
        if (validate.empty(slug)) {
            setInputStatus($this, "error");
            return;
        }
        $this.ajaxValidation(
            "{!!route('admin.local.check.slug.get')!!}",
            "GET",
            {"slug": slug, "ignore": ignoreOnChecks.slug || null},
            "O slug informado já está sendo utilizado."
        );
    });
    $form.find("textarea[name='descricao'],input[name='raioAtuacao']").validate("empty", null, "blur");
    $form.find("input[name='raioAtuacao']").on("change", function(ev) {
        desenharRaioDeAtuacao($(this).val());
    });
    window.drawActionRadius = function() {
        @if(!empty($local->raioAtuacao))
        desenharRaioDeAtuacao("{!!$local->raioAtuacao!!}");
        @endif
    };
    function desenharRaioDeAtuacao(tamanho) {
        var map = globals.maps["local-map"];
        var marker = map.markers[0];
        if (map.circles) {
            for (var i = 0; i < map.circles.length; i++) {
                map.circles[i].setMap(null);
            }
        }
        map.circles = [];
        map.circles.push(new google.maps.Circle({
            strokeColor: '#F75448',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#F75448',
            fillOpacity: 0.35,
            map: map,
            center: marker.position,
            radius: parseFloat(tamanho)
        }));
        map.fitBounds(map.circles[0].getBounds());
    }

    $form.defaultAjaxSubmit("{!! route('admin.local.listagem.get') !!}");
})();
</script>
@endsection