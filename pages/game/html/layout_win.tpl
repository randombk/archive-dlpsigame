<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<html class="no-js">
	<head>
		<title>{{block name="title"}}{{/block}}</title>
		{{include file="main_header.tpl"}}
	</head>
	<body id="winBody" unselectable="on">
		<div id="gameContainer">
			<div id="blankOut"></div>
			<div id="confirmBox">
			    <div class="message"></div>
			    <span class="button yes">Yes</span>
			    <span class="button no">No</span>
			</div>
			<div id="inputBox">
				<label for="in" class="inputText"></label>
  				<input name="in" class="inputField">
				<span class="button yes">Enter</span>
			    <span class="button no">Cancel</span>
			</div>
			{{block name="content"}}{{/block}}
		</div>

		{{include file="main_scripts.tpl" bodyclass="full"}}
		<script type="text/javascript">
			//Load window handlers
			function msgWinReceiver(channel, payload) {
				if (channel == "winManager" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all") || inArray(payload.msgTarget, "windows")) {
						//console.log(payload);
						switch (payload.msgType) {
							case "msgNewMain": {
								$.jStorage.publish("winManager", new Message("msgWinOpen", null, ["main"], window.name));
								break;
							}
						}
					}
				}
			}
			(function($) {
				$(window).load(function() {
					//Handle window events
					$.jStorage.subscribe("winManager", msgWinReceiver);
					$.jStorage.publish("winManager", new Message("msgNewWin", null, ["all"], window.name));
				});

				$(window).unload(function() {
					$.jStorage.publish("winManager", new Message("msgCloseWin", null, ["all"], window.name));
				});
			})(jQuery);
		</script>
	</body>
</html>
