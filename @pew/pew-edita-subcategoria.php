<?php
    $post_fileds = array("id_subcategoria", "id_categoria");
    $invalid_fileds = array();
    $carregar = true;
    $i = 0;
    foreach($post_fileds as $post_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_POST[$post_name])){
            $carregar = false;
            $i++;
            $invalid_fileds[$i] = $post_name;
        }
    }
    function loadingError(){
        /*Se algo deu errado essa função é executada*/
        echo "<h3 align='center'>Ocorreu um erro ao carregar os dados. Recarregue a página e tente novamente.</h3>";
    }

    if($carregar){
        $idSubcategoria = $_POST["id_subcategoria"];
        $idCategoria = $_POST["id_categoria"];
        require_once "pew-system-config.php";
        $tabela_subcategorias = $pew_db->tabela_subcategorias;
        $contarSubcategoria = mysqli_query($conexao, "select count(id) as total_subcategoria from $tabela_subcategorias where id = '$idSubcategoria'");
        $contagem = mysqli_fetch_assoc($contarSubcategoria);
        $totalSubcategoria = $contagem["total_subcategoria"];
		$dirImagens = "../imagens/categorias/subcategorias";
        if($totalSubcategoria > 0){
            $querySubcategoria = mysqli_query($conexao, "select * from $tabela_subcategorias where id = '$idSubcategoria'");
            $subcategoria = mysqli_fetch_array($querySubcategoria);
            $titulo = $subcategoria["subcategoria"];
            $descricao = $subcategoria["descricao"];
            $imagem = $subcategoria["imagem"];
            $dataControle = $pew_functions->inverter_data(substr($subcategoria["data_controle"], 0, 10));
            $status = $subcategoria["status"] == 1 ? "Ativa" : "Desativada";
            echo "<h2 class=titulo-edita>Informações da subcategoria</h2>";
            echo "<form id='formUpdateCategoria' method='post' action='pew-update-subcategoria.php' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='id_categoria' id='idCategoria' value='$idCategoria'>";
                echo "<input type='hidden' name='id_subcategoria' id='idSubcategoria' value='$idSubcategoria'>";
                echo "<div class='label full'>";
                    echo "<h3 class='label-title'>Título</h3>";
                    echo "<input type='text' class='label-input' placeholder='Título da categoria' name='titulo' id='tituloCategoria' value='$titulo' maxlength='35'>";
                echo "</div>";
                echo "<div class='label full'>";
                    echo "<h3 class='label-title'>Descrição (opcional, recomendado 156 caracteres)</h3>";
                    echo "<textarea class='label-textarea' placeholder='Descrição da subcategoria SEO Google' name='descricao' id='descricaoCategoria'>$descricao</textarea>";
                echo "</div>";
				echo "<br class='clear'>";
                if($imagem != "" && file_exists($dirImagens."/".$imagem)){
                    echo "<div class='medium'>";
                        echo "<h3 class='label-title'>Imagem atual</h3>";
                        echo "<img src='$dirImagens/$imagem' style='margin: 0px; width: 100%;'>";
                    echo "</div>";
                }
                echo "<div class='large'>";
                    echo "<h3 class='label-title'>Atualizar imagem (1280px : 570px)</h3>";
                    echo "<input type='file' accept='image/*' name='imagem' class='label-input'>";
                echo "</div>";
                echo "<br class='clear'>";
                echo "<div class='label small'>";
                    echo "<h3 class='label-title'>Status</h3>";
                    echo "<select name='status' id='statusCategoria' class='label-input'>";
                        $possibleStatus = array("Ativa", "Desativada");
                        foreach($possibleStatus as $optionStatus){
                            $selected = $optionStatus == $status ? "selected" : "";
                            $value = $optionStatus == "Ativa" ? 1 : 0;
                            echo "<option $selected value='$value'>$optionStatus</option>";
                        }
                    echo "</select>";
                echo "</div>";
                echo "<div class='label large'>";
                    echo "<h3>Última modificação: $dataControle</h3>";
                echo "</div>";
                echo "<div class='group clear'>";
                    echo "<div class='label small'>";
                        echo "<input type='button' class='btn-excluir botao-acao label-input' pew-acao='deletar' pew-id-categoria='$idSubcategoria' value='Excluir'>";
                    echo "</div>";
                    echo "<div class='label small'>";
                        echo "<input type='submit' class='btn-submit label-input js-button-submit' value='Atualizar'>";
                    echo "</div>";
                echo "</div>";
            echo "</form>";
        }else{
            loadingError();
        }
    }else{
        loadingError();
    }
?>
<script>
    $(document).ready(function(){
        var formUpdate = $("#formUpdateCategoria");
        var idCategoria = $("#idCategoria").val();
        $(".js-button-submit").off().on("click", function(event){
            event.preventDefault();
            var objId = $("#idSubcategoria");
            var objTitulo = $("#tituloCategoria");
            var objDescricao = $("#descricaoCategoria");
            var objStatus = $("#statusCategoria");
            var idSubcategoria = objId.val();
            var titulo = objTitulo.val();
            var descricao = objDescricao.val();
            var status = objStatus.val();
            if(titulo.length < 3){
                mensagemAlerta("O campo Título deve conter no mínimo 3 caracteres.", objTitulo);
                return false;
            }
            formUpdate.submit();
        });
        $(".botao-acao").each(function(){
            var botao = $(this);
            var acao = botao.attr("pew-acao");
            var idSubcategoria = botao.attr("pew-id-categoria");
            var msgErro = "Não foi possível excluir a subcategoria. Recarregue a página e tente novamente.";
            var msgSucesso = "A subcategoria foi excluida com sucesso!";
            function excluir(){
                $.ajax({
                    type: "POST",
                    url: "pew-deleta-subcategoria.php",
                    data: {id_subcategoria: idSubcategoria, acao: acao},
                    error: function(){
                        mensagemAlerta(msgErro);
                    },
                    success: function(resposta){
                        console.log(resposta);
                        if(resposta == "true"){
                            mensagemAlerta(msgSucesso, false,"#259e25", "pew-categorias.php?subfocus=&id_categoria="+idCategoria);
                        }else{
                            mensagemAlerta(msgErro);
                        }
                    }
                });
            }
            botao.off().on("click", function(){
                mensagemConfirma("Você tem certeza que deseja excluir esta subcategoria?", excluir);
            });
        });
    });
</script>
