{{block name="title" prepend}}{{"Info"}}{{/block}}
{{block name="additionalStylesheets" append}}
	<link rel="stylesheet" href="resources/css/buildings.css?v={{$VERSION}}">
{{/block}}
{{block name="additionalIncluding" append}}
	<script src="handlebars/buildings/buildingQueueItem.js?v={{$VERSION}}"></script>
	<script src="handlebars/objInfoEconomyRow.js?v={{$VERSION}}"></script>
	<script src="handlebars/objInfoModifierRow.js?v={{$VERSION}}"></script>
	<script src="handlebars/objInfoResearchRow.js?v={{$VERSION}}"></script>
{{/block}}

{{block name="content"}}
<table class="pageTable">
	<tr>
		<th>{{$objectTypeName}} Overview - {{$objectName}}</th>
	</tr>
	<tr>
		<td>
			<table class="innerTable">
				<tr>
					<td rowspan="10" style="width: 40%;"><img style="float: right; padding-right: 20px;" src="http://placehold.it/150x150" /></td>
					<td style="width: 60%; font-size: 18px;">{{$objectName}}</td>
				</tr>
				<tr>
					<td>{{$objectTypeName}} Location: {{$objectCoord}}</td>
				</tr>
				<tr>
					<td>Planet Owner: <span id="ownerName"></span> (<span id="ownerAlliance"></span>)</td>
				</tr>
				<tr>
					<td>Planet Type: <span id="planetType"></span></td>
				</tr>
				<tr>
					<td>Planet Size: <span id="planetSize"></span></td>
				</tr>
				<tr>
					<td>Planet Temperature: <span id="planetTemp"></span></td>
				</tr>
				<tr>
					<td>Planet Humidity: <span id="planetHumidity"></span></td>
				</tr>
				<tr>
					<td>Number of buildings: <span id="numBuildings"></span></td>
				</tr>
				<tr>
					<td>Storage Capacity: <span id="storageUsed"></span></td>
				</tr>
				<tr>
					<td>Fleet Capacity: <span id="fleetCapacity"></span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>NOTE: The folowing information does not reflect reduced production due to missing consumption resources</td>
	</tr>
	<tr>
		<td>
			<table class="innerTable">
				<tr>
					<th style="text-align: center; font-size: 12px;">Construction</th>
				</tr>
				<td id="constructionQueue"></td>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="innerBorderedTable" id="tableEconomy">
				<tr>
					<th colspan="4" style="text-align: center; font-size: 12px;">Economy</th>
				</tr>
				<tr style="font-weight: bold;">
					<td style="width: 25%;"></td>
					<td>Hourly Production</td>
					<td>Hourly Consumption</td>
					<td style="width: 5%; min-width: 70px;">Activity</td>
				</tr>
				<tr style="font-weight: bold;">
					<td>Net Hourly Change</td>
					<td colspan="2" id="econNetChange" style="text-align: center;"></td>
					<td style="vertical-align: top;" ><div class="green-over buttonDiv" onclick="updateBuildingActivity();">Update All</div></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="innerBorderedTable" id="tableModifiers">
				<tr>
					<th colspan="3" style="text-align: center; font-size: 12px;">Modifiers</th>
				</tr>
				<tr style="font-weight: bold;">
					<td style="width: 25%;"></td>
					<td>Modifiers</td>
					<td style="width: 5%; min-width: 70px;">Activity</td>
				</tr>
				<tr style="font-weight: bold;">
					<td>Net Modifiers</td>
					<td id="modifierTotal" style="text-align: center;"></td>
					<td style="vertical-align: top;" ><div class="green-over buttonDiv"onclick="updateBuildingActivity();">Update All</div></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<br>
<br>
{{/block}}

{{block name="winHandlers" append}}
	<script>
		var objectID = {{$objectID}};
		$("#gameMenu").ready(function() {
			$("#gameMenu #pageObjectOverview").addClass("active");
		});

		var lastBuildingData = {};
		var lastObjectModData = {};
		var lastObjectWeightPenalty = {};

		var econRowTemplate = Handlebars.templates['objInfoEconomyRow.tmpl'];
		var modRowTemplate = Handlebars.templates['objInfoModifierRow.tmpl'];

		$(document).on('gameDataLoaded', function() {
			$.jStorage.subscribe("dataUpdater", function(channel, payload) {
				if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all")) {
						switch (payload.msgType) {
							case "msgUpdateResearchInfo":
								parseResearchData(payload.msgData.researchData);
								latestGameData.researchData = payload.msgData.researchData;
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

							case "msgUpdateBuildingData":
								if(payload.msgData.objectID == objectID) {
									loadObjectBuildingInfo(payload.msgData.buildingData);
								}
								break;

							case "msgUpdateBuildingQueue":
								if(payload.msgData.objectID == objectID) {
									loadBuidingQueue(payload.msgData.buildQueue);
								}
								break;

							case "msgUpdateObjectData":
								if(payload.msgData.objectID == objectID) {
									if(isset(payload.msgData.objectModifiers)) {
										if(!isEmpty(payload.msgData.objectModifiers)){
											$(".genObjectMod").remove();
											$('#tableModifiers tr:last').before(modRowTemplate({
												itemName: "Planet Bonuses",
												modifiers: payload.msgData.objectModifiers,
												class: "genObjectMod"
											}));
											lastObjectModData = payload.msgData.objectModifiers;
											loadObjectTotals();
										}
									}

									if(isset(payload.msgData.objectWeightPenalty)) {
										if(!isEffectivelyEmpty(payload.msgData.objectWeightPenalty)){
											$(".genWeightPenalty").remove();
											$('#tableModifiers tr:last').before(modRowTemplate({
												itemName: "Storage Overflow Penalty",
												modifiers: payload.msgData.objectWeightPenalty,
												class: "genWeightPenalty"
											}));
											lastObjectWeightPenalty = payload.msgData.objectWeightPenalty;
											loadObjectTotals();
										}
									}

									if(isset(payload.msgData.usedStorage) && isset(payload.msgData.objStorage)) {
										var usedStorageText = $("#storageUsed").text(niceNumber(payload.msgData.usedStorage) + " / " + niceNumber(payload.msgData.objStorage));
										if(payload.msgData.usedStorage >= payload.msgData.objStorage) {
											usedStorageText.addClass("red");
										} else {
											usedStorageText.removeClass("red");
										}
									}

									if(isset(payload.msgData.objEnergyStorage)) {}

									if(isset(payload.msgData.numBuildings)) {
										$("#numBuildings").text(payload.msgData.numBuildings);
									}

									if(isset(payload.msgData.objectData)) {
										$("#planetType").text(payload.msgData.objectData.planetType);
										$("#planetSize").text(payload.msgData.objectData.planetSize);
										$("#planetTemp").text(payload.msgData.objectData.planetTemp);
										$("#planetHumidity").text(payload.msgData.objectData.planetHumidity);
									}
								}
								break;
						}
					}
				}
			});

			getOverviewData();
		});

		function handleOverviewAjax(data) {
			if(data.code < 0) {
				showMessage("Error " + (-data.code) + ": " + data.message, "red", 30000);
			}
			handleAjax(data);

			if(isset(data.buildingData)) {
				$.jStorage.publish("dataUpdater", new Message("msgUpdateBuildingData", {"objectID" : objectID, "buildingData" : data.buildingData}, ["all"], window.name));
			}

			if(isset(data.buildQueue)) {
				$.jStorage.publish("dataUpdater", new Message("msgUpdateBuildingQueue", {"objectID" : objectID, "buildQueue" : data.buildQueue}, ["all"], window.name));
			}

			$.jStorage.publish("dataUpdater", new Message(
				"msgUpdateObjectData",
				{
					"objectID" : objectID,
					"objectModifiers" : data.objectModifiers,
					"objectWeightPenalty" : data.objectWeightPenalty,
					"usedStorage" : data.usedStorage,
					"objStorage" : data.objStorage,
					"objEnergyStorage" : data.objEnergyStorage,
					"numBuildings" : data.numBuildings,
					"objectData" : data.objectData
				},
				["all"],
				window.name)
			);
		}

		function getOverviewData() {
			$.post(
				"ajaxRequest.php",
				{"action" : "getObjectInfo", "ajaxType": "ObjectHandler", "objectID": objectID},
				handleOverviewAjax,
				"json"
			).fail(function() { $("#tabContainer").text("An error occurred while getting data"); });
		}

		function loadBuidingQueue(buildQueue) {
			var queueHolder = $("#constructionQueue").html("");
			var buildingQueueTemplate = Handlebars.templates['buildingQueueItem.tmpl'];

			if(typeof buildQueue[0] !== 'undefined') {
				var uid = buildQueue[0].id;
				queueHolder.append(buildingQueueTemplate({
					operation: buildQueue[0].operation,
					buildName: dbBuildData[buildQueue[0].buildingID].buildName,
					buildLevel: buildQueue[0].buildingLevel,
					startTime: buildQueue[0].startTime,
					endTime: buildQueue[0].endTime,
					callback: "getOverviewData();",
					id: uid
				}));

				$("#" + uid).progressbar({
					value: 0,
					max: buildQueue[0].endTime - buildQueue[0].startTime,
					change: function() {
						$("#text-" + uid).text(
							niceETA(
								moment.duration($("#" + uid).progressbar("option", "max") - $("#" + uid).progressbar("value"), 'seconds')
							) + " left"
						);
					},
					complete: function() {
						$("#text-" + uid).text( "Complete!" );
					}
				});

				for(var i = 1; i < buildQueue.length; i++) {
					queueHolder.append(
						buildingQueueTemplate({
							operation: buildQueue[i].operation,
							buildName: dbBuildData[buildQueue[i].buildingID].buildName,
							buildLevel: buildQueue[i].buildingLevel,
							id: buildQueue[i].id
						})
					);
				}

				//Load buttons
				$(".buildingQueueCancel").on("click", function(){
					var queueID = $(this).attr("data-id");
					$.post(
						"ajaxRequest.php",
						{"action" : "cancelBuildingQueueItem", "ajaxType": "BuildingHandler", "objectID": objectID, "queueItemID": queueID},
						handleOverviewAjax,
						"json"
					).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
				});
			} else {
				queueHolder.text("No construction in progress");
			}
		}

		function loadObjectTotals() {
			var economyTotal = {};
			var modifierTotal = {};
			$(".genTotal").remove();

			objAdd(modifierTotal, lastObjectWeightPenalty);
			objAdd(modifierTotal, lastObjectModData);

			for(var key in lastBuildingData) {
				var obj = lastBuildingData[key];
				mergeItemData(economyTotal, obj.curResProduction, "+");
				mergeItemData(economyTotal, obj.curResConsumption, "-");

				if(obj.curModifiers) {
					objAdd(modifierTotal, obj.curModifiers);
				}
			}

			//Load totals
			for(var key in economyTotal) {
				$("#econNetChange").append("<span class='itemLink genTotal' data-type='diff' data-item='" + key + "'  data-parameters='" + JSON.stringify(economyTotal[key]) +"'></span>");
			}

			for(var key in modifierTotal) {
				$("#modifierTotal").append("<span class='modLink genTotal' data-modID='" + key + "' data-amount='" + modifierTotal[key] +"'></span>");
			}

			loadHovers(latestGameData);
		}

		function loadObjectBuildingInfo(buildingData) {
			$(".genBuilding").remove();
			for(var key in buildingData) {
				var obj = buildingData[key];
				var building = new Building(key, [obj.level, obj.activity]);
				if(!(isEmpty(building.getBaseBuildingConsumption()) && isEmpty(building.getBaseBuildingProduction()))) {
					$('#tableEconomy tr:last').before(econRowTemplate({
						itemName: "Level " + obj.level + " " + dbBuildData[key].buildName,
						production: obj.curResProduction,
						consumption: obj.curResConsumption,
						activity: obj.activity,
						class: "genBuilding",
						id: key
					}));
				}

				if(obj.curModifiers) {
					$('#tableModifiers tr:last').before(modRowTemplate({
						itemName: "Level " + obj.level + " " + dbBuildData[key].buildName,
						modifiers: obj.curModifiers,
						activity: obj.activity,
						class: "genBuilding",
						id: key
					}));
				}

				$(".activity_"+key).on("change", function() {
					var value = $(this).val();
					$(".activity_"+$(this).attr("data-id")).each(function() {
						$(this).val(value);
					});
				});
			}
			lastBuildingData = buildingData;
			loadObjectTotals();
		}

		function updateBuildingActivity () {
			var newData = {};

			$(".activityInput").each(function() {
				newData[$(this).attr("data-id")] = $(this).val();
			});

			$.post(
				"ajaxRequest.php",
				{"action" : "setAllBuildingActivity", "ajaxType": "BuildingHandler", "objectID": objectID, "activityData": JSON.stringify(newData)},
				handleOverviewAjax,
				"json"
			).fail(function() { showMessage("An error occurred while updating activity data", "red", 30000);});

			getOverviewData();
		}
	</script>
{{/block}}
