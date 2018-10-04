<!--STANDARDS-->
<script src="@pew/jquery-mask/src/jquery.mask.js"></script>
<script src="js/standard.js?v=<?= time(); ?>" async></script>
<script src="js/valida-datas.js" async></script>
<script src="js/valida-cpf.js?v=1.3" async></script>
<script src="js/valida-cep.js?v=1.2" async></script>
<script src="js/vitrines.js?v=5"></script>
<!--END STANDARDS-->
<!--SLICK SLIDER-->
<script src="js/slick-slider/slick.min.js" async></script>
<link rel="stylesheet" type="text/css" href="js/slick-slider/slick.css">
<link rel="stylesheet" type="text/css" href="js/slick-slider/slick-theme.css">
<script src="js/vitrine-carrossel.js"></script>
<!--END SLICK SLIDER-->
<!--CARRINHO-->
<?php require_once "@include-carrinho.php"; ?>
<!--END CARRINHO-->
<!--MODAL ENTRADA-->
<?php require_once "@include-modal-entrada.php"; ?>
<!--END MODAL ENTRADA-->
<!--MODAL SAIDA-->
<?php require_once "@include-modal-saida.php"; ?>
<!--END MODAL SAIDA-->