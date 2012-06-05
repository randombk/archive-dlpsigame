{{block name="title" prepend}}{{"Info"}}{{/block}}
{{block name="additionalStylesheets" append}}
	<link rel="stylesheet" href="resources/css/buildings.css?v={{$VERSION}}">
{{/block}}

{{block name="additionalIncluding" append}}
	<script src="handlebars/buildings/buildingBox.js?v={{$VERSION}}"></script>
	<script src="handlebars/buildings/buildingInfo.js?v={{$VERSION}}"></script>
	<script src="handlebars/buildings/buildingQueueItem.js?v={{$VERSION}}"></script>
{{/block}}

{{block name="content"}}
<table class="pageTable">
	<tr>
		<th>Building Management</th>
	</tr>
	<tr>
		<td id="buildingQueue"></td>
	</tr>
	<tr>
		<td id="tabContainer">
			<div id='tabs'>
				<span data-tab='resource'>Resource</span>
				<span data-tab='rnd'>Research and Development</span>
				<span data-tab='military'>Military</span>
				<span data-tab='special'>Special</span>
			</div>
			<div id='tabContent'>
				<div id='resource' 	class="tabContentHolder"></div>
				<div id='rnd' 		class="tabContentHolder"></div>
				<div id='military' 	class="tabContentHolder"></div>
				<div id='special' 	class="tabContentHolder"></div>
			</div>
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
		$("#gameMenu #pageBuildings").addClass("active");
	});

	//Load tabs
	$("#tabContainer").ready(function() {
		var active, content, links = $(this).find('#tabs span');

		active = $(links[0]).addClass('active');
		content = $("#tabContainer #" + active.attr('data-tab'));

		links.not(active).each(function() {
			$("#tabContent #" + $(this).attr('data-tab')).hide();
		});

		links.each(function(index) {
			$(this).click(function(e) {
				e.preventDefault();
				active.removeClass('active');
				content.hide();

				active = $(this).addClass('active');
				content = $("#tabContent #" + $(this).attr('data-tab')).show();
			});
		});
	});

	//Load building data
	$(document).on('gameDataLoaded', function() {
		$.jStorage.subscribe("dataUpdater", function(channel, payload) {
			if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
				if (inArray(payload.msgTarget, "all")) {
					switch (payload.msgType) {
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

						case "msgUpdateBuildingQueue":
							if(payload.msgData.objectID == objectID) {
								loadBuidingQueue(payload.msgData.buildQueue);
							}
							break;

						case "msgUpdateBuildingUpgrades":
							if(payload.msgData.objectID == objectID) {
								parseBuildingData(latestGameData.objectBuildings);
								loadBuildingData(latestGameData.objectBuildings, payload.msgData.canBuild);
							}
							break;
					}
				}
			}
		});

		getBuildingData();
	});

	function handleBuildingAjax(data) {
		if(data.code < 0) {
			showMessage("Error " + (-data.code) + ": " + data.message, "red", 30000);
		}
		handleAjax(data);
		if(isset(data.buildQueue)) {
			$.jStorage.publish("dataUpdater", new Message("msgUpdateBuildingQueue", {"objectID" : objectID, "buildQueue" : data.buildQueue}, ["all"], window.name));
		}

		if(isset(data.canBuild)) {
			$.jStorage.publish("dataUpdater", new Message("msgUpdateBuildingUpgrades", {"objectID" : objectID, "canBuild" : data.canBuild}, ["all"], window.name));
		}
	}

	function getBuildingData() {
		$.post(
			"ajaxRequest.php",
			{"action" : "getBuildings", "ajaxType": "BuildingHandler", "objectID": objectID},
			handleBuildingAjax,
			"json"
		).fail(function() { $("#tabContainer").text("An error occurred while getting data"); });
	}

	function loadBuidingQueue(buildQueue) {
		//Load building queue
		var buildQueueHolder = $("#buildingQueue").html("");
		var templateBuildingQueue = Handlebars.templates['buildingQueueItem.tmpl'];
		if(typeof buildQueue[0] !== 'undefined') {
			var uid = buildQueue[0].id;
			buildQueueHolder.append(templateBuildingQueue({
				operation: buildQueue[0].operation,
				buildName: dbBuildData[buildQueue[0].buildingID].buildName,
				buildLevel: buildQueue[0].buildingLevel,
				startTime: buildQueue[0].startTime,
				endTime: buildQueue[0].endTime,
				callback: "getBuildingData();",
				id: uid
			}));

			$("#" + uid).progressbar({
				value: 1,
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
				$("#buildingQueue").append(
						templateBuildingQueue({
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
					handleBuildingAjax,
					"json"
				).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
			});
		}
	}

	function loadBuildingData(objectBuildings, canBuild) {
		//Load data
		var buildPageInfo = {};

		//Load possible buildings first
		for(var key in canBuild) {
			var data = canBuild[key];
			var nextBuilding = isset(objectBuildings[key]) ? objectBuildings[key] : new Building(key, 0);
			if(!(key in buildPageInfo)) {
				buildPageInfo[key] = clone(nextBuilding);
				buildPageInfo[key].curLevel = 0;
			}

			buildPageInfo[key].nextLevel = data.nextLevel;
			buildPageInfo[key].nextDestroyLevel = data.nextLevel - 1;
			buildPageInfo[key].nextResReq = data.nextResReq;

			buildPageInfo[key].nextResConsumption = nextBuilding.getBaseBuildingConsumption(data.nextLevel);
			if(!isEmpty(buildPageInfo[key].nextResConsumption))
				buildPageInfo[key].showConsumption = true;

			buildPageInfo[key].nextResProduction = nextBuilding.getBaseBuildingProduction(data.nextLevel);
			if(!isEmpty(buildPageInfo[key].nextResProduction))
				buildPageInfo[key].showProduction = true;

			if(buildPageInfo[key].showProduction && buildPageInfo[key].showConsumption) {
				buildPageInfo[key].showNetChange = true;
				buildPageInfo[key].nextResChange = mergeSub(buildPageInfo[key].nextResProduction, buildPageInfo[key].nextResConsumption);
			}

			buildPageInfo[key].nextModifiers = nextBuilding.getBaseBuildingMods(data.nextLevel);
			if(buildPageInfo[key].nextModifiers)
				buildPageInfo[key].showModifiers = true;

			buildPageInfo[key].upgradeTime = niceETA(moment.duration(data.upgradeTime, 'seconds'));
		}

		for(var key in objectBuildings) {
			var curBuilding = objectBuildings[key];
			if(!(key in buildPageInfo)) {
				buildPageInfo[key] = clone(curBuilding);
			}

			buildPageInfo[key].curLevel = curBuilding.level;
			if(!buildPageInfo[key].nextDestroyLevel && curBuilding.level)
				buildPageInfo[key].nextDestroyLevel = curBuilding.level;

			buildPageInfo[key].curResConsumption = curBuilding.getBaseBuildingConsumption();
			if(!isEmpty(buildPageInfo[key].curResConsumption))
				buildPageInfo[key].showConsumption = true;

			buildPageInfo[key].curResProduction = curBuilding.getBaseBuildingProduction();
			if(!isEmpty(buildPageInfo[key].curResProduction))
				buildPageInfo[key].showProduction = true;

			if(buildPageInfo[key].showProduction && buildPageInfo[key].showConsumption) {
				buildPageInfo[key].showNetChange = true;
				buildPageInfo[key].curResChange = mergeSub(buildPageInfo[key].curResProduction, buildPageInfo[key].curResConsumption);
			}

			buildPageInfo[key].curModifiers = curBuilding.getBaseBuildingMods();
			if(buildPageInfo[key].curModifiers)
				buildPageInfo[key].showModifiers = true;
		}

		//Load interface
		$('#resource').html("");
		$('#rnd').html("");
		$('#military').html("");
		$('#special').html("");
		var templateBuildingBox = Handlebars.templates['buildingBox.tmpl'];
		for (var i in buildPageInfo) {
			var building = buildPageInfo[i];
			var html = $(templateBuildingBox(building));
			$("#" + building.buildType).append(html);
		}

		//Load buttons
		$(".buildingUpgrade").on("click", function(){
			var buildingID = $(this).attr("data-buildingID");
			var buildingLevel = $(this).attr("data-buildingLevel");

			if(!isset(buildingLevel)) {
				return;
			}

			$.post(
				"ajaxRequest.php",
				{"action" : "buildBuilding", "ajaxType": "BuildingHandler", "objectID": objectID, "buildingID": buildingID, "buildingLevel": buildingLevel},
				handleBuildingAjax,
				"json"
			).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
		});

		$(".buildingDestroy").on("click", function(){
			var buildingID = $(this).attr("data-buildingID");
			var buildingLevel = $(this).attr("data-buildingLevel");
			doConfirm("Are you sure you want to destroy this building?", function() {
				$.post(
					"ajaxRequest.php",
					{"action" : "destroyBuilding", "ajaxType": "BuildingHandler", "objectID": objectID, "buildingID": buildingID, "buildingLevel": buildingLevel},
					handleBuildingAjax,
					"json"
				).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
			}, function(){});
		});

		$(".buildingRecycle").on("click", function(){
			var buildingID = $(this).attr("data-buildingID");
			var buildingLevel = $(this).attr("data-buildingLevel");
			doConfirm("Are you sure you want to recycle this building?", function() {
				$.post(
					"ajaxRequest.php",
					{"action" : "recycleBuilding", "ajaxType": "BuildingHandler", "objectID": objectID, "buildingID": buildingID, "buildingLevel": buildingLevel},
					handleBuildingAjax,
					"json")
					.fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); }
				).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
			}, function(){});
		});

		//Load info links
		var templateBuildingInfo = Handlebars.templates['buildingInfo.tmpl'];
		$(".buildingInfo").each(function() {
			staticTT(
				$(this),
				{
					show: { delay: 300, effect: "show" },
					content : function() {
						var context = buildPageInfo[$(this).attr("data-buildingID")];
						return templateBuildingInfo(context);
					},
					open: function( event, ui ) {
						loadHovers(latestGameData);
					}
				}
			);
		});
		loadHovers(latestGameData);
	}
</script>
{{/block}}
