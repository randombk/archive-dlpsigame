{block name="title" prepend}{"Research"}{/block}
{block name="additionalIncluding" append}
	<link rel="stylesheet" href="resources/css/research.css?v={$VERSION}">
	<script src="handlebars/researchListItem.js?v={$VERSION}"></script>
{/block}

{block name="content"}
<div class="researchLeftPanel">
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
<div class="researchMainPanel">
	<div class="researchQueueHolder">

	</div>
</div>
<div id="toggleMaxMain">
	&lt;
</div>
{/block}

{block name="winHandlers" append}
	<script>var objectID = {$objectID};</script>
	{literal}
		<script>
			$('#gamePageContainer').css("height", "100%");
			$("#gameMenu").ready(function() {
				$("#gameMenu #pageResearch").addClass("active");
			});
			
			(function($) {
				$(document).on('gameDataLoaded', function() {
					$("#toggleMaxMain").on("click", function(){
						if($(this).hasClass("maxMain")) {
							$(this).removeClass('maxMain');
							$(this).css("left", 251);
							$(".researchLeftPanel").show();
							$(".researchMainPanel").css("left", 251);
							$(this).text("<");
						} else {
							$(this).addClass('maxMain');
							$(this).css("left", 0);
							$(".researchLeftPanel").hide();
							$(".researchMainPanel").css("left", 0);
							$(this).text(">");
						}
						$(".scrollable").tinyscrollbar_update();
					});
					
					$.jStorage.subscribe("dataUpdater", function(channel, payload) {
						if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
							if (inArray(payload.msgTarget, "all")) {
								switch (payload.msgType) {
									case "msgUpdateResearchInfo": {
										if(payload.msgData.objectID == objectID) {
											parseResearchData(payload.msgData.researchData.research);
											loadResearchList(payload.msgData.researchData.research);
										}
										break;
									}
								}
							}
						}
					});
										
					loadObjectResearchData(objectID);
					//resetInfoPage();
				});
			})(jQuery);
			
			function loadObjectResearchData(objectID) {
				$.post("ajaxRequest.php",
					{"action" : "getObjectResearch", "ajaxType": "ResearchHandler", "objectID": objectID},
					function(data){
						if(data.code < 0) {
							showMessage("Error #" + (-data.code) + ": " + data.message, "red", 30000);
						} else {
							$.jStorage.publish("dataUpdater", new Message("msgUpdateResearchInfo", {"researchData" : data, "objectID": data.objectID}, ["all"], window.name));
						}
					},
					"json"
				).fail(function() {
					showMessage("An error occurred while getting data", "red", 30000);
				});
			}
			
			function loadResearchList(researchData) {
				var researchListItem = Handlebars.templates['researchListItem.tmpl'];
				$("#researchList").text("");
				for ( var i in researchData ) {
					var data = researchData[i];
					if(data.canResearch(researchData)) {
						$("#researchList").append(researchListItem({
							"techID" : data.techID,
							"techName" : data.techName,
							"techImage" : data.techImage,
							"techLevel" : data.techLevel,
							"techPoints" : data.techPoints,
							"techPointsReq" : 100,
							"techColor" : data.getResearchColor(researchData)
						}));
					}
				}
				$(".researchListItem").on("click", function() {
					loadObjectData($(this).attr("data-techID"));
				});
			}
			
			
			
			//Load research data
			/*

			function loadObjectInfoPage(data) {
				$.jStorage.publish("dataUpdater", new Message("msgUpdateItems", {"objectID" : objectID, "itemData" : data.items}, ["all"], window.name));
				$(".gen").remove();
				//Load object info
				
				$("#planetName").text(data.objectName);
				$("#planetLoc").text(data.objectCoords);
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
				
				loadHovers({items: data.items});
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
			}*/
		</script>
	{/literal}
{/block}
