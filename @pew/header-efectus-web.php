<style>
    .section-header{
        width: 100%;
        height: 100px;
    }
    .header-efectus-web{
        width: 100%;
        background-color: #fbfbfb;
        -webkit-box-shadow: 2px 0px 10px 5px rgba(0, 0, 0, .1);
        -moz-box-shadow: 2px 0px 10px 5px rgba(0, 0, 0, .1);
        box-shadow: 2px 0px 10px 5px rgba(0, 0, 0, .1);
        position: fixed;
        top: 0px;
        left: 0px;
        z-index: 80;
    }
    .header-efectus-web .top-info{
        width: 100%;
        height: 30px;
        background-color: #eee;
        color: #666;
        line-height: 30px;
        position: relative;
        z-index: 80;
    }
    .header-efectus-web .top-info .date-field{
        position: absolute;
        top: 0px;
        right: 0px;
        font-size: 14px;
        width: 120px;
        text-align: center;
    }
    .header-efectus-web .top-info .login-field{
        position: absolute;
        top: 0px;
        right: 120px;
        padding-left: 10px;
        border-right: 4px solid #dedede;
        padding-right: 10px;
        font-size: 14px;
        transition: .2s;
        cursor: pointer;
    }
    .header-efectus-web .top-info .login-field:hover{
        background-color: #df2321;
        color: #FFF;
        border-color: #df2321;
        border-radius: 5px;
        border-bottom-right-radius: 0px;
    }
    .header-efectus-web .top-info .login-field .menu-field{
        position: absolute;
        top: 25px;
        right: -4px;
        background-color: #df2321;
        font-size: 14px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        z-index: -1;
        opacity: 0;
        transition: .2s;
        background-color: #c02423;
        visibility: hidden;
    }
    .header-efectus-web .top-info .login-field:hover .menu-field{
        opacity: 1;
        top: 30px;
        z-index: 10;
        visibility: visible;
    }
    .header-efectus-web .top-info .login-field .menu-field li{
        display: block;
        list-style: none;
        text-decoration: none;
        color: #DDD;
        padding: 10px;
        padding-bottom: 0px;
        padding-top: 0px;
    }
    .header-efectus-web .top-info .login-field .menu-field a{
        text-decoration: none;
    }
    .header-efectus-web .top-info .login-field .menu-field li:hover{
        color: #999;
    }
    .header-efectus-web .nav-header{
		position: relative;
        width: 100%;
    }
    .header-efectus-web .nav-header .background-nav{
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: #000;
        top: 96px;
        z-index: -1;
        left: 0px;
        visibility: hidden;
        opacity: 0;
        transition: .4s;
        pointer-events: none;
    }
    .header-efectus-web .nav-header .logo-header{
        width: 250px;
        float: left;
    }
    .header-efectus-web .nav-header .logo-header img{
        width: 100%;
    }
    .header-efectus-web .nav-header .display-links{
        display: inline-block;
        padding: 0px;
        height: 68px;
        margin: 0px;
    }
    .header-efectus-web .nav-header .display-links:hover .background-nav{
        visibility: visible;
        opacity: .6;
        transition: visibilty 0s, opacity .3s;
    }
    .header-efectus-web .nav-header .display-links li{
        display: inline-block;
        position: relative;
        z-index: 51;
    }
    .header-efectus-web .nav-header .display-links .link-principal{
        display: inline-block;
        color: #f78a14;
        padding-left: 15px;
        padding-right: 15px;
        line-height: 65px;
        transition: .2s linear;
        border-top: 4px solid transparent;
        text-decoration: none;
    }
    .header-efectus-web .nav-header .display-links .sub-menu{
        background-color: #f6f6f6;
        padding: 0px;
        position: absolute;
        top: 65px;
        left: 0px;
        transition: .2s;
        visibility: hidden;
        opacity: 0;
    }
    .header-efectus-web .nav-header .display-links li:hover .link-principal{
        color: #df2321;
        border-color: #df2321;
        background-color: #f6f6f6;
        font-weight: bold;
    }
    .header-efectus-web .nav-header .display-links li:hover .sub-menu{
        opacity: 1;
        visibility: visible;
        transition: visibility 0s, opacity .2s;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li{
        white-space: nowrap;
        font-size: 14px;
        min-width: 250px;
        width: 100%;
        position: relative;
    }
    .header-efectus-web .nav-header .display-links .sub-menu .sub-link{
        display: block;
        text-decoration: none;
        color: #f78a14;
        width: 85%;
        padding: 10px;
        padding-left: 5%;
        padding-right: 10%;
        transition: .2s;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li:hover .sub-link{
        background-color: #f78a14;
        color: #f6f6f6;
        font-weight: bold;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li .sub-sub-menu{
        position: absolute;
        top: 0px;
        left: 96%;
        z-index: -1;
        background-color: #f2f2f2;
        padding: 0px;
        opacity: 0;
        visibility: hidden;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li:hover .sub-sub-menu{
        visibility: visible;
        opacity: 1;
        left: 100%;
        transition: visibility 0s, opacity .3s, left .2s;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li .sub-sub-menu li{
        white-space: nowrap;
        font-size: 14px;
        min-width: 200px;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li .sub-sub-menu .sub-sub-links{
        display: block;
        text-decoration: none;
        color: #df2321;
        width: 90%;
        padding: 10px;
        padding-left: 5%;
        padding-right: 5%;
        border-right: 2px solid transparent;
        transition: .2s;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li .sub-sub-menu .sub-sub-links:hover{
        font-weight: bold;
        border-color: #f78a14;
        color: #f78a14;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li .sub-sub-links-icon{
        position: absolute;
        top: 0px;
        right: 5px;
        width: 10%;
        height: 100%;
        line-height: 38px;
        color: #ccc;
        transition: .2s;
    }
    .header-efectus-web .nav-header .display-links .sub-menu li:hover .sub-sub-links-icon{
        color: #fff;
        right: 0px;
    }
	.notification-button{
		position: absolute;
		top: 0;
		right: 0;
		width: 70px;
		height: 68px;
		line-height: 68px;
		text-align: center;
		cursor: pointer;
		font-size: 28px;
		color: #666;
		transition: .3s;
		z-index: 100;
	}
	.notification-button .count{
		position: absolute;
		min-width: 8px;
		height: 8px;
		line-height: 10px;
		top: 30px;
		right: 15px;
		padding: 5px;
		background-color: #f78a14;
		border-radius: 50%;
		color: #fff;
		font-size: 10px;
		text-align: center;
	}
	.notification-button:hover{
		background-color: #ddd;
		color: #df2321;
	}
	.display-notifications{
		position: fixed;
		height: 100%;
		width: 325px;
		background-color: #fff;
		top: 0px;
		right: -100%;
		z-index: 200;
		color: #333;
		transition: .3s;
	}
	.display-notifications-active{
		right: 0;
	}
	.display-notifications header{
		position: relative;
	}
	.display-notifications header .title{
		height: 50px;
		line-height: 50px;
		margin: 0px;
		padding: 0px 10px;
		font-size: 18px;
		background-color: #f78a14;
		color: #fff;
	}
	.display-notifications header .close-notifications{
		position: absolute;
		top: 0px;
		right: 0px;
		width: 40px;
		height: 50px;
		line-height: 50px;
		text-align: center;
		font-size: 22px;
		color: #fff;
		background-color: transparent;
		border: none;
		cursor: pointer;
	}
	.display-notifications header .close-notifications-active{
		right: 0px;	
	}
	.display-notifications header .close-notifications:hover{
		color: #111;	
	}
	.display-notifications .notifications-list{
		padding-bottom: 40px;
		overflow-y: auto;
		height: calc(100% - 90px);
	}
	.display-notifications .notifications-list .notification-tag{
		position: relative;
		width: 10px;
		height: 10px;
		background-color: #faa84e;
		margin-right: 5px;
		float: left;
		border-radius: 50%;
		top: 4px;
	}
	.display-notifications .notifications-list .date-info{
		color: #999;
		text-align: center;
		margin: 10px 0 5px 0;
	}
	.display-notifications .notifications-list .notf-box{
		color: #666;
		padding: 15px;
		border-bottom: 1px solid #ddd;
		border-top: 1px solid #ddd;
		transition: .3s;
	}
	.display-notifications .notifications-list .hidden-notfy{
		position: relative;
		left: -100%;
		visibility: hidden;
		height: 0px;
		opacity: 0;
		padding: 0px;
		border: none;
	}
	.display-notifications .notifications-list .notf-box .title{
		margin: 0px 0px 10px 0px;	
	}
	.display-notifications .notifications-list .notf-box .description{
		font-size: 14px;	
	}
	.display-notifications .notifications-list .notf-box .description .date{
		display: block;
		padding: 5px 1px;
		font-size: 12px;
	}
	.display-notifications .notifications-list .notf-box .description .redirect{
		display: block;
		color: #ccc;
		text-decoration: none;
	}
	.display-notifications .notifications-list .notf-box .description .redirect:hover{
		text-decoration: underline;
	}
	.display-notifications .button-load-more{
		width: 100%;
		border: none;
		color: #666;
		background-color: transparent;
		height: 40px;
		line-height: 40px;
		text-align: center;
		transition: .2s;
		font-size: 14px;
		cursor: pointer;
	}
	.display-notifications .button-load-more:hover{
		background-color: #ddd;
		font-weight: bold;
		color: #333;
	}
	.display-notifications .bottom-controlls{
		position: absolute;
		width: 100%;
		bottom: 0px;
		right: 0px;
		display: flex;
		height: 40px;
		align-items: center;
		justify-content: space-between;
		background-color: #eee;
	}
	.display-notifications .bottom-controlls .button-controll{
		flex: 1 1 auto;
		text-align: center;
		height: 39px;
		border-top: 1px solid #ccc;
		line-height: 40px;
		align-self: center;
		color: #555;
		cursor: pointer;
	}
	.display-notifications .bottom-controlls .button-controll:hover{
		border-color: #f78a14;
		color: #f78a14;
	}
	.display-notifications .bottom-controlls .button-controll-active{
		border-color: #111;
		color: #111;
	}
	.notification-background{
		position: fixed;
		z-index: 199;
		width: 100%;
		height: 100%;
		top: 0px;
		right: 0px;
		background-color: rgba(0, 0, 0, .5);
		transition: .3s;
		visibility: hidden;
		opacity: 0;
	}
	.notification-background-active{
		visibility: visible;
		opacity: 1;
	}
</style>
<?php
class NavLinks{
		private $titulo_link;
		private $url_link;
		private $qtd_sublinks;
		private $sublinks;
		private $qtd_sub_sublinks;
		private $sub_sublinks;
		private $invalid_levels = array();
		private $classe;

		function __construct($tituloLink, $urlLink, $classe = null, $invalid_levels = array()){
			$this->titulo_link = $tituloLink;
			$this->url_link = $urlLink;
			$this->qtd_sublinks = 0;
			$this->sublinks = array();
			$this->qtd_sub_sublinks = 0;
			$this->sub_sublinks = array();
			$this->invalid_levels = $invalid_levels;
			$this->classe = $classe;
		}

		public function add_sublink($id, $titulo, $url, $invalid_levels = array()){
			$this->sublinks[$this->qtd_sublinks] = array();
			$this->sublinks[$this->qtd_sublinks]["id"] = $id;
			$this->sublinks[$this->qtd_sublinks]["titulo"] = $titulo;
			$this->sublinks[$this->qtd_sublinks]["url"] = $url;
			$this->sublinks[$this->qtd_sublinks]["qtd_sub_sublinks"] = 0;
			$this->sublinks[$this->qtd_sublinks]["invalid_levels"] = $invalid_levels;
			$this->qtd_sublinks++;
		}

		public function add_sub_sublink($idSublink, $titulo, $url, $produto_destaque = false){
			$this->sub_sublinks[$this->qtd_sub_sublinks] = array();
			$this->sub_sublinks[$this->qtd_sub_sublinks]["id_sublink"] = $idSublink;
			$this->sub_sublinks[$this->qtd_sub_sublinks]["titulo"] = $titulo;
			$this->sub_sublinks[$this->qtd_sub_sublinks]["url"] = $url;
			$this->sub_sublinks[$this->qtd_sub_sublinks]["produto_destaque"] = $produto_destaque;
			foreach($this->sublinks as $indice => $sublink){
				$id = $sublink["id"];
				if($idSublink == $id){
					$this->sublinks[$indice]["qtd_sub_sublinks"]++;
				}
			}
			$this->qtd_sub_sublinks++;
		}

		public function get_qtd_sublinks(){
			return $this->qtd_sublinks;
		}

		public function listar_link(){
			$tituloPrincipal = $this->titulo_link;
			$urlPrincipal = $this->url_link;
			$subLinks = $this->sublinks;
			$classe = $this->classe;
			
			global $pew_session;
			
			if(!in_array($pew_session->nivel, $this->invalid_levels)){
				
				echo "<li>";
					echo "<a href='$urlPrincipal' class='link-principal'>$tituloPrincipal</a>";
					$quantidadeSubLinks = isset($linkMenu["sub_link"]) ? count($linkMenu["sub_link"]) : 0;
					if($this->qtd_sublinks > 0){
						echo "<ul class='sub-menu'>";
							foreach($subLinks as $sublink){
								$tituloSubLink = $sublink["titulo"];
								$urlSubLink = $sublink["url"];
								$qtd_sub_subLinks = $sublink["qtd_sub_sublinks"];
								$sub_subLinks = $this->sub_sublinks;
								$invalid_levels = $sublink["invalid_levels"];
								if(!in_array($pew_session->nivel, $invalid_levels)){
									echo "<li><a href='$urlSubLink' class='sub-link'>$tituloSubLink</a>";
									if($qtd_sub_subLinks > 0){
										echo "<span class='sub-sub-links-icon'><i class='fa fa-arrow-right' aria-hidden='true'></i></span>";
										echo "<ul class='sub-sub-menu'>";
										foreach($sub_subLinks as $subSubLink){
											$title = $subSubLink["titulo"];
											$url = $subSubLink["url"];
											echo "<li><a href='$url' class='sub-sub-links'>$title</a></li><br>";
										}
										echo "</ul>";
									}
									echo "</li>";
								}
							}
						echo "</ul>";
					}
				echo "</li>";
				
			}
		}
	}
	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<section class="section-header">
    <header class="header-efectus-web">
        <div class="top-info">
            <div class="date-field">
                <i class="fas fa-calendar-alt"></i> <?php echo $pew_functions->inverter_data(substr(date("Y-m-d h:i:s"), 0, 10)); ?>
            </div>
            <div class="login-field"><i class="fas fa-user-circle"></i>
                <?php
                    switch($pew_session->nivel){
						case 1:
							$pew_nivel = "Franqueador";
							break;
						case 2:
							$pew_nivel = "Franquia";
							break;
                        case 3:
                            $pew_nivel = "Administrador";
                            break;
                        case 4:
                            $pew_nivel = "Comercial";
                            break;
                        default:
                            $pew_nivel = "Designer";
                    }
                ?>
                <?php echo $pew_session->usuario." | ".$pew_nivel; ?>
                <div class="menu-field">
                    <a href="../" target="blank"><li><i class="fas fa-external-link-alt"></i> Ir para a loja</li></a>
                    <a href="pew-configurar-conta.php"><li>Configurar conta</li></a>
                    <a href="deslogar.php"><li>Sair</li></a>
                </div>
            </div>
        </div>
        <nav class="nav-header">
            <div class="logo-header"><a href="pew-painel-controle.php"><img src="imagens/sistema/identidadeVisual/logo-efectus-web.png" alt="Efectus Web - Desenvolvimento de Softwares e Plataformas Web" title="Painel de Controle"></a></div>
            <?php
				$countLinks = 0;
			
				$link_nav[$countLinks] = new NavLinks("Banners", "pew-banners.php", null, array(4));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-images'></i> Listar Banners", "pew-banners.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus'></i> Cadastrar novo", "pew-cadastra-banner.php", array(5, 3, 2));
				$countLinks++;
				
				$link_nav[$countLinks] = new NavLinks("Produtos", "pew-produtos.php", null, array(5));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-th' aria-hidden='true'></i> Listar produtos", "pew-produtos.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-tasks'></i> Solicitar produtos", "pew-lista-produtos-franquia.php", array(1));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus' aria-hidden='true'></i> Cadastrar novo", "pew-cadastra-produto.php", array(4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-tag' aria-hidden='true'></i> Marcas", "pew-marcas.php", array(4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-paint-brush'></i> Cores", "pew-cores.php", array(4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-cogs' aria-hidden='true'></i> Especificações técnicas", "pew-especificacoes.php", array(4, 3, 2));
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Vendas", "pew-vendas.php", null, array(5));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-dollar-sign'></i> Listar Pedidos", "pew-vendas.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-box-open'></i> Retirada na loja", "pew-retirada-loja.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-chart-pie'></i> Relatórios", "pew-relatorios.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-truck'></i> Rotas de entrega", "pew-rotas-entrega.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-clock'></i> Promoções", "pew-promocoes.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Dicas", "pew-dicas.php", null, array(1));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-newspaper'></i> Listar dicas", "pew-dicas.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus'></i> Cadastrar nova", "pew-cadastra-dica.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Vitrine", "pew-categorias-vitrine.php", null, array(1));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-tag'></i> Categorias da vitrine", "pew-categorias-vitrine.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-star'></i> Categorias destaque", "pew-categoria-destaque.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Orçamentos", "pew-orcamentos.php", null, array(5, 4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-dollar-sign'></i> Pedidos de orçamento", "pew-orcamentos.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus'></i> Cadastrar orçamento", "pew-cadastra-orcamento.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Mensagens", "pew-contatos.php", null, array(5));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='far fa-comment'></i> Contatos", "pew-contatos.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-question'></i> Atendimento", "pew-tickets.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-briefcase'></i> Contatos Serviços", "pew-contatos-servicos.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='far fa-newspaper'></i> Newsletter", "pew-newsletter.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Clientes", "pew-clientes.php", null, array(5));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-users'></i> Lista de clientes", "pew-clientes.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-users'></i> Clube de Descontos", "pew-clube-descontos.php", array(5, 4, 3, 2));
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Categorias", "pew-categorias.php", null, array(5, 4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-tags'></i> Listar categorias", "pew-categorias.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-th-list'></i> Departamentos", "pew-departamentos.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Loja", "pew-usuarios.php", null, array(5, 4));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-users'></i> Usuários", "pew-usuarios.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus'></i> Cadastrar usuário", "pew-cadastra-usuario.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-truck'></i> Opções de Transporte", "pew-opcoes-transporte.php", array(1));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-hotel'></i> Franquias", "pew-franquias.php", array(3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-tasks'></i> Solicitações de produtos", "pew-gerenciamento-solicitacoes-produtos.php", array(3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-database'></i> Log Franquias", "pew-log-franquias.php", array(3, 2));
				$countLinks++;
			
                $quantidadeLinks = count($link_nav);
                if($quantidadeLinks > 0){
                    echo "<ul class='display-links'>";
                    echo "<span class='background-nav'></span>";
                    $i = 0;
                    foreach($link_nav as $link){
						$link->listar_link();
					}
                    echo "</ul>";
                }
			
				require_once "@classe-notificacoes.php";
				$cls_notficacoes = new Notificacoes();
				$newNotificacoes = $cls_notficacoes->get_views($pew_session->id_usuario);
				$ctrlNovas = 0;
				foreach($newNotificacoes as $infoN){
					$status = $infoN['status'];
					if($status == 0){
						$ctrlNovas++;
					}
				}
				// Button Notificacoes
				echo "<div class='notification-button'><i class='far fa-bell'></i><span class='count js-notfy-count'>$ctrlNovas</span></div>";
				echo "<br style='clear:both;'>";
            ?>
        </nav>
		<!--SISTEMA DE NOTIFICAÇÕES-->
		<div class="display-notifications">
			<header>
				<h3 class="title">Notificações</h3>
				<button class="close-notifications"><i class="fas fa-times"></i></button>
			</header>
			<div class="notifications-list">
			<?php
				$condicaoNotificacoes = $pew_session->nivel == 1 ? "true" : "id_franquia = '{$pew_session->id_franquia}'";
				
				$queryTodas = $cls_notficacoes->query_id($condicaoNotificacoes);
				$totalNotificacoes = count($queryTodas);
					
				$selectedNotificacoes = $cls_notficacoes->query_id($condicaoNotificacoes, null, 20);
				$totalListadas = count($selectedNotificacoes);
				$loadCount = $totalNotificacoes - $totalListadas;
				$loadCount = $loadCount > 20 ? 20 : $loadCount;

				$cssStyle = "display: none;";

				if($totalListadas > 0){
					echo "<h5 class='date-info js-new-notifications' js-date-filter='antigo' style='display: none;'>Novas</h5>";
					echo "<span class='js-span-notifications'>";
					$cls_notficacoes->listar_notificacoes($selectedNotificacoes);
					echo "</span>";
					echo "<input type='hidden' class='js-total-notifications' value='$totalNotificacoes'>";
					echo "<input type='hidden' class='js-last-span' value='{$cls_notficacoes->ctrl_span}'>";
					if($totalNotificacoes > $totalListadas){
						echo "<button class='button-load-more'>Carregar mais ($loadCount)</button>";
					}
				}else{
					$cssStyle = null;
				}

				echo "<span class='no-results' style='$cssStyle'>";
					echo "<h4 align=center>Nenhum resultado encontrado</h4>";
				echo "</span>";
			?>
			</div>
			<div class="bottom-controlls">
				<div class="button-controll button-controll-active" title="Todas as notificações" js-notfy-type='global'>
					<i class="fas fa-globe-americas"></i>
				</div>
				<div class="button-controll" title="Financeiro" js-notfy-type='finances'>
					<i class="fas fa-dollar-sign"></i>
				</div>
				<div class="button-controll" title="Contato e Mensagens" js-notfy-type='contact'>
					<i class="fas fa-comments"></i>
				</div>
				<div class="button-controll" title="Mensagens do sistema" js-notfy-type='system'>
					<i class="fas fa-desktop"></i>
				</div>
				<?php
				if($pew_session->nivel == 1){
					echo 
					"<div class='button-controll' title='Franquias' js-notfy-type='franquias'>
						<i class='fas fa-hotel'></i>
					</div>";
				}
				?>
			</div>
		</div>
		<span class="notification-background"></span>
		<!--END SISTEMA DE NOTIFICAÇÕES-->
    </header>
</section>
<script>
	$(document).ready(function(){
		// Sistema Notificações
		var displayNotifications = $(".display-notifications");
		var headerNotifications = displayNotifications.children("header");
		var closeNotifications = headerNotifications.children(".close-notifications");
		var notificationsList = displayNotifications.children(".notifications-list");
		var notificationsSpan = notificationsList.children(".js-span-notifications");
		var notificationsBox = notificationsSpan.children(".notf-box");
		var bottomControlls = displayNotifications.children(".bottom-controlls");
		var controllButtons = bottomControlls.children(".button-controll");
		var buttonOpenNotf = $(".notification-button");
		var buttonLoadMore = notificationsList.children(".button-load-more");
		var notfCountView = buttonOpenNotf.children(".js-notfy-count");
		var notfBackground = $(".notification-background");
		var totalNotifications = notificationsList.children(".js-total-notifications").val();
		var lastSpan = notificationsList.children(".js-last-span").val();
		var newMessagesInfo = notificationsList.children(".js-new-notifications");
		var filterTypeActive = null;
		var refreshDelay = 5000;
		
		var loading_notifications = false;
		
		function load_new_messages(){
			$.ajax({
				type: "POST",
				url: "@classe-notificacoes.php",
				data: {acao: "get_views", status: 0},
				success: function(response){
					if(JSON.parse(response) != false){
						var jsonResponse = JSON.parse(response);
						var loadArray = [];
						jsonResponse.forEach(function(dados){
							loadArray.push(dados.id_notificacao);
						});
						if(loadArray.length > 0){
							var exeptions = get_exeptions();
							load_messages(exeptions, loadArray, "top");
						}
					}
				},
				complete: function(){
					loading_notifications = false;
				}
			});
		}
		
		function load_messages(exeptions = null, selected = null, position = "bottom"){
			buttonLoadMore.html("...");
			$.ajax({
				type: "POST",
				url: "@classe-notificacoes.php",
				data: {acao: "load_more", exeptions: exeptions, ctrl_span: lastSpan, selected: selected},
				error: function(){
					mensagemAlerta("Ocorreu um erro ao carregar as notificações. Recarregue a página e tente novamente.");
				},
				success: function(response){
					if(response != "no_result"){
						
						if(position == "bottom"){
							notificationsSpan.append(response);
						}else{
							newMessagesInfo.addClass("show-after-filter").show();
							notificationsSpan.prepend(response);
						}

						if(count_box_views() == totalNotifications){
							buttonLoadMore.remove();
						}else{
							var loadCount = totalNotifications - count_box_views();
							loadCount = loadCount > 20 ? 20 : loadCount;
							buttonLoadMore.html("Carregar mais (" + loadCount + ")");
						}
					}
				},
				complete: function(){
					update_box_layout();
					
					if(filterTypeActive != null){
						filter_by_type(filterTypeActive);
					}
				}
			});
		} 
		
		function update_views(){
			var array = [];
			var ctrlUpdate = 0;
			if(!loading_notifications){
				loading_notifications = false;
				notificationsBox.each(function(){
					var box = $(this);
					var idNotificacao = box.attr("js-notfy-id");
					var statusAtual = box.attr("js-notfy-status");
					if(statusAtual == 0){
						array.push(idNotificacao);
						ctrlUpdate++;
					}
				});
				if(ctrlUpdate > 0){
					$.ajax({
						type: "POST",
						url: "@classe-notificacoes.php",
						data: {acao: "update_views", notificacoes: array, status: 1},
						complete: function(){
							loading_notifications = false;	
						},
						success: function(response){
							console.log(response)
							update_controll_view();
						},
					});
				}
			}
		}
		
		function update_controll_view(){
			notfCountView.text("...");
			$.ajax({
				type: "POST",
				url: "@classe-notificacoes.php",
				data: {acao: "get_views", status: 0},
				success: function(response){
					if(JSON.parse(response) != false){
						var jsonResponse = JSON.parse(response);
						notfCountView.text(jsonResponse.length);
					}else{
						notfCountView.text(0);
					}
				},
			});
		}
		
		setInterval(function(){
			update_controll_view();
		}, refreshDelay);
		
		function toggle_notf_background(){
			if(notfBackground.hasClass("notification-background-active")){
				notfBackground.removeClass("notification-background-active");
			}else{
				notfBackground.addClass("notification-background-active");
			}
		}
		
		var loadNewMessages = false;
		setTimeout(function(){
			loadNewMessages = true;
		}, 5000);
		function toggle_notf_display(){
			if(loadNewMessages){
				load_new_messages();
			}
			toggle_notf_background();
			if(displayNotifications.hasClass("display-notifications-active")){
				update_controll_view();
				$("body").css("overflow-y", "auto");
				displayNotifications.removeClass("display-notifications-active");
				$(".notification-tag").each(function(){
					$(this).remove();
				});
			}else{
				update_views();
				$("body").css("overflow-y", "hidden");
				displayNotifications.addClass("display-notifications-active");
			}
		}
		
		function filter_by_type(type){
			var ctrl = 0;
			filterTypeActive = type == "global" ? null : type;
			notificationsList.children(".no-results").css("display", "none");
			notificationsBox.each(function(){
				var box = $(this);
				var boxType = box.attr("js-notfy-type");
				if(boxType == type || type == "global"){
					box.removeClass("hidden-notfy");
					ctrl++;
				}else{
					box.addClass("hidden-notfy");
				}
			});
			
			notificationsSpan.children(".date-info").each(function(){
				var filter = $(this).attr("js-date-filter");
				var remove = true;
				notificationsBox.each(function(){
					var box = $(this);
					var boxDateFilter = box.attr("js-notfy-date-filter");
					if(filter == boxDateFilter && box.hasClass("hidden-notfy") == false){
						remove = false;
					}
				});
				if(remove){
					$(this).hide();
				}else{
					$(this).show();
				}
			});
			
			
			var showNewMessagesInfo = newMessagesInfo.hasClass("show-after-filter") ? true : false;
			if(type == "global" && showNewMessagesInfo){
				newMessagesInfo.show();
			}else{
				newMessagesInfo.hide();
			}
			
			if(ctrl == 0){
				notificationsList.children(".no-results").css("display", "block");
			}
		}
		
		function get_exeptions(){
			var exeptions = [];
			notificationsBox.each(function(){
				var box = $(this);
				var idNotificacao = box.attr("js-notfy-id");
				exeptions.push(idNotificacao);
			});
			return exeptions;
		}
		
		function update_box_layout(){
			notificationsBox = notificationsSpan.children(".notf-box");
		}
		
		function count_box_views(){
			update_box_layout();
			var count = 0;
			notificationsBox.each(function(){
				count++;
			});
			return count;
		} 
		
		controllButtons.each(function(){
			var button = $(this);
			var type = button.attr("js-notfy-type");
			type = typeof type != "undefined" && type.length > 0 ? type : "global";
			button.off().on("click", function(){
				controllButtons.each(function(){
					$(this).removeClass("button-controll-active");
				});
				button.addClass("button-controll-active");
				filter_by_type(type);
			});
		});
		
		buttonLoadMore.off().on("click", function(){
			update_box_layout();
			var exeptions = get_exeptions();
			load_messages(exeptions);
		});
		
		// open/close triggers
		buttonOpenNotf.off().on("click", function(){
			toggle_notf_display();
		});
		
		closeNotifications.off().on("click", function(){
			toggle_notf_display();
		});
		
		notfBackground.off().on("click", function(){
			toggle_notf_display();
		});
		// END open/close triggers
	});
</script>