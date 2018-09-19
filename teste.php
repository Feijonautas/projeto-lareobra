<html>
	<head>
		<title>Clube de Descontos</title>
		<!-- You can use Open Graph tags to customize link previews.
Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
		<meta property="og:url"           content="https://www.lareobra.com.br/dev/teste.php" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="Faça parte do Clube de Descontos" />
		<meta property="og:description"   content="Olá, tudo bem? Estou lhe convidando para participar do Clube de Descontos! Você vai adorar os benefícios que te esperam. Acesse: $reference_url" />
		<meta property="og:image"         content="https://www.lareobra.com.br/dev/imagens/identidadeVisual/logo-lareobra.png" />
	</head>
	<body>

		<!-- Load Facebook SDK for JavaScript -->
		<div id="fb-root"></div>
		<script>
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>

		<!-- Your share button code -->
		<div class='fb-share-button' 
			 data-href='https://www.lareobra.com.br/dev/teste.php' 
			 data-layout='button'>
		</div>

	</body>
</html>