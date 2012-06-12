{{block name="title" prepend}}{{"Research"}}{{/block}}
{{block name="additionalStylesheets" append}}
	<link rel="stylesheet" href="resources/css/research.css?v={{$VERSION}}">
	<link rel="stylesheet" href="resources/css/inventory.css?v={{$VERSION}}">
{{/block}}

{{block name="additionalIncluding" append}}
	<script src="handlebars/researchListItem.js?v={{$VERSION}}"></script>
	<script src="handlebars/researchNoteInfo.js?v={{$VERSION}}"></script>
	<script src="handlebars/invObject.js?v={{$VERSION}}"></script>
{{/block}}

{{block name="content"}}
<div id="toggleResearchMaxMain">
	&lt;
</div>
<div id="researchLeftPanel">
	<div class="researchListSearch"></div>
	<div class="researchListHolder scrollable">
		<div class="scrollbar">
			<div class="track">
				<div class="thumb green-over">
					<div class="end"></div>
				</div>
			</div>
		</div>
		<div class="viewport">
			<div id="researchList" class="overview"></div>
		</div>
	</div>
</div>
<div id="researchMainPanel">
	<div id="researchQueueHolder" class="abs stdBorder" style="top: 0; left: 0; right: 0; height: 30px; background: rgba(2,26,58,0.8);">
		<div style='position: relative;	width: 100%; text-align: center; margin-left: -1px;	border: 1px solid #D3D3D3; margin-top: -1px;'>
			Current Research: <span id="researchQueueItemQuantity"></span> notes of <span id="researchQueueItem"></span>
			<div id="researchQueueCancel" class="buttonDiv red-over abs" style="top: 0; height: 14px; left: 0; width: 60px;">
				Cancel
			</div>

			<span id="researchQueueProgressBar" class='mousePointer progressbar countdown' data-progressbar='yes'>
				<span id="text-researchQueueProgressBar" class="ui-progressbar-centerText"></span>
			</span>

		</div>
	</div>
	<div id="researchInfoHolder">
		<div class="stdBorder abs" style="top: 10px; left: 10px; width: 115px; height: 100px; ">
			<img id="researchInfoImage" width="115" height="100">
		</div>
		<div id="researchInfoTitle" class="stdBorder abs" style="top: 10px; left: 136px; right: 10px; height: 15px; background-color: #1E3E5D; text-align: center;"></div>
		<div id="researchInfoDesc" 	class="stdBorder abs" style="top: 35px; left: 135px; right: 10px; height: 75px;"></div>

		<div id="researchInfoControlsHolder" class="stdBorder abs" style="top: 120px; left: 10px; width: 270px; height: 122px;">
			<div id="researchInfoNoteHolder" class="abs mousePointer" style="top: -2px; left: -2px; width: 91px; height: 120px;"></div>
			<div id="researchInfoControls" class="abs stdBorder" style="top: -1px; left: 92px; right: -1px; bottom: -1px;">
				<div class="abs" style="padding-left: 3px; top: 0px; left: -1px; right: -1px; height: 15px; text-align: left;">Total Required: <span id="researchInfoControlInfoTotal"></span></div>
				<div class="abs" style="padding-left: 3px; top: 15px; left: -1px; right: -1px; height: 15px; text-align: left;">Current Progress: <span id="researchInfoControlInfoProgress"></span></div>
				<div class="abs" style="padding-left: 3px; top: 30px; left: -1px; right: -1px; height: 15px; text-align: left;">Notes Required: <span id="researchInfoControlInfoRequired"></span></div>
				<div class="abs" style="padding-left: 3px; top: 45px; left: -1px; right: -1px; height: 15px; text-align: left;">Inventory: <span id="researchInfoControlInfoInventory"></span></div>
				<div class="abs" style="padding-left: 3px; top: 60px; left: -1px; right: -1px; height: 15px; text-align: left;">Research Time: <span id="researchInfoControlInfoTime"></span></div>

				<div id="researchInfoControlShowAddOverlay" class="abs buttonDiv stdBorder green-over" style="bottom: 15px; left: -1px; right: -1px; height: 20px; text-align: center;">Start Research</div>
				<div id="researchInfoLink" class="stdBorder abs buttonDiv" style="bottom: -1px; left: -1px; right: -1px; height: 15px; text-align: center;">View in Research Map &gt;&gt;</div>

			</div>
		</div>

		<div id="researchInfoEffectsControls" class="stdBorder abs" style="top: 252px; left: 10px; width: 270px; height: 20px;">
			<div id="researchInfoEffectsControlsLeft" class="stdBorder abs buttonDiv" style="text-align: center; left: -1px; top: -1px; width: 20px; height: 100%;">&lt;</div>
			<div id="researchInfoEffectsControlsLevel" class="abs" style="text-align: center; left: 20px; right: 20px; height: 100%;"></div>
			<div id="researchInfoEffectsControlsRight" class="stdBorder abs buttonDiv" style="text-align: center; right: -1px; top: -1px; width: 20px; height: 100%; ">&gt;</div>
		</div>
		<div id="researchInfoEffectsHolder" class="stdBorder abs scrollable" style="top: 273px; left: 10px; width: 265px; bottom: 10px; text-align: left; padding-left: 5px;">
			<div class="scrollbar">
			<div class="track">
					<div class="thumb green-over">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div id="researchInfoEffects" class="overview"></div>
			</div>
		</div>

		<div class="stdBorder abs scrollable" style="top: 120px; left: 290px; right: 10px; bottom: 10px;">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb green-over">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div id="researchNoteRequirements" class="overview"></div>
			</div>
        </div>
	</div>
</div>
<div id="researchAddOverlayHolder" class="absFill" style="text-align: center; vertical-align: middle; display: none;">
	<div id="researchAddOverlay" style="z-index: 901; position: relative; top: 20%; margin: auto; width: 450px; height: 275px; background: rgba(3,26,58,0.9); border: 1px #D3D3D3 solid;">
		<div id="researchAddOverlayClose" class="stdBorder abs buttonDiv red-over" style="top: 10px; right: 10px; width: 10px; height: 15px;">X</div>
		<div class="stdBorder abs" style="top: 10px; left: 10px; width: 115px; height: 100px;">
			<img id="researchAddOverlayImage" width="115" height="100"/>
		</div>
		<div id="researchAddOverlayTitle" class="stdBorder abs" style="top: 10px; left: 135px; right: 20px; height: 15px; background-color: #1E3E5D; text-align: center;"></div>
		<div id="researchAddOverlayDesc" class="stdBorder abs" style="top: 35px; left: 135px; right: 10px; height: 75px;"></div>
		<div id="researchAddInfoController" class="stdBorder abs" style="top: 120px; left: 10px; width: 92px; bottom: 10px;">
			<div id="researchAddInfoNoteHolder" class="abs mousePointer" style="top: -2px; left: -2px; width: 90px; height: 120px;"></div>
			<input id="researchInfoControlNumber" class="abs" style="bottom: 0px; left: 0px; right: 0px; height: 15px; text-align: center;" type="number" value="100" min="0">
		</div>
		<div id="researchAddInfoDetails" class="abs stdBorder scrollable" style="bottom: 31px; left: 110px; right: 10px; top: 120px; text-align: center;">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb green-over">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div class="overview">
					Required Time: <span id="researchRequiredTime"></span><br>
					Estimated Completion Time: <span id="researchCompletionTime"></span>
					<table class="scaffold" style="text-align: left;">
						<tr>
							<td style="padding-bottom: 5px;">
								Start Cost:
							</td>
							<td style="padding-bottom: 5px;">
								<span id="researchStartCost"></span>
							</td>
						</tr>
						<tr>
							<td style="padding-bottom: 5px;">
								Ongoing Cost:
							</td>
							<td style="padding-bottom: 5px;">
								<span id="researchConsumptionCost"></span>
							</td>
						</tr>
						<tr>
							<td style="padding-bottom: 5px;">
								Total Cost:
							</td>
							<td style="padding-bottom: 5px;">
								<span id="researchTotalCost"></span>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="researchInfoControlAddToQueue" class="abs buttonDiv stdBorder green-over" style="bottom: 10px; left: 110px; right: 10px; height: 20px; text-align: center;">Loading</div>
	</div>
</div>
{{/block}}

{{block name="winHandlers" append}}
	<script>
		var objectID = {{$objectID}};
		var lastSelection = null;
		$('#gamePageContainer').css("height", "100%");
		$("#gameMenu").ready(function() {
			$("#gameMenu #pageResearch").addClass("active");
		});

		$(document).on('gameDataLoaded', function() {
			$("#toggleResearchMaxMain").on("click", function(){
				if($(this).hasClass("maxMain")) {
					$(this).removeClass('maxMain');
					$(this).css("left", 251);
					$("#researchLeftPanel").show();
					$("#researchMainPanel").css("left", 251);
					$(this).text("<");
				} else {
					$(this).addClass('maxMain');
					$(this).css("left", 0);
					$("#researchLeftPanel").hide();
					$("#researchMainPanel").css("left", 0);
					$(this).text(">");
				}
				updateAllScrollbars();
			});

			$.jStorage.subscribe("dataUpdater", function(channel, payload) {
				if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all")) {
						switch (payload.msgType) {
							case "msgUpdateResearchInfo":
								parseResearchData(payload.msgData.researchData);
								latestGameData.researchData = payload.msgData.researchData;
								loadResearchList(payload.msgData.researchData);
								break;

							case "msgUpdateItems":
								if(payload.msgData.objectID == objectID) {
									parseItemData(payload.msgData.itemData);
									latestGameData.objectItems = payload.msgData.itemData;
									loadItemHover(latestGameData);
								}
								break;

							case "msgUpdateBuildings":
								if(payload.msgData.objectID == objectID) {
									parseBuildingData(payload.msgData.buildingData);
									latestGameData.objectBuildings = payload.msgData.buildingData;
									loadBuidingHover(latestGameData);
								}
								break;

							case "msgUpdateResearchQueue":
								if(payload.msgData.objectID == objectID) {
									latestGameData.researchQueue = payload.msgData.researchQueue;
									loadResearchQueue(payload.msgData.researchQueue);
								}
								break;
						}
					}
				}
			});

			getObjectResearchData(objectID);
		});

		function handleResearchAjax(data) {
			if(data.code < 0) {
				showMessage("Error " + (-data.code) + ": " + data.message, "red", 30000);
			}
			handleAjax(data);

			if(isset(data.researchData)) {
				$.jStorage.publish("dataUpdater", new Message("msgUpdateResearchInfo", {"researchData" : data.researchData}, ["all"], window.name));
			}

			if(isset(data.researchQueue)) {
				$.jStorage.publish("dataUpdater", new Message("msgUpdateResearchQueue", {"objectID": objectID, "researchQueue" : data.researchQueue}, ["all"], window.name));
			}
		}

		function getObjectResearchData(objectID) {
			$.post("ajaxRequest.php",
				{"action" : "getObjectResearch", "ajaxType": "ResearchHandler", "objectID": objectID},
				handleResearchAjax,
				"json"
			).fail(function() {showMessage("An error occurred while getting data", "red", 30000);});
		}

		function loadResearchQueue(researchQueue) {
			var researchQueueHolder = $("#researchQueueHolder");
			var researchQueueProgressbar = $("#researchQueueProgressBar");
			var researchInfoHolder = $("#researchInfoHolder");
			if(!isEmpty(researchQueue)) {
				researchQueueHolder.show();
				researchQueueProgressbar.addClass("countdown");
				researchInfoHolder.css("top", 31);

				var researchQueueCountdownText = $("#text-researchQueueProgressBar");
				$("#researchQueueCancel").unbind("click").bind("click", function(){
					$.post(
						"ajaxRequest.php",
						{"action" : "cancelResearchQueueItem", "ajaxType": "ResearchHandler", "objectID": objectID, "queueItemID": researchQueue.id},
						handleResearchAjax,
						"json"
					).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
				});

				$("#researchQueueItemQuantity").text(researchQueue.numQueued);
				$("#researchQueueItem").text(latestGameData.researchData[researchQueue.techID].techName);
				$("#researchQueueProgressBar")
					.attr("data-beginning", researchQueue.startTime)
					.attr("data-end", researchQueue.endTime)
					.attr("data-callback", "getResearchData();")
					.progressbar({
						value: 1,
						max: researchQueue.endTime - researchQueue.startTime,
						change: function() {
							researchQueueCountdownText.text(
								niceETA(
									moment.duration($(this).progressbar("option", "max") - $(this).progressbar("value"), 'seconds')
								) + " left"
							);
						},
						complete: function() {
							$("#text-researchQueueProgressBar").text( "Complete!" );
						}
					});
			} else {
				researchQueueHolder.hide();
				researchQueueProgressbar.removeClass("countdown");
				researchInfoHolder.css("top", 0);
			}
		}

		function loadResearchList(researchData) {
			var researchListItem = Handlebars.templates['researchListItem.tmpl'];

			var researchList = $("#researchList").text("");
			var researchesAvaliable = false;
			for ( var i in researchData ) {
				var data = researchData[i];
				if(data.canResearch(researchData)) {
					if(!researchesAvaliable) {
						researchesAvaliable = data.techID;
					}

					researchList.append(researchListItem({
						"techID" : data.techID,
						"techName" : data.techName,
						"techImage" : data.techImage,
						"techLevel" : data.techLevel,
						"techPoints" : data.techPoints,
						"techPointsReq" : data.getTotalNotesRequired(),
						"techColor" : data.getResearchColor(researchData)
					}));
				}
			}
			if(researchesAvaliable) {
				$(".researchListItem").on("click", function() {
					loadResearchInfo(researchData, $(this).attr("data-techID"));
				});
				if(!lastSelection) {
					lastSelection = researchesAvaliable;
				}
				loadResearchInfo(researchData, lastSelection);
			} else {
				researchList.text("No Researches Avaliable!");
				$("#researchInfoHolder").text("No Researches Avaliable!");
			}
		}

		function loadResearchInfo(researchData, techID) {
			lastSelection = techID;
			$("[data-active=true]").attr("data-active", "");
			$(".researchListItem[data-techID=" + techID + "]").attr("data-active", "true");

			var tech = researchData[techID];

			$("#researchInfoImage").attr("src", "resources/images/research/" + tech.techImage);
			$("#researchInfoTitle").text(tech.techName);
			$("#researchInfoDesc").text(tech.techDesc);
			$("#researchInfoEffects").html(tech.getResearchEffect(Math.max(tech.techLevel, 1)));

			$("#researchInfoLink").unbind('click').bind('click', function() {
				document.location = "game.php?page=researchmap&techID=" + techID;
			});

			$("#researchInfoEffectsControlsLevel").text(Math.max(tech.techLevel, 1)).attr("data-level", Math.max(tech.techLevel, 1)).attr("data-techID", tech.techID);

			$('#researchInfoEffectsControlsRight').unbind('click').bind('click', function() {
				var level = parseInt($("#researchInfoEffectsControlsLevel").attr("data-level"));
				if(level < tech.techLevel + 10) {
					var newLevel = level + 1;
					$("#researchInfoEffects").html(tech.getResearchEffect(newLevel));
					$("#researchInfoEffectsControlsLevel").text(newLevel).attr("data-level", newLevel).attr("data-techID", tech.techID);
					loadModHover();
					updateAllScrollbars();
				}
			});

			$('#researchInfoEffectsControlsLeft').unbind('click').bind('click', function() {
				var level = parseInt($("#researchInfoEffectsControlsLevel").attr("data-level"));
				if(level > 1) {
					var newLevel = level - 1;
					$("#researchInfoEffects").html(tech.getResearchEffect(newLevel));
					$("#researchInfoEffectsControlsLevel").text(newLevel).attr("data-level", newLevel).attr("data-techID", tech.techID);
					loadModHover();
					updateAllScrollbars();
				}
			});
			loadResearchNoteInfo(researchData, techID);
			loadResearchControls(researchData, techID);
			loadHovers(latestGameData);
		}

		function loadResearchControls(researchData, techID) {
			var item = new Item("research-notes_" + techID);
			var research = researchData[techID];

			item.quantity = research.getTotalNotesRequired();
			var template = Handlebars.templates['invObject.tmpl'];
			var html = $(template({
				quantity: item.quantity,
				itemName: item.itemName,
				itemImage: item.itemImage
			}));

			html.addClass("type_" + item.itemType.replace(" ", "_"));
			$("#researchInfoNoteHolder").html(
				html.each(function() {
					if($(this).hasClass("tt-init")) {
						$(this).tooltip("option", "content", function() {
							return item.getHoverContent();
						});
					} else {
						staticTT(
							$(this),
							{
								content : function() {
									return item.getHoverContent();
								},
								show: { delay: 600, effect: "show" }
							}
						);
					}
				})
			);

			var notesRequired = research.getTotalNotesRequired();
			$("#researchInfoControlInfoTotal").text(notesRequired);
			$("#researchInfoControlInfoProgress").text(research.techPoints);
			$("#researchInfoControlInfoRequired").text(notesRequired - research.techPoints);

			var currentInv = isset(latestGameData.objectItems["research-notes_" + techID]) ? latestGameData.objectItems["research-notes_tech1"].quantity : 0;
			$("#researchInfoControlInfoInventory").text(currentInv);
			$("#researchInfoControlInfoTime").text(niceETA(moment.duration(research.getResearchTime(latestGameData), 'seconds')));
			$("#researchInfoControlNumber").val(Math.max(notesRequired - research.techPoints - currentInv, 0));

			$("#researchInfoControlShowAddOverlay").unbind('click').bind('click', function() {
				showResearchAddOverlay(researchData, techID);
			});
		}

		function loadResearchNoteInfo(researchData, techID) {
			var tech = researchData[techID];
			var researchNoteInfo = Handlebars.templates['researchNoteInfo.tmpl'];
			$("#researchNoteRequirements").html(researchNoteInfo(tech));
		}

		function showResearchAddOverlay(researchData, techID) {
			var tech = researchData[techID];

			$("#researchAddOverlayImage").attr("src", "resources/images/research/" + tech.techImage);
			$("#researchAddOverlayTitle").text(tech.techName);
			$("#researchAddOverlayDesc").text(tech.techDesc);

			$("#researchInfoControlNumber").on("change", function() {
				var value = $(this).val();
				updateResearchOverlayQuantity(researchData, techID, value);
			});

			function hideInfoBox() {
				$("#blankOut").hide();
				$("#researchAddOverlayHolder").hide();
			}

			$("#researchInfoControlAddToQueue").unbind('click').bind('click', function() {
				$.post(
					"ajaxRequest.php",
					{"action" : "startResearch", "ajaxType": "ResearchHandler", "objectID": objectID, "techID": techID, "numberNotes": $("#researchInfoControlNumber").val()},
					handleResearchAjax,
					"json"
				).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
				hideInfoBox();
			});

			$("#researchAddOverlayHolder").show();
			$("#blankOut").show().on("click", hideInfoBox);

			updateResearchOverlayQuantity(researchData, techID, $("#researchInfoControlNumber").val());
			$("#researchAddOverlayClose").on("click", hideInfoBox);
		}

		function updateResearchOverlayQuantity(researchData, techID, quantity) {
			var tech = researchData[techID];
			var item = new Item("research-notes_" + techID);
			item.quantity = quantity;

			var template = Handlebars.templates['invObject.tmpl'];
			var html = $(template({
				quantity: item.quantity,
				itemName: item.itemName,
				itemImage: item.itemImage
			}));

			html.addClass("type_" + item.itemType.replace(" ", "_"));
			$("#researchAddInfoNoteHolder").html(
				html.each(function() {
					if($(this).hasClass("tt-init")) {
						$(this).tooltip("option", "content", function() {
							return item.getHoverContent();
						});
					} else {
						staticTT(
							$(this),
							{
								content : function() {
									return item.getHoverContent();
								},
								show: { delay: 600, effect: "show" }
							}
						);
					}
				})
			);

			$("#researchInfoControlAddToQueue").text("Create " + quantity + " research Notes");
			$("#researchRequiredTime").text(niceETA(moment.duration(tech.getResearchTime(latestGameData) * quantity, 'seconds')));
			$("#researchCompletionTime").text(formatDateTime(moment().add(moment.duration(tech.getResearchTime(latestGameData) * quantity, 'seconds'))));

			var startCostHolder = $("#researchStartCost").text("");
			var startCost = multiplyEach(clone(tech.researchNoteCost), quantity);
			for ( var i in startCost ) {
				var res = startCost[i];
				startCostHolder.append($('<span class="itemLink" data-item="' + i + '" data-parameters="' + res + '"></span>'));
			}

			var ongoingCostHolder = $("#researchConsumptionCost").text("");
			var ongoingCost = multiplyEach(clone(tech.researchNoteConsumption), tech.getResearchTime(latestGameData) * quantity / 3600);
			for ( var i in ongoingCost ) {
				var res = ongoingCost[i];
				ongoingCostHolder.append($('<span class="itemLink" data-item="' + i + '" data-parameters="' + res + '"></span>'));
			}

			var totalCostHolder = $("#researchTotalCost").text("");
			var totalCost = objAdd(clone(startCost), ongoingCost);
			for ( var i in totalCost ) {
				var res = totalCost[i];
				totalCostHolder.append($('<span class="itemLink" data-item="' + i + '" data-parameters="' + res + '"></span>'));
			}

			loadItemHover(latestGameData);
			updateAllScrollbars();
		}

	</script>
{{/block}}
