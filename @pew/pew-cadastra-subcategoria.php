<?php
if(isset($_POST["id_categoria"])){
    $idCategoria = $_POST["id_categoria"];
?>
<h2 class=titulo-edita>Cadastrar subcategoria</h2>
<form id='formCadCategoria' method="post" action="pew-grava-subcategoria.php" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $idCategoria;?>" id="idCategoria" name="id_categoria">
    <div class='medium'>
        <h3 class="label-title">Título</h3>
        <input type='text' class='label-input' placeholder='Título da subcategoria' name='titulo' id='titulSubcategoria' maxlength='35'>
    </div>
	<div class='large'>
        <h3 class="label-title">Imagem</h3>
        <input type='file' name='imagem' accept='image/*' class="label-input">
    </div>
    <div class='label full'>
        <h3 class="label-title">Descrição (opcional, recomendado 156 caracteres)</h3>
        <textarea class='label-textarea' placeholder='Descrição da subcategoria SEO Google' name='descricao' id='descricaoSubcategoria'></textarea>
    </div>
    <div class='label small clear'>
        <input type='submit' class='btn-submit label-input js-button-submit' value='Cadastrar'>
    </div>
    <br class="clear">
</form>
<?php
}else{
    echo "<h3 align=center><br>Ocorreu um erro ao carregar os dados. Recarregue a página e tente novamente.</h3>";
}
?>
<script>
    $(document).ready(function(){
        var formCadastra = $("#formCadCategoria");
        $("#titulSubcategoria").focus();
        $(".js-button-submit").off().on("click", function(event){
            event.preventDefault();
            var objTitulo = $("#titulSubcategoria");
            var objDescricao = $("#descricaoSubcategoria");
            var idCategoria = $("#idCategoria").val();
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
