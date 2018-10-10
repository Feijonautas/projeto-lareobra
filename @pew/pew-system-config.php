<?php
    /*CLASSE PRINCIPAL DO SISTEMA*/

    if(!class_exists("Pew_Data_Base")){
        class Pew_Data_Base{
            public $db_host;
            public $db_name;
            public $db_user;
            public $db_pass;
            public $tabela_banners;
            public $tabela_categorias;
            public $tabela_subcategorias;
            public $tabela_contatos;
            public $tabela_usuarios_administrativos;

            function __construct($database_host, $database_user, $database_pass, $database_name, $tb_banners, $tb_categorias, $tb_subcategorias, $tb_contatos, $tb_usuarios_administrativos){
                $this->db_host = $database_host;
                $this->db_name = $database_name;
                $this->db_user = $database_user;
                $this->db_pass = $database_pass;
                $this->tabela_banners = $tb_banners;
                $this->tabela_categorias = $tb_categorias;
                $this->tabela_subcategorias = $tb_subcategorias;
                $this->tabela_contatos = $tb_contatos;
                $this->tabela_usuarios_administrativos = $tb_usuarios_administrativos;
            }
        }
    }
    $pew_db = new Pew_Data_Base("mysql1.lareobra.com.br", "lareobra1", "admlareobra@123", "lareobra1", "pew_banners", "pew_categorias", "pew_subcategorias", "pew_contatos", "pew_usuarios_administrativos");
    /*$pew_db = new Pew_Data_Base("localhost", "root", "", "pew_lareobra", "pew_banners", "pew_categorias", "pew_subcategorias", "pew_contatos", "pew_usuarios_administrativos");*/
    $conexao = mysqli_connect($pew_db->db_host, $pew_db->db_user, $pew_db->db_pass, $pew_db->db_name);
    /*CLASSE PRINCIPAL DO SISTEMA*/

    /*CLASSE TABELAS CUSTOMIZADAS ADICIONAIS*/
    if(!class_exists("Pew_Custom_Data_Base")){
        class Pew_Custom_Data_Base{
            public $tabela_produtos;
            public $tabela_marcas;
            public $tabela_marcas_produtos;
            public $tabela_cores;
            public $tabela_imagens_produtos;
            public $tabela_departamentos;
            public $tabela_departamentos_produtos;
            public $tabela_categorias_produtos;
            public $tabela_subcategorias_produtos;
            public $tabela_orcamentos;
            public $tabela_config_orcamentos;
            public $tabela_categorias_vitrine;
            public $tabela_categoria_destaque;
            public $tabela_especificacoes;
            public $tabela_especificacoes_produtos;
            public $tabela_produtos_relacionados;
            public $tabela_cores_relacionadas;
            public $tabela_newsletter;
            public $tabela_minha_conta;
            public $tabela_enderecos;
            public $tabela_links_menu;
            public $tabela_dicas;
            public $tabela_carrinhos;
            public $tabela_pedidos;
            public $tabela_pedidos_observacoes;
            public $tabela_contatos_servicos;
            public $tabela_notificacoes;
            public $tabela_views_notificacoes;
            public $tabela_promocoes;
            public $tabela_clube_descontos;
            public $tabela_clube_descontos_pontos;
            public $tabela_franquias;
            public $tabela_franquias_produtos;
            public $tabela_franquias_solicitacoes;
            public $tabela_cupons_utilizados;
            public $tabela_transporte_franquias;
            public $tabela_log_franquias;

            function __construct($tb_produtos, $tb_marcas, $tb_marcas_produtos, $tb_cores, $tb_imagens_produtos, $tb_departamentos, $tb_departamentos_produtos, $tb_categorias_produtos, $tb_subcategorias_produtos, $tb_orcamentos, $tb_config_orcamentos, $tb_categorias_vitrine, $tb_categoria_destaque, $tb_especificacoes, $tb_especificacoes_produtos, $tb_produtos_relacionados, $tb_cores_relacionadas, $tb_newsletter, $tb_minha_conta, $tb_enderecos, $tb_links_menu, $tb_dicas, $tb_carrinhos, $tb_pedidos, $tb_pedidos_observacoes, $tb_contatos_servicos, $tb_notificacoes, $tb_views_notificacoes, $tb_promocoes, $tb_clube_descontos, $tb_clube_descontos_pontos, $tb_franquias, $tb_franquias_produtos, $tb_franquias_solicitacoes, $tb_cupons_utilizados, $tb_transporte_franquias, $tb_log_franquias){
                $this->tabela_produtos = $tb_produtos;
                $this->tabela_marcas = $tb_marcas;
                $this->tabela_marcas_produtos = $tb_marcas_produtos;
                $this->tabela_cores = $tb_cores;
                $this->tabela_imagens_produtos = $tb_imagens_produtos;
                $this->tabela_departamentos = $tb_departamentos;
                $this->tabela_departamentos_produtos = $tb_departamentos_produtos;
                $this->tabela_categorias_produtos = $tb_categorias_produtos;
                $this->tabela_subcategorias_produtos = $tb_subcategorias_produtos;
                $this->tabela_orcamentos = $tb_orcamentos;
                $this->tabela_config_orcamentos = $tb_config_orcamentos;
                $this->tabela_categorias_vitrine = $tb_categorias_vitrine;
                $this->tabela_categoria_destaque = $tb_categoria_destaque;
                $this->tabela_especificacoes = $tb_especificacoes;
                $this->tabela_especificacoes_produtos = $tb_especificacoes_produtos;
                $this->tabela_produtos_relacionados = $tb_produtos_relacionados;
                $this->tabela_cores_relacionadas = $tb_cores_relacionadas;
                $this->tabela_newsletter = $tb_newsletter;
                $this->tabela_minha_conta = $tb_minha_conta;
                $this->tabela_enderecos = $tb_enderecos;
                $this->tabela_links_menu = $tb_links_menu;
                $this->tabela_dicas = $tb_dicas;
                $this->tabela_carrinhos = $tb_carrinhos;
				$this->tabela_pedidos = $tb_pedidos;
				$this->tabela_pedidos_observacoes = $tb_pedidos_observacoes;
				$this->tabela_contatos_servicos = $tb_contatos_servicos;
				$this->tabela_notificacoes = $tb_notificacoes;
				$this->tabela_views_notificacoes = $tb_views_notificacoes;
				$this->tabela_promocoes = $tb_promocoes;
				$this->tabela_clube_descontos = $tb_clube_descontos;
				$this->tabela_clube_descontos_pontos = $tb_clube_descontos_pontos;
				$this->tabela_franquias = $tb_franquias;
				$this->tabela_franquias_produtos = $tb_franquias_produtos;
				$this->tabela_franquias_solicitacoes = $tb_franquias_solicitacoes;
				$this->tabela_cupons_utilizados = $tb_cupons_utilizados;
				$this->tabela_transporte_franquias = $tb_transporte_franquias;
				$this->tabela_log_franquias = $tb_log_franquias;
			}
        }
    }
    $pew_custom_db = new Pew_Custom_Data_Base("pew_produtos", "pew_marcas", "pew_marcas_produtos", "pew_cores", "pew_imagens_produtos", "pew_departamentos", "pew_departamentos_produtos", "pew_categorias_produtos", "pew_subcategorias_produtos", "pew_orcamentos", "pew_config_orcamentos", "pew_categorias_vitrine", "pew_categoria_destaque", "pew_especificacoes_tecnicas", "pew_especificacoes_produtos", "pew_produtos_relacionados", "pew_cores_relacionadas", "pew_newsletter", "pew_minha_conta", "pew_enderecos", "pew_links_menu", "pew_dicas", "pew_carrinhos", "pew_pedidos", "pew_pedidos_observacoes", "pew_contatos_servicos", "pew_notificacoes",  "pew_views_notificacoes", "franquias_promocoes", "clube_descontos", "clube_descontos_pontos", "franquias_lojas", "franquias_produtos", "franquias_requisicoes", "pew_cupons_utilizados", "franquias_transportes", "franquias_notifications_log");
    /*FIM TABELAS CUSTOMIZADAS ADICIONAIS*/

    /*END GLOBAL VARS*/

    // Aditional Functions
    require_once "@classe-system-functions.php";

    /*CLASSE SESSÃO ADMINISTRATIVA*/
    if(!class_exists("Pew_Session")){
        class Pew_Session{
            public $usuario;
            public $senha;
            public $nivel;
            public $empresa;
            public $id_usuario;
            public $id_franquia;

            function __construct($usuario = null, $senha = null, $nivel = null, $empresa = null, $id_usuario = 0, $id_franquia = 0){
                $this->empresa = "Lar e Obra";
                $this->usuario = $usuario;
                $this->senha = $senha;
                $this->nivel = $nivel;
                $this->id_usuario = $id_usuario;
                $this->id_franquia = $id_franquia;
            }
            
            function auth(){
                global $pew_db, $pew_functions, $conexao;
                $tabela_usuarios_administrativos = $pew_db->tabela_usuarios_administrativos;
                $authCondition = "usuario = '" . $this->usuario . "' and senha = '" . $this->senha . "'";
                $totalUsuario = $pew_functions->contar_resultados($tabela_usuarios_administrativos, $authCondition);
                if($totalUsuario > 0){
                    return true;
                }else{
                    return false;
                }
            }
            
            function block_level(){
                echo "<h3 align=center style='padding-top: 150px;'><i class='fas fa-exclamation-triangle'></i><br>Acesso restrito<br>Você não pode acessar esta página.<br><br> Qualquer dúvida entre em contato com a <a href='https://www.efectusdigital.com.br' target='_blank' class='link-padrao'>Efectus Digital</a></h3>";
                die();
            }
        }
    }
    /*FIM CLASSE SESSÃO ADMINISTRATIVA*/

    date_default_timezone_set("America/Sao_Paulo");
?>