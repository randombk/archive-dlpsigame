{block name="title" prepend}Inventory{/block}
{block name="additionalIncluding" append}
	<script src="handlebars/invObject.js?v={$VERSION}"></script>
{/block}
{block name="additionalStylesheets" append}
	<link rel="stylesheet" href="resources/css/inventory.css?v={$VERSION}">
{/block}

{block name="content"}
<div class="invLeftPanel">
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

<div class="invMainPanel">
	<div class="invTopInfo">
		<table id="invTopInfoHolder" class="scaffold" style="width: 100%;">
			<tr>
				<td style="width: 80px;">
					<img src="http://placehold.it/80x80">
				</td>
				<td>
					<table class="scaffold" style="width: 100%;">
						<tr>
							<th colspan="2">
								<span id="planetName"></span> (<span id="planetLoc"></span>)
							</th>
						</tr>
						<tr>
							<td style="width: 60%;">
								Number of buildings: <span id="numBuildings"></span>
							</td>
							<td style="width: 40%;">
								Planet Type: <span id="planetType"></span>
							</td>
						</tr>
						<tr>
							<td style="width: 60%;">
								Storage Capacity: <span id="storageUsed"></span>
							</td>
							<td style="width: 40%;">
								Planet Size: <span id="planetSize"></span>
							</td>
						</tr>
						<tr>
							<td style="width: 60%;">
								Energy Capacity: <span id="energyCapacity"></span>
							</td>
							<td style="width: 40%;">
								Planet Temperature: <span id="planetTemp"></span>
							</td>
						</tr>
						<tr>
							<td style="width: 60%;">
								Fleet Capacity: <span id="fleetCapacity"></span>
							</td>
							<td style="width: 40%;">
								Planet Humidity: <span id="planetHumidity"></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div class="invTiles scrollable">
		<div id="messageBanner"></div>
		<div class="scrollbar">
			<div class="track">
				<div class="thumb green-over">
					<div class="end"></div>
				</div>
			</div>
		</div>
		<div class="viewport">
		    <div class="overview">
				<div class="invHolder">
					Select an object from the left panel
				</div>
			</div>
		</div>
	</div>
	<div class="invControls">
		<div class="invControlHolder">
			<div class="invControl" id="reloadData" oncontextmenu="loadObjectListData(); loadObjectData(objectID);" onclick="loadObjectListData(); loadObjectData(objectID);">
				Reload Data
			</div>
			<div class="invControl" id="clearSelection" oncontextmenu="return clearSelections();" onclick="return clearSelections();">
				Clear Selection
			</div>
			<div class="invControl" oncontextmenu="return sortInvName();" onclick="return sortName();">
				Sort Name
			</div>
			<div class="invControl" oncontextmenu="return sortInvType();" onclick="return sortType();">
				Sort Type
			</div>	
			<div class="invControl" oncontextmenu="return sortInvQuantity();" onclick="return sortQuantity();">
				Sort Quantity
			</div>	
			<div class="invControl" oncontextmenu="return sortInvWeight();" onclick="return sortWeight();">
				Sort Weight
			</div>
			<div class="invControl" oncontextmenu="return sortInvType();" onclick="return sortType();">
				Sort Value (NYI)
			</div>
		</div>
		<div id="invControlText" class="invControlHolder">
			
		</div>
	</div>
</div>
<div id="toggleMaxMain">
	&lt;
</div>
{/block}

{block name="winHandlers" append}
	{literal}
		<script type="text/javascript">
			var objectID = -1;
			var selectedItems = {};
			
			(function($) {
				$(document).on('gameDataLoaded', function() {
					$("#toggleMaxMain").on("click", function(){
						if($(this).hasClass("maxMain")) {
							$(this).removeClass('maxMain');
							$(this).css("left", 250);
							$(".invLeftPanel").show();
							$(".invMainPanel").css("left", 251);
							$(this).text("<");
						} else {
							$(this).addClass('maxMain');
							$(this).css("left", 0);
							$(".invLeftPanel").hide();
							$(".invMainPanel").css("left", 0);
							$(this).text(">");
						}
						$(".scrollable").tinyscrollbar_update();
					});
					
					$.jStorage.subscribe("dataUpdater", function(channel, payload) {
						if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
							if (inArray(payload.msgTarget, "all")) {
								switch (payload.msgType) {
									case "msgUpdateObjectInfo": {
										if(payload.msgData.objectID == objectID && jQuery.isEmptyObject(selectedItems)) {
											parseItemData(payload.msgData.objectInfo.items);
											
											loadInfoPage(payload.msgData.objectInfo);
											loadInventoryPage(payload.msgData.objectInfo.items);
											
											$.jStorage.publish("dataUpdater", new Message("msgUpdateItems", {"objectID" : objectID, "itemData" : payload.msgData.objectInfo.items}, ["all"], window.name));
										}
										break;
									}
									case "msgUpdateObjectList": {
										loadObjectList(payload.msgData.objectList);
										break;
									}
								}
							}
						}
					});
										
					loadObjectListData();
					resetInfoPage();
				});
			})(jQuery); 
			
			function loadObjectListData() {
				$("#objectList").text("Loading Data...");
				$.post("ajaxRequest.php", 
					{"action" : "getObjectList", "ajaxType": "ObjectHandler"},
					function(data){
						if(data.code < 0) {
							$("#objectList").text("Error #" + (-data.code) + ": " + data.message);
						} else {
							$.jStorage.publish("dataUpdater", new Message("msgUpdateObjectList", {"objectList" : data}, ["all"], window.name));
						}
					},
					"json"
				).fail(function() { $(".invHolder").text("An error occurred while getting data"); });
			}
			
			function loadObjectList(data) {
				var objectListItem = Handlebars.templates['objectListItem.tmpl'];
				$("#objectList").text("");
				for ( var i in data) {
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
					loadObjectData($(this).attr("data-objectID"));
				});
			}
			
			function loadObjectData(id) {
				if(id <= 0) return;
				
				clearSelections();
				objectID = id;
				$(".invHolder").text("Loading Data...");
				$.post("ajaxRequest.php", 
					{"action" : "getObjectInfo", "ajaxType": "ObjectHandler", "objectID": objectID},
					function(data){
						if(data.code < 0) {
							$(".invHolder").text("Error #" + (-data.code) + ": " + data.message);
						} else {
							$.jStorage.publish("dataUpdater", new Message("msgUpdateObjectInfo", {"objectID" : objectID, "objectInfo" : data}, ["all"], window.name));
						}
					},
					"json"
				).fail(function() { $(".invHolder").text("An error occurred while getting data"); });
			}
			
			function resetInfoPage() {
				$("#invTopInfoHolder").hide();
			}
			
			function loadInfoPage(data) {
				resetInfoPage();
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
				
				$("#energyCapacity").text(niceNumber(isset(data.items.energy) ? data.items.energy.quantity : 0) + " / " + niceNumber(data.objEnergyStorage));									
				if(data.items.energy >= data.objEnergyStorage) {
					$("#energyCapacity").addClass("red");
				} else {
					$("#energyCapacity").removeClass("red");
				}
				
				$("#invTopInfoHolder").show();
			}
			
			function resetInventoryPage() {
				$(".invHolder").html("");
				clearSelections();
			}
			
			function loadInventoryPage(data) {
				resetInventoryPage();
				var template = Handlebars.templates['invObject.tmpl'];
				for(var itemID in data) {
					var item = data[itemID];
					if(item.itemVisibility > 0 && item.quantity > 0) {
						var html = $(template({
							quantity: niceNumber(item.quantity),
							itemName: item.itemName,
							itemImage: item.itemImage
						}));
						
						html.addClass("type_" + item.itemType)
					    	.attr("data-itemType", item.itemType)
						    .attr("data-itemName", item.itemName)
						    .attr("data-itemID", item.itemID)
						    .attr("data-itemQuantity", item.quantity)
						    .attr("data-itemVisibility", item.itemVisibility)
						    .attr("data-itemUnitWeight", item.itemWeight)
						    .attr("data-itemTotalWeight", item.getTotalWeight());
						$(".invHolder").append(html);
						
						//Selection Toggling
						$(html).on("click", function() {
							if($(this).hasClass("selected")) {
								$(this).removeClass("selected");
								$(this).find(".selText").remove();
								delete selectedItems[$(this).attr("data-itemID")];
							} else {
								var element = $(this);
								doInput("Number:", function(number) {
									element.addClass("selected");
									element.append(
										$("<div></div>").addClass("selText").text("Selected: " + niceNumber(number))
									);
									selectedItems[element.attr("data-itemID")] = number; 
									updateControls(data);
								}, "text", Math.round($(this).attr("data-itemQuantity")));
							}
							updateControls(data);
						});
					}
				}
				registerHover(data);
			}
			
			function clearSelections() {
				selectedItems = {};
				updateControls();
				$(".invObject").each(function() {
					$(this).removeClass("selected");
					$(this).find(".selText").remove();
				});
			}
			
			function updateControls(itemData) {
				if(!jQuery.isEmptyObject(selectedItems)) {
					var totalNumber = 0;
					var totalWeight = 0;
					
					for (var i in selectedItems) {
						totalNumber += parseInt(selectedItems[i]);
						totalWeight += parseInt(selectedItems[i] * itemData[i].itemWeight);
					}
					
					$("#invControlText").text("Selected " + niceNumber(totalNumber) + " items, weighing " + niceNumber(totalWeight));
					$("#reloadData").hide();
					$("#clearSelection").show();
				} else {
					$("#invControlText").text("");
					$("#reloadData").show();
					$("#clearSelection").hide();
				}
			}
			
			function registerHover(data) {
				$(".invObject").each(function() {
					if($(this).hasClass("tt-init")) {
						$(this).tooltip("option", "content", function() {
							return data[$(this).attr("data-itemID")].getHoverContent();
						});
					} else {
						staticTT(
							$(this), 
							{
								content : function() {
									return data[$(this).attr("data-itemID")].getHoverContent();
								}, 
								show: { delay: 600, effect: "show" }
							}
						);	
					}
				});
			}
			
			function sortType() {
				function sortQuery(a,b){
					var aVis = parseInt(a.getAttribute("data-itemVisibility"));
					var bVis = parseInt(b.getAttribute("data-itemVisibility"));
					if(aVis == bVis) {
				    	return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? 1 : -1;	
					} else {
						return aVis > bVis ? 1 : -1;	
					}
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
		
			function sortInvType() {
				function sortQuery(a,b){
					var aVis = parseInt(a.getAttribute("data-itemVisibility"));
					var bVis = parseInt(b.getAttribute("data-itemVisibility"));
					if(aVis == bVis) {
				    	return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? -1 : 1;	
					} else {
						return aVis > bVis ? -1 : 1;	
					}
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
			
			function sortName() {
				function sortQuery(a,b){
				 	return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? 1 : -1;	
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
		
			function sortInvName() {
				function sortQuery(a,b){
				  	return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? -1 : 1;	
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
			
			function sortWeight() {
				function sortQuery(a,b){
				 	return parseInt(a.getAttribute("data-itemTotalWeight")) > parseInt(b.getAttribute("data-itemTotalWeight")) ? 1 : -1;	
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
		
			function sortInvWeight() {
				function sortQuery(a,b){
				  	return parseInt(a.getAttribute("data-itemTotalWeight")) > parseInt(b.getAttribute("data-itemTotalWeight")) ? -1 : 1;	
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
			
			function sortQuantity() {
				function sortQuery(a,b){
				 	return parseInt(a.getAttribute("data-itemQuantity")) > parseInt(b.getAttribute("data-itemQuantity")) ? 1 : -1;	
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
		
			function sortInvQuantity() {
				function sortQuery(a,b){
				  	return parseInt(a.getAttribute("data-itemQuantity")) > parseInt(b.getAttribute("data-itemQuantity")) ? -1 : 1;	
				};
				
				$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
				return false;
			}
		</script>
	{/literal}
	
	{if $objectID > 0}
		<script type="text/javascript">
			(function($) {
				$(document).on('gameDataLoaded', function() {
					loadObjectData({$objectID});
				});
			})(jQuery); 
		</script>
	{/if}
{/block}