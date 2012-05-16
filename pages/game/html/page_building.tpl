{block name="title" prepend}{"Info"}{/block}
{block name="additionalIncluding" append}
	<link rel="stylesheet" href="resources/css/buildings.css?v={$VERSION}">
	<script src="handlebars/buildings/buildingBox.js?v={$VERSION}"></script>
	<script src="handlebars/buildings/buildingInfo.js?v={$VERSION}"></script>
	<script src="handlebars/buildings/buildingQueueItem.js?v={$VERSION}"></script>
{/block}

{block name="content"}
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
{/block}

{block name="winHandlers" append}
	<script>var objectID = {$objectID};</script>
	{literal}
		<script>
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
			(function($) {
				$(document).on('gameDataLoaded', function() {					
					loadData();
				});
			})(jQuery); 
			
			function loadData() {
				$.post("ajaxRequest.php", 
					{"action" : "getBuildings", "ajaxType": "BuildingHandler", "objectID": objectID},
					function(data){
						if(data.code < 0) {
							$("#tabContainer").text("Fatal Error #" + (-data.code) + ": " + data.message);
						} else {
							$.jStorage.publish("dataUpdater", new Message("msgUpdateItems", {"objectID" : objectID, "itemData" : data.items}, ["all"], window.name));
							parseItemData(data.items);
											
							//Load building queue
							$("#buildingQueue").html("");
							var templateBuildingQueue = Handlebars.templates['buildingQueueItem.tmpl'];
							
							if(typeof data.buildQueue[0] !== 'undefined') {
								var uid = data.buildQueue[0].id;
								$("#buildingQueue").append(templateBuildingQueue({
									operation: data.buildQueue[0].operation,
									buildName: dbBuildData[data.buildQueue[0].buildingID].buildName,
									buildLevel: data.buildQueue[0].buildingLevel,
									startTime: data.buildQueue[0].startTime,
									endTime: data.buildQueue[0].endTime,
									id: uid
								}));
								
								$("#" + uid).progressbar({
					      			value: 1,
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
									$("#buildingQueue").append(
										templateBuildingQueue({
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
											loadNotificationData();
											if(data.code >= 0) {
												loadData();
											}
										},
										"json"
									).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
								});
							}
							
							//Load data
							var buildPageInfo = {};
							
							//Load popssible buildings first
							for(var key in data.canBuild) {
								var obj = data.canBuild[key];
								if(!(key in buildPageInfo)) {
							   		buildPageInfo[key] = dbBuildData[key];
							   		buildPageInfo[key].curLevel = 0;
								}
								
								buildPageInfo[key].nextLevel = obj.nextLevel;
								buildPageInfo[key].nextDestroyLevel = obj.nextLevel - 1;
								buildPageInfo[key].nextResReq = obj.nextResReq;
								
								buildPageInfo[key].nextResConsumption = obj.nextResConsumption;
								if(!isEmpty(buildPageInfo[key].nextResConsumption))
									buildPageInfo[key].showConsumption = true;
								
								buildPageInfo[key].nextResProduction = obj.nextResProduction;
								if(!isEmpty(buildPageInfo[key].nextResProduction))
									buildPageInfo[key].showProduction = true;
								
								if(buildPageInfo[key].showProduction && buildPageInfo[key].showConsumption) {
									buildPageInfo[key].showNetChange = true;
									buildPageInfo[key].nextResChange = mergeItemDataClone(buildPageInfo[key].nextResProduction, buildPageInfo[key].nextResConsumption, "-");
								}
								
								buildPageInfo[key].nextResearch = getBuildingBaseResearch(key, obj.nextLevel);
								if(buildPageInfo[key].nextResearch) {
									if(buildPageInfo[key].nextResearch.Weapons)
										buildPageInfo[key].showWeaponsResearch = true;
									if(buildPageInfo[key].nextResearch.Defense)
										buildPageInfo[key].showDefenseResearch = true;
									if(buildPageInfo[key].nextResearch.Diplomatic)
										buildPageInfo[key].showDiplomaticResearch = true;
									if(buildPageInfo[key].nextResearch.Economic)
										buildPageInfo[key].showEconomicResearch = true;
									if(buildPageInfo[key].nextResearch.Fleet)
										buildPageInfo[key].showFleetResearch = true;
								}
								
								buildPageInfo[key].nextModifiers = obj.nextModifiers;
								if(buildPageInfo[key].nextModifiers)
									buildPageInfo[key].showModifiers = true;
								
								buildPageInfo[key].upgradeTime = niceETA(moment.duration(obj.upgradeTime, 'seconds'));
							}
	
							for(var key in data.buildings) {
								var obj = data.buildings[key];
								if(!(key in buildPageInfo)) {
							   		buildPageInfo[key] = dbBuildData[key];
								}
								
								buildPageInfo[key].curLevel = obj.level;
							   	if(!buildPageInfo[key].nextDestroyLevel && obj.level)
							   		buildPageInfo[key].nextDestroyLevel = obj.level;
								
								buildPageInfo[key].curResConsumption = obj.curResConsumption;
								if(!isEmpty(buildPageInfo[key].curResConsumption))
									buildPageInfo[key].showConsumption = true;
							
								buildPageInfo[key].curResProduction = obj.curResProduction;
							   	if(!isEmpty(buildPageInfo[key].curResProduction))
									buildPageInfo[key].showProduction = true;
							    
							    if(buildPageInfo[key].showProduction && buildPageInfo[key].showConsumption) {
									buildPageInfo[key].showNetChange = true;
									buildPageInfo[key].curResChange = mergeItemDataClone(buildPageInfo[key].curResProduction, buildPageInfo[key].curResConsumption, "-");
								}
							    
							    buildPageInfo[key].curResearch = getBuildingBaseResearch(key, obj.nextLevel);
								if(buildPageInfo[key].curResearch) {
									if(buildPageInfo[key].curResearch.Weapons)
										buildPageInfo[key].showWeaponsResearch = true;
									if(buildPageInfo[key].curResearch.Defense)
										buildPageInfo[key].showDefenseResearch = true;
									if(buildPageInfo[key].curResearch.Diplomatic)
										buildPageInfo[key].showDiplomaticResearch = true;
									if(buildPageInfo[key].curResearch.Economic)
										buildPageInfo[key].showEconomicResearch = true;
									if(buildPageInfo[key].curResearch.Fleet)
										buildPageInfo[key].showFleetResearch = true;
								}
							    
							    buildPageInfo[key].curModifiers = obj.curModifiers;
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
								
								$.post("ajaxRequest.php", 
									{"action" : "buildBuilding", "ajaxType": "BuildingHandler", "objectID": objectID, "buildingID": buildingID, "buildingLevel": buildingLevel},
									function(data){
										loadNotificationData();
										if(data.code < 0) {
											showMessage(data.message, "red", 30000);
										} else {
											loadData();
										}
									}, "json")
									.fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); })
									.always(function() {  });
							});
							
							$(".buildingDestroy").on("click", function(){
								var buildingID = $(this).attr("data-buildingID");
								var buildingLevel = $(this).attr("data-buildingLevel");
								doConfirm("Are you sure you want to destroy this building?", function() {
									$.post("ajaxRequest.php", 
										{"action" : "destroyBuilding", "ajaxType": "BuildingHandler", "objectID": objectID, "buildingID": buildingID, "buildingLevel": buildingLevel},
										function(data){
											loadNotificationData();
											if(data.code < 0) {
												showMessage(data.message, "red", 30000);
											} else {
												loadData();
											}
										}, "json")
										.fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); })
										.always(function() {  });
								}, function(){});
							});
							
							$(".buildingRecycle").on("click", function(){
								var buildingID = $(this).attr("data-buildingID");
								var buildingLevel = $(this).attr("data-buildingLevel");
								doConfirm("Are you sure you want to recycle this building?", function() {
									$.post("ajaxRequest.php", 
										{"action" : "recycleBuilding", "ajaxType": "BuildingHandler", "objectID": objectID, "buildingID": buildingID, "buildingLevel": buildingLevel},
										function(data){
											loadNotificationData();
											if(data.code < 0) {
												showMessage(data.message, "red", 30000);
											} else {
												loadData();
											}
										}, "json")
										.fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); })
										.always(function() {  });
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
											loadHovers({items: data.items});
										}
									}
								);
							});
							
							loadHovers({items: data.items});
						}
					}, 
					"json"
				)
				.fail(function() { $("#tabContainer").text("An error occurred while getting data"); })
				.always(function() {  });
			}
		</script>
	{/literal}
{/block}
