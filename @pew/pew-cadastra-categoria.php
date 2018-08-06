<h2 class=titulo-edita>Cadastrar categoria</h2>
<form id='formCadCategoria' method="post" action="pew-grava-categoria.php" enctype="multipart/form-data">
    <div class='full'>
        <h3 class="label-title">Título</h3>
        <input type='text' class='label-input' placeholder='Título da categoria' name='titulo' id='tituloCategoria' maxlength='35'>
    </div>
	<div class='half'>
        <h3 class="label-title">Imagem (1200px : 510px)</h3>
        <input type='file' name='imagem' accept='image/*' class="label-input">
    </div>
	<div class='half'>
        <h3 class="label-title">Ícone</h3>
        <input type='file' name='icone' accept='image/*' class="label-input">
    </div>
    <div class='full clear'>
        <h3 class="label-title">Descrição (opcional, recomendado 156 caracteres)</h3>
        <textarea class='label-textarea' placeholder='Descrição da categoria SEO Google' name='descricao' id='descricaoCategoria' rows="3"></textarea>
    </div>
	<div class="small">
		<input type='submit' class='btn-submit label-input js-button-submit' value='Cadastrar'>
	</div>
</form>
<script>
    $(document).ready(function(){
        var formCadastra = $("#formCadCategoria");
        $("#tituloCategoria").focus();
        $(".js-button-submit").off().on("click", function(event){
            event.preventDefault();
            var objTitulo = $("#tituloCategoria");
            var objDescricao = $("#descricaoCategoria");
            var titulo = objTitulo.val();
            var descricao = objDescricao.val();
            if(titulo.length < 3){
                mensagemAlerta("O campo Título deve conter no mínimo 3 caracteres.", objTitulo);
                return false;
            }
            formCadastra.submit();
        });
    });
</script>
