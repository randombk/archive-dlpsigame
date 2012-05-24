<!DOCTYPE html>
<html>
	{{include file="main_header.tpl"}}
	<body id="mainBody">
	{{include file="main_navigation.tpl"}}
		<div class="pageBox" style="width: 800px; margin-top: 30px;">
			{{block name=content}}{{/block}}
		</div>
	{{include file="main_footer.tpl" nocache}}
	</body>
</html>
