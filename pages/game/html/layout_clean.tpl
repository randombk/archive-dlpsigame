<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<html class="no-js">
	<head>
		{{include file="main_header.tpl" bodyclass="full"}}
	</head>
	<body class="mainBody" unselectable="on">
		<!--[if lt IE 7]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p><![endif]-->
		<div class="gameContainer">
			<div class="gamePageContainer">
				{{block name="content"}}{{/block}}
			</div>
			{{include file="main_footer.tpl" nocache}}
		</div>
		<div hidden="hidden" id="hidden-container"></div>

	{{include file="main_scripts.tpl" bodyclass="full"}}
	</body>
</html>
