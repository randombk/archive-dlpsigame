<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"> <![endif]-->
<html class="no-js">
	<head>
		<title>{{block name="title"}} - {{$game_name}}{{/block}}</title>
		{{include file="main_header.tpl" bodyclass="full"}}
	</head>
	<body id="mainBody" unselectable="on">
		<div id="gameContainer">
			<div id="gameMenuContainer">
				<ul id="gameMenu">
					<img class="menuImage" src="http://placehold.it/200x100&text=game+title">

					<li id="pageOverview" class="menuItem buttonDiv" ><a href="game.php?page=overview">Overview</a></li>
					<li id="pageFleetSummary" class="menuItem buttonDiv" ><a href="game.php?page=research">Fleet Summary</a></li>
					<li id="pageMap" class="menuItem buttonDiv" ><a href="game.php?page=map&mode=uniMap">Universe Map</a></li>
					<li id="pageResearchMap" class="menuItem buttonDiv" ><a href="game.php?page=researchmap">Research Map</a></li>

					<li class="menuItem menuWindowItem buttonDiv"
						id="winInventory"
						data-windowTitle="Inventory"
						data-windowWidth="800"
						data-windowHeight="500"
						data-windowHref="game.php?page=invWindow&objectID={{$objectID}}">
						<a>Inventory Management</a>
					</li>

					<img class="menuImage" src="http://placehold.it/200x30/fff&text=Object+Functions">
					<li id="pageObjectOverview" class="menuItem buttonDiv" ><a href="game.php?page=objectoverview&objectID={{$objectID}}">{{$objectTypeName}} Overview</a></li>
					<li id="pageBuildings" class="menuItem buttonDiv" ><a href="game.php?page=buildings&objectID={{$objectID}}">Buildings</a></li>
					<li id="pageResearch" class="menuItem buttonDiv" ><a href="game.php?page=research&objectID={{$objectID}}">Research</a></li>
					<li id="pageFactory" class="menuItem buttonDiv" ><a href="game.php?page=shipyard&mode=fleet&objectID={{$objectID}}">Orbital Factory</a></li>
					<li id="pageFleet" class="menuItem buttonDiv" ><a href="game.php?page=fleetTable&objectID={{$objectID}}">Fleet Commands</a></li>

					<img class="menuImage" src="http://placehold.it/200x30/fff&text=Game+Functions">
					<li class="menuItem menuWindowItem buttonDiv"
						id="winNotifications"
						data-windowTitle="Notifications"
						data-windowWidth="600"
						data-windowHeight="600"
						data-windowHref="game.php?page=notificationWindow">
						<a>Notifications</a>
					</li>

					<li class="menuItem menuWindowItem buttonDiv"
						id="winMessages"
						data-windowTitle="Messages"
						data-windowWidth="600"
						data-windowHeight="600"
						data-windowHref="game.php?page=messages">
						<a>Messages</a>
					</li>

					<li id="pageAlliance" class="menuItem buttonDiv" ><a href="game.php?page=alliance">Alliance</a></li>
					<li id="pageFriends" class="menuItem buttonDiv" ><a href="game.php?page=buddyList">Friends</a></li>

					<li class="menuItem menuWindowItem buttonDiv"
						id="winSearch"
						data-windowTitle="Player Search"
						data-windowWidth="600"
						data-windowHeight="600"
						data-windowHref="game.php?page=search">
						<a>Player Search</a>
					</li>

					<li class="menuItem menuWindowItem buttonDiv"
						id="winSupport"
						data-windowTitle="Support"
						data-windowWidth="600"
						data-windowHeight="600"
						data-windowHref="game.php?page=support">
						<a>Support</a>
					</li>

					<li class="menuItem menuWindowItem buttonDiv"
						id="winNotices"
						data-windowTitle="Notices"
						data-windowWidth="600"
						data-windowHeight="600"
						data-windowHref="notices">
						<a>Notices</a>
					</li>

					<li id="pageRules" class="menuItem buttonDiv" ><a href="index.php?page=rules" target="rules">Rules</a></li>
					<li id="pageSettings" class="menuItem buttonDiv" ><a href="game.php?page=settings">Setting</a></li>
					<li class="menuItem buttonDiv" ><a href="game.php?page=logout">Logout</a></li>

					{{if $isAdmin}}
						<li class="menuItem buttonDiv" id="funcClearGameCache">
							<a href="javascript:clearCache();">Clear Game Cache</a>
						</li>
					{{/if}}
				</ul>
				<div class="br" style="background-color: transparent;"></div>
				<div class="bBottom" style="bottom: -15px;">
					<div class="bb"  style="background-color: transparent; left: 0;"></div>
					<div class="cbr" style="background-color: transparent;"></div>
				</div>
			</div>

			<div id="gameTopContainer">
				<div class="playerImage">
					<img src="http://placehold.it/100x100&text=player+image">
				</div>
				<div class="topBanner">
					<div class="playerBox">
						<div class="playerName">{{$playerName}}</div>
						<div class="dataHolder">
							<div class="dataUnit"><b>Player ID:</b> {{$playerID}}</div>
							<div class="dataUnit"><b>Planets:</b> {{$numPlanets}}</div>
							<div class="dataUnit-long"><b>Alliance:</b> (None)</div>
							<div class="dataUnit"><b>Rank:</b> N/A</div>
							<div class="dataUnit-long"><b>Last Login:</b> </div>
						</div>
					</div>
					<div class="dataBox">
						<div class="notifications">
						    Loading Data...
						</div>
						<div class="fleet">
						    <div class="item">No fleet movements</div>
						</div>
					</div>
				</div>
				<div id="objectListToggle" class="objectSelector buttonDiv">
					{{$objectName}} ({{$objectCoord}})
				</div>
				<div class="timeBanner"></div>
			</div>

			<div id="objectListContainer" style="display: none;">
				<div class="objectListSearch"></div>
				<div class="objectListHolder scrollable">
					<div class="scrollbar">
						<div class="track">
							<div class="thumb green-over">
								<div class="end"></div>
							</div>
						</div>
					</div>
					<div class="viewport">
					    <div id="objectList" class="overview">
						</div>
					</div>
				</div>
			</div>

			<div id="gameMainContainer">
				<div id="blankOut"></div>
				<div id="messageBanner"></div>
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
				<div id="gamePageContainer">
					{{block name="content"}}{{/block}}
				</div>
			</div>
			{{include file="main_footer.tpl" nocache}}
		</div>

		{{include file="main_scripts.tpl" bodyclass="full"}}
		<script type="text/javascript">
			var objectID = {{$objectID}};
			var lastAjaxResponse = {};
			{{if $isAdmin}}
				function clearCache() {
					$.post("ajaxRequest.php",
						{"action" : "clearCache", "ajaxType": "DataLoader"},
						function(data){
							if(data.code < 0) {
								showMessage(data.message, "red", 30000);
							} else {
								location.reload();
							}
						},
						"json"
					).fail(function() { $(".invHolder").text("An error occurred while getting data"); });
				}
			{{/if}}

			function winMsgReceiver(channel, payload) {
				if (channel == "winManager" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all") || inArray(payload.msgTarget, "main")) {
						//console.log(payload);
						switch (payload.msgType) {
							case "msgCloseWin": {
								$('li[id="' + payload.msgSender + '"]').css("background-color", "");
								break;
							}
							case "msgWinOpen": {
								$('li[id="' + payload.msgSender + '"]').css("background-color", "#05316D");
								break;
							}
							case "msgNewWin": {
								$('li[id="' + payload.msgSender + '"]').css("background-color", "#05316D");
								break;
							}
						}
					}
				}
			}

			function dataUpdateReceiver(channel, payload) {
				if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all") || inArray(payload.msgTarget, "windows")) {
						//console.log(payload);
						switch (payload.msgType) {
							case "msgUpdateNotifications": {
								loadNotifications(payload.msgData.notificationData);
								break;
							}
						}
					}
				}
			}

			(function($) {
				//Handle window events
				$.jStorage.subscribe("winManager", winMsgReceiver);
				$.jStorage.subscribe("dataUpdater", dataUpdateReceiver);

				//Load Notifications
				//loadNotificationData();

				//Load menu
				$("#gameMenu li.menuWindowItem").each(function() {
					$(this).click(function() {
						popupwindow($(this).attr("data-windowHref"), $(this).attr('id'), $(this).attr('data-windowWidth'), $(this).attr('data-windowHeight'));
					});
				});

				$("#gameMainContainer").css("min-height", $("#gameMenu").height() - 100);

				$.jStorage.publish("winManager", new Message("msgNewMain", null, ["all"], "main"));

				$("#objectListToggle").on("click", function(event){
					if($("#objectListContainer").hasClass("open")) {
						hideObjectList();
					} else {
						showObjectList();
					}
					event.stopPropagation();
				});

				$(document).click(function(event) {
				    if($(event.target).parents().index($('#objectListContainer')) == -1) {
				        hideObjectList();
				    }
				});

				$(window).unload(function() {
					$.jStorage.publish("winManager", new Message("msgCloseMain", null, ["all"], "main"));
				});
			})(jQuery);

			$(window).load(function() {
				$("#gameMainContainer").css("min-height", $("#gameMenu").height() - 100);
			});

			function handleAjax(data) {
				lastAjaxResponse = data;
				if(isset(data.objectItems)) {
					$.jStorage.publish("dataUpdater", new Message("msgUpdateItems", {"objectID" : objectID, "itemData" : data.objectItems}, ["all"], window.name));
				}

				if(isset(data.objectBuildings)) {
					$.jStorage.publish("dataUpdater", new Message("msgUpdateBuildings", {"objectID" : objectID, "buildingData" : data.objectBuildings}, ["all"], window.name));
				}

				if(isset(data.notifications)) {
					$.jStorage.publish("dataUpdater", new Message("msgUpdateNotifications", {"notificationData" : data.notifications}, ["all"], window.name));
				}
			}

			function showObjectList() {
				$("#objectListContainer").addClass('open').show();

				$("#objectList").text("Loading Data...");
				$.post("ajaxRequest.php",
					{"action" : "getObjectList", "ajaxType": "ObjectHandler"},
					function(data){
						if(data.code < 0) {
							$("#objectList").text("Error #" + (-data.code) + ": " + data.message);
						} else {
							$.jStorage.publish("dataUpdater", new Message("msgUpdateObjectList", {"objectList" : data}, ["all"], window.name));
							var objectListItem = Handlebars.templates['objectListItem.tmpl'];
							$("#objectList").text("");
							for ( var i in data) {
								if(i == "code")
									continue;
								$("#objectList").append(objectListItem({
									"objectID" : i,
									"objectName" : data[i].objectName,
									"objectCoord" : data[i].objectCoords,
									"usedStorage" : niceNumber(data[i].usedStorage),
									"maxStorage" : niceNumber(data[i].objStorage),
									"storageColor" : (data[i].usedStorage >= data[i].objStorage) ? "red" : ""
								}));
							}


							$(".objectListItem").on("click", function() {
								document.location = document.location.origin
									+ document.location.pathname
									+ "?page={{$page}}"
									+"&objectID=" + $(this).attr("data-objectID");
							});

							$(".scrollable").tinyscrollbar_update();
							$(".objectListItem[data-objectID=" + objectID + "]").attr("data-active", "true");
						}
					},
					"json"
				).fail(function() { $(".invHolder").text("An error occurred while getting data"); });

			}

			function hideObjectList() {
				$("#objectListContainer").removeClass('open').hide();
			}

			function loadNotificationData() {
				$.post("ajaxRequest.php",
					{"ajaxType": "MessageHandler", "action" : "getNotifications"},
					function(data){
						if(data.code < 0) {
							$("#gameTopContainer .notifications").text("Error #" + (-data.code) + ": " + data.message);
						} else {
							$.jStorage.publish("dataUpdater", new Message("msgUpdateNotifications", {"notificationData" : data}, ["all"], window.name));
						}
					},
					"json"
				).fail(function() { $(".msgHolder").text("An error occurred while getting data"); });
			}

			function loadNotifications(data) {
				$("#gameTopContainer .notifications").html("");

				var notifications = false;
				for (var i in data) {
					if(i == "code") {
						continue;
					}
					notifications = true;
					var dateStr = formatTime(new Date(data[i].messageTime * 1000));

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

					var itemDiv = $('<div></div>')
					    .addClass("item")
					    .addClass(color)
					    .attr("onclick", "popupwindow('game.php?page=notificationWindow', 'winNotifications', 600, 400);")
					    .text(dateStr + "   " + data[i].messageSubject)
					;
					$("#gameTopContainer .notifications").append(itemDiv);
				}

				if(!notifications) {
					$("#gameTopContainer .notifications").html("No Notifications");
				}
			}
		</script>
	</body>
</html>
