{{block name="title" prepend}}Notifications{{/block}}
{{block name="additionalIncluding" append}}
	<script src="handlebars/notificationBox.js?v={{$VERSION}}"></script>
{{/block}}
{{block name="content"}}
<table class="pageTable">
	<tbody>
		<tr>
			<th>Notifications</th>
		</tr>
	</tbody>
</table>
<div class="notificationHolder" style="background: rgba(3,3,3,0.4);">
	<div id="scrollHolder" class="scrollable absFill">
		<div class="scrollbar">
			<div class="thumb"></div>
		</div>
		<div class="viewport">
			<div class="overview absFill" style="position: absolute;">
				<div class="msgHolder" style="text-align:center;">
					Loading Data...
				</div>
			</div>
		</div>
	</div>
</div>

<div class="msgControls">
	<div class="msgControl" onclick="loadData()">
		Reload Data
	</div>

	<div class="msgControl" oncontextmenu="return false;" onclick="return clearNotifications();">
		Clear All
	</div>
</div>
{{/block}}
{{block name="winHandlers" append}}
	<script type="text/javascript">
		(function($) {
			function dataUpdateReceiver(channel, payload) {
				if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all") || inArray(payload.msgTarget, "windows")) {
						console.log(payload);
						switch (payload.msgType) {
							case "msgUpdateNotifications": {
								loadNotifications(payload.msgData.notificationData);
								break;
							}
						}
					}
				}
			}

			$(window).load(function() {
				$.jStorage.subscribe("dataUpdater", dataUpdateReceiver);
			});

			$(document).ready(function() {
				$("#scrollHolder").tinyscrollbar();
				loadNotificationData();
				$(window).resize(function() {
					$(".scrollable").tinyscrollbar_update();
				});
			});
		})(jQuery);

		function loadNotifications(data) {
			$(".msgHolder").html("");
			var notifications = false;
			var notificationTemplate = Handlebars.templates['notificationBox.tmpl'];
			for (var i in data) {
				if(i == "code") {
					continue;
				}
				notifications = true;
				var dateStr = formatDateTime(new Date(parseInt(data[i].messageTime) * 1000));

				var color = "";
				switch(data[i].messageText.msgType) {
					case "ERROR": 	color = "red-over"; break;
					case "NOTICE": 	color = "blue-over"; break;
					case "WARNING": color = "yellow-over"; break;
					case "REPORT": 	color = "blue-over"; break;
					case "OK": 		color = "green-over"; break;
					default:
						color = "blue-over";
				}

				$(".msgHolder").append(notificationTemplate({
					link: data[i].messageText.msgAction,
					content: data[i].messageText.msgText,
					id: data[i].messageID,
					title: "(" + dateStr + ")  " + data[i].messageSubject,
					image: data[i].messageText.msgImage,
					class: color
				}));
			}

			if(!notifications) {
				$(".msgHolder").html("No Notifications");
			}

			$(".scrollable").tinyscrollbar_update();
		}

		function loadNotificationData() {
			$.post("ajaxRequest.php",
				{"ajaxType": "MessageHandler", "action" : "getNotifications"},
				function(data){
					if(data.code < 0) {
						$(".msgHolder").text("Error #" + (-data.code) + ": " + data.message);
					} else {
						$.jStorage.publish("dataUpdater", new Message("msgUpdateNotifications", {"notificationData" : data}, ["all"], window.name));
					}
				},
				"json"
			).fail(function() { $(".msgHolder").text("An error occurred while getting data"); });
		}

		function clearNotifications() {
			$.post("ajaxRequest.php", {"ajaxType": "MessageHandler", "action" : "clearNotifications"}, function(data){}, "json");
			loadNotificationData();
		}

		function removeNotification(messageID) {
			$.post("ajaxRequest.php", {"ajaxType": "MessageHandler", "action" : "deleteMessage", "messageID" : messageID}, function(data){}, "json");
			loadNotificationData();
		}
	</script>
{{/block}}
