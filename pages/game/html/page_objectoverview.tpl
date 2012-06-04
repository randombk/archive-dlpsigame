{{block name="title" prepend}}{{"Info"}}{{/block}}
{{block name="additionalIncluding" append}}
	<link rel="stylesheet" href="resources/css/buildings.css?v={{$VERSION}}">
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
	<tr><td>NOTE: The folowing information does not reflect reduced production due to missing consumption resources</td></tr>
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
	<tr>
		<td>
			<table class="innerBorderedTable" id="tableResearch">
				<tr>
					<th colspan="7" style="text-align: center; font-size: 12px;">Research Output</th>
				</tr>
				<tr style="font-weight: bold;">
					<td style="width: 25%;"></td>
					<td>Weapons Research</td>
					<td>Defense Research</td>
					<td>Diplomatic Research</td>
					<td>Economic Research</td>
					<td>Fleet Research</td>
					<td style="width: 5%; min-width: 70px;">Activity</td>
				</tr>
				<tr style="font-weight: bold;">
					<td>Total Research Output</td>
					<td id="researchWeaponsTotal" style="text-align: center;"></td>
					<td id="researchDefenseTotal" style="text-align: center;"></td>
					<td id="researchDiplomaticTotal" style="text-align: center;"></td>
					<td id="researchEconomicTotal" style="text-align: center;"></td>
					<td id="researchFleetTotal" style="text-align: center;"></td>
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

		//Load building data
		(function($) {
			$(document).on('gameDataLoaded', function() {
				loadData();
			});

			$.jStorage.subscribe("dataUpdater", function(channel, payload) {
				if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all")) {
						switch (payload.msgType) {
							case "msgUpdateResearchInfo":
								parseResearchData(payload.msgData.researchData.research);
								lastAjaxResponse.researchData = payload.msgData.researchData.research;
								break;

							case "msgUpdateItems":
								if(payload.msgData.objectID == objectID) {
									parseItemData(payload.msgData.itemData);
									lastAjaxResponse.objectItems = payload.msgData.itemData;
									loadItemHover(lastAjaxResponse);
								}
								break;

							case "msgUpdateBuildings":
								if(payload.msgData.objectID == objectID) {
									parseBuildingData(payload.msgData.buildingData);
									lastAjaxResponse.objectBuildings = payload.msgData.buildingData;
									loadBuidingHover(lastAjaxResponse);
								}
								break;

							case "msgUpdateObjectInfo":
								if(payload.msgData.objectID == objectID) {
									loadObjectInfoPage(payload.msgData.objectInfo);
								}
								break;

						}
					}
				}
			});
		})(jQuery);

		function loadData() {
			$.post("ajaxRequest.php",
				{"action" : "getObjectInfo", "ajaxType": "ObjectHandler", "objectID": objectID},
				function(data){
					if(data.code < 0) {
						$("#constructionQueue").text("Fatal Error #" + (-data.code) + ": " + data.message);
					} else {
						handleAjax(data);
						$.jStorage.publish("dataUpdater", new Message("msgUpdateObjectInfo", {"objectID" : objectID, "objectInfo" : data}, ["all"], window.name));
					}
				},
			"json")
			.fail(function() { $("#tabContainer").text("An error occurred while getting data"); })
			.always(function() {  });
		}

		function loadObjectInfoPage(data) {
			$.jStorage.publish("dataUpdater", new Message("msgUpdateItems", {"objectID" : objectID, "itemData" : data.items}, ["all"], window.name));
			$(".gen").remove();
			//Load object info

			$("#planetType").text(data.objectData.planetType);
			$("#planetSize").text(data.objectData.planetSize);
			$("#planetTemp").text(data.objectData.planetTemp);
			$("#planetHumidity").text(data.objectData.planetHumidity);
			$("#numBuildings").text(data.numBuildings);
			$("#storageUsed").text(niceNumber(data.usedStorage) + " / " + niceNumber(data.objStorage));
			if(data.usedStorage >= data.objStorage) {
				$("#storageUsed").addClass("red");
			} else {
				$("#storageUsed").removeClass("red");
			}

			//Load building queue
			$("#constructionQueue").html("");
			var buildingQueueTemplate = Handlebars.templates['buildingQueueItem.tmpl'];

			if(typeof data.buildQueue[0] !== 'undefined') {
				var uid = data.buildQueue[0].id;
				$("#constructionQueue").append(buildingQueueTemplate({
					operation: data.buildQueue[0].operation,
					buildName: dbBuildData[data.buildQueue[0].buildingID].buildName,
					buildLevel: data.buildQueue[0].buildingLevel,
					startTime: data.buildQueue[0].startTime,
					endTime: data.buildQueue[0].endTime,
					id: uid
				}));

				$("#" + uid).progressbar({
	                value: 0,
	                max: data.buildQueue[0].endTime - data.buildQueue[0].startTime,
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

				for(var i = 1; i < data.buildQueue.length; i++) {
					$("#constructionQueue").append(
						buildingQueueTemplate({
							operation: data.buildQueue[i].operation,
							buildName: dbBuildData[data.buildQueue[i].buildingID].buildName,
							buildLevel: data.buildQueue[i].buildingLevel,
							id: data.buildQueue[i].id
						})
					);
				}

				//Load buttons
				$(".buildingQueueCancel").on("click", function(){
					var queueID = $(this).attr("data-id");
					$.post(
						"ajaxRequest.php",
						{"action" : "cancelBuildingQueueItem", "ajaxType": "BuildingHandler", "objectID": objectID, "queueItemID": queueID},
						function(data){
							if(data.code < 0) {
								loadNotificationData();
							} else {
								handleAjax(data);
								loadData();
							}
						},
						"json"
					).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
				});
			} else {
				$("#constructionQueue").text("No construction in progress");
			}

			var econRowTemplate = Handlebars.templates['objInfoEconomyRow.tmpl'];
			var modRowTemplate = Handlebars.templates['objInfoModifierRow.tmpl'];
			var researchRowTemplate = Handlebars.templates['objInfoResearchRow.tmpl'];

			var economyTotal = {};
			var modifierTotal = {};
			var researchTotal = {
				"Weapons": 0,
				"Defense": 0,
				"Diplomatic": 0,
				"Economic": 0,
				"Fleet": 0
			};

			//Load planet modifiers
			if(!isEmpty(data.objectModifiers)){
				objAdd(modifierTotal, data.objectModifiers);
				$('#tableModifiers tr:last').before(modRowTemplate({
					itemName: "Planet Bonuses",
					modifiers: data.objectModifiers
				}));
			}
			//Load storage penalties
			if(data.usedStorage >= data.objStorage) {
				objAdd(modifierTotal, data.objectWeightPenalty);
				$('#tableModifiers tr:last').before(modRowTemplate({
					itemName: "Storage Overflow Penalty",
					modifiers: data.objectWeightPenalty
				}));
			}

			//Load buildings
			for(var key in data.buildings) {
				var obj = data.buildings[key];
				if(!(isEmpty(obj.curResProduction) && isEmpty(obj.curResConsumption))) {
					mergeItemData(economyTotal, obj.curResProduction, "+");
					mergeItemData(economyTotal, obj.curResConsumption, "-");

					$('#tableEconomy tr:last').before(econRowTemplate({
						itemName: "Level " + obj.level + " " + dbBuildData[key].buildName,
						production: obj.curResProduction,
						consumption: obj.curResConsumption,
						activity: obj.activity,
						id: key
					}));

				}

				if(obj.curModifiers) {
					objAdd(modifierTotal, obj.curModifiers);

					$('#tableModifiers tr:last').before(modRowTemplate({
						itemName: "Level " + obj.level + " " + dbBuildData[key].buildName,
						modifiers: obj.curModifiers,
						activity: obj.activity,
						id: key
					}));
				}

				if(!isEmpty(obj.curResearch)) {
					objAdd(researchTotal, obj.curResearch);

					$('#tableResearch tr:last').before(researchRowTemplate({
						itemName: "Level " + obj.level + " " + dbBuildData[key].buildName,
						research: obj.curResearch,
						activity: obj.activity,
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

			//Load totals
			for(var key in economyTotal) {
				$("#econNetChange").append("<span class='itemLink gen' data-type='diff' data-item='" + key + "'  data-parameters='" + JSON.stringify(economyTotal[key]) +"'></span>");
			}

			for(var key in modifierTotal) {
				$("#modifierTotal").append("<span class='modLink gen' data-modID='" + key + "' data-amount='" + modifierTotal[key] +"'></span>");
			}

			for(var key in researchTotal) {
				$("#research" + key + "Total").text(researchTotal[key]);
			};

			loadHovers(data);
		}

		function updateBuildingActivity () {
			var newData = {};

			$(".activityInput").each(function() {
				newData[$(this).attr("data-id")] = $(this).val();
			});

			$.post("ajaxRequest.php",
				{"action" : "setAllBuildingActivity", "ajaxType": "BuildingHandler", "objectID": objectID, "activityData": JSON.stringify(newData)},
				function(data){
					if(data.code < 0) {
						showMessage("Fatal Error #" + (-data.code) + ": " + data.message, "red", 30000);
					}
				},
			"json")
			.fail(function() { showMessage("An error occurred while updating activity data", "red", 30000);})
			.always(function() {  });

			loadData();
		}
	</script>
{{/block}}
