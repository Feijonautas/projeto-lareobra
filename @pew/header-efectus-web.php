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
							$pew_nivel = "Franquia Principal";
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
                    <a href="pew-configurar-conta.php"><li>Configurar conta</li></a>
                    <a href="deslogar.php"><li>Sair</li></a>
                </div>
            </div>
        </div>
        <nav class="nav-header">
            <div class="logo-header"><a href="pew-banners.php"><img src="imagens/sistema/identidadeVisual/logo-efectus-web.png" alt="Efectus Web - Desenvolvimento de Softwares e Plataformas Web" title="Painel de Controle"></a></div>
            <?php
				$countLinks = 0;
			
				$link_nav[$countLinks] = new NavLinks("Banners", "pew-banners.php", null, array(4));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-images'></i> Listar Banners", "pew-banners.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus'></i> Cadastrar novo", "pew-cadastra-banner.php");
				$countLinks++;
				
				$link_nav[$countLinks] = new NavLinks("Produtos", "pew-produtos.php", null, array(5));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-th' aria-hidden='true'></i> Listar produtos", "pew-produtos.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-tasks'></i> Atualizar lista de produtos", "pew-lista-produtos-franquia.php", array(1	));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus' aria-hidden='true'></i> Cadastrar novo", "pew-cadastra-produto.php", array(4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-tag' aria-hidden='true'></i> Marcas", "pew-marcas.php", array(4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-paint-brush'></i> Cores", "pew-cores.php", array(4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-cogs' aria-hidden='true'></i> Especificações técnicas", "pew-especificacoes.php", array(4, 3, 2));
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Vendas", "pew-vendas.php", null, array(5));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-dollar-sign'></i> Listar Pedidos", "pew-vendas.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-chart-pie'></i> Relatórios", "pew-relatorios.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Dicas", "pew-dicas.php", null, array());
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-newspaper'></i> Listar dicas", "pew-dicas.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus'></i> Cadastrar nova", "pew-cadastra-dica.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Vitrine", "pew-categorias-vitrine.php", null, array());
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
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='far fa-envelope'></i> E-mails newsletter", "pew-newsletter.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Categorias", "pew-categorias.php", null, array(5, 4, 3, 2));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-tags'></i> Listar categorias", "pew-categorias.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-th-list'></i> Departamentos", "pew-departamentos.php");
				$countLinks++;
			
				$link_nav[$countLinks] = new NavLinks("Loja", "pew-usuarios.php", null, array(5, 4));
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-users'></i> Usuários", "pew-usuarios.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fa fa-plus'></i> Cadastrar usuário", "pew-cadastra-usuario.php");
				$link_nav[$countLinks]->add_sublink($countLinks, "<i class='fas fa-hotel'></i> Franquias", "pew-franquias.php", array(3, 2));
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
                    echo "<br style='clear:both;'>";
                }
            ?>
        </nav>
    </header>
</section>