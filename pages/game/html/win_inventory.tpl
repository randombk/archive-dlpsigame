{{block name="title" prepend}}Inventory{{/block}}
{{block name="additionalStylesheets" append}}
	<link rel="stylesheet" href="resources/css/inventory.css?v={{$VERSION}}">
{{/block}}
{{block name="additionalIncluding" append}}
	<script src="handlebars/invObject.js?v={{$VERSION}}"></script>
{{/block}}

{{block name="content"}}
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
			<div id="objectList" class="overview"></div>
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
							<td style="width: 60%;">Number of buildings: <span id="numBuildings"></span></td>
							<td style="width: 40%;">Planet Type: <span id="planetType"></span></td>
						</tr>
						<tr>
							<td style="width: 60%;">Storage Capacity: <span id="storageUsed"></span></td>
							<td style="width: 40%;">Planet Size: <span id="planetSize"></span></td>
						</tr>
						<tr>
							<td style="width: 60%;">Energy Capacity: <span id="energyCapacity"></span></td>
							<td style="width: 40%;">Planet Temperature: <span id="planetTemp"></span></td>
						</tr>
						<tr>
							<td style="width: 60%;">Fleet Capacity: <span id="fleetCapacity"></span></td>
							<td style="width: 40%;">Planet Humidity: <span id="planetHumidity"></span></td>
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
			<div class="invControl" id="reloadData" 	onclick="getObjectListData(); getObjectData(objectID);">Reload Data</div>
			<div class="invControl" id="clearSelection" onclick="return clearSelections();">Clear Selection</div>
			<div class="invControl" id="sortName"		onclick="return sortName();">Sort Name</div>
			<div class="invControl" id="sortType"		onclick="return sortType();">Sort Type</div>
			<div class="invControl" id="sortQuantity"	onclick="return sortQuantity();">Sort Quantity</div>
			<div class="invControl" id="sortWeight"		onclick="return sortWeight();">Sort Weight</div>
			<div class="invControl" id="Type"			onclick="return sortType();">Sort Value (NYI)</div>
		</div>
		<div class="invControlHolder">
			<div class="invControl" id="useItem" 		onclick="return useSelected();">Use Item</div>
			<div class="invControl" id="discardItem" 	onclick="return discardSelected();">Discard selected items</div>
		</div>
		<div id="invControlText" class="invControlHolder"></div>
	</div>
</div>
<div id="toggleMaxMain">
	&lt;
</div>
{{/block}}

{{block name="winHandlers" append}}
	<script type="text/javascript">
		var objectID = -1;
		var selectedItems = {};
		var curSortBy = "";

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
				updateAllScrollbars()
			});

			$.jStorage.subscribe("dataUpdater", function(channel, payload) {
				if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
					if (inArray(payload.msgTarget, "all")) {
						switch (payload.msgType) {
							case "msgUpdateItems":
								if(payload.msgData.objectID == objectID && jQuery.isEmptyObject(selectedItems)) {
									parseItemData(payload.msgData.itemData);
									latestGameData.objectItems = payload.msgData.itemData;
									loadInventoryPage(payload.msgData.itemData);
									loadItemHover(latestGameData);
								}
								break;

							case "msgUpdateObjectData":
								if(payload.msgData.objectID == objectID && jQuery.isEmptyObject(selectedItems)) {
									resetInfoPage();
									$("#planetName").text(payload.msgData.objectName);
									$("#planetLoc").text(payload.msgData.objectCoords);
									$("#planetType").text(payload.msgData.objectData.planetType);
									$("#planetSize").text(payload.msgData.objectData.planetSize);
									$("#planetTemp").text(payload.msgData.objectData.planetTemp);
									$("#planetHumidity").text(payload.msgData.objectData.planetHumidity);
									$("#numBuildings").text(payload.msgData.numBuildings);

									$("#storageUsed").text(niceNumber(payload.msgData.usedStorage) + " / " + niceNumber(payload.msgData.objStorage));
									if(payload.msgData.usedStorage >= payload.msgData.objStorage) {
										$("#storageUsed").addClass("red");
									} else {
										$("#storageUsed").removeClass("red");
									}

									$("#invTopInfoHolder").show();
								}
								break;

							case "msgUpdateObjectInventoryData":
								if(payload.msgData.objectID == objectID && jQuery.isEmptyObject(selectedItems)) {
									$("#energyCapacity").text(niceNumber(isset(payload.msgData.objUsedEnergyStorage) ? payload.msgData.objUsedEnergyStorage : 0) + " / " + niceNumber(payload.msgData.objEnergyStorage));
									if(payload.msgData.objUsedEnergyStorage >= payload.msgData.objEnergyStorage) {
										$("#energyCapacity").addClass("red");
									} else {
										$("#energyCapacity").removeClass("red");
									}
								}
								break;

							case "msgUpdateObjectList":
								loadObjectList(payload.msgData.objectList);
								break;
						}
					}
				}
			});

			getObjectListData();
			resetInfoPage();
		});

		function handleInventoryAjax(data) {
			if(data.code < 0) {
				showMessage("Error " + (-data.code) + ": " + data.message, "red", 30000);
			}

			handleAjax(data);

			$.jStorage.publish("dataUpdater", new Message(
				"msgUpdateObjectData",
				{
					"objectID" : data.objectID,
					"objectName" : data.objectName,
					"objectCoords" : data.objectCoords,
					"objectModifiers" : data.objectModifiers,
					"objectWeightPenalty" : data.objectWeightPenalty,
					"usedStorage" : data.usedStorage,
					"objStorage" : data.objStorage,
					"numBuildings" : data.numBuildings,
					"objectData" : data.objectData
				},
				["all"],
				window.name)
			);

			$.jStorage.publish("dataUpdater", new Message(
				"msgUpdateObjectInventoryData",
				{
					"objectID" : data.objectID,
					"objUsedEnergyStorage" : data.objUsedEnergyStorage,
					"objEnergyStorage" : data.objEnergyStorage
				},
				["all"],
				window.name)
			);
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
				getObjectData($(this).attr("data-objectID"));
			});
			$(".objectListItem").attr("data-active", "");
			$(".objectListItem[data-objectID=" + objectID + "]").attr("data-active", "true");
		}

		function getObjectData(id) {
			if(id <= 0) return;
			clearSelections();

			objectID = id;
			$(".objectListItem").attr("data-active", "");
			$(".objectListItem[data-objectID=" + objectID + "]").attr("data-active", "true");

			$(".invHolder").text("Loading Data...");
			$.post("ajaxRequest.php",
				{"action" : "getObjectInventoryInfo", "ajaxType": "ObjectInventory", "objectID": objectID},
				handleInventoryAjax,
				"json"
			).fail(function() { $(".invHolder").text("An error occurred while getting data"); });
		}

		function resetInfoPage() {
			$("#invTopInfoHolder").hide();
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
				if(item.itemVisibility > 0 && Math.floor(item.quantity) > 0) {
					var html = $(template({
						quantity: niceNumber(Math.floor(item.quantity)),
						itemName: item.itemName,
						itemImage: item.itemImage
					}));

					html.addClass("type_" + item.itemType.replace(" ", "_"))
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
									$("<div></div>").addClass("selText").html("Selected:<br>" + niceNumber(number))
								);
								selectedItems[element.attr("data-itemID")] = number;
								updateControls(data);
							}, "text", Math.floor($(this).attr("data-itemQuantity")));
						}
						updateControls(data);
					});
				}
			}
			registerHover(data);
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

		//Selection handling and controls
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

				$("#reloadData").hide();
				$("#clearSelection").show();

				//By default, show everything
				$("#discardItem").show();
				$("#useItem").show();

				//Then, block out as criteria are not met
				if(Object.keys(selectedItems).length > 1) {
					$("#useItem").hide();
				}

				for (var i in selectedItems) {
					totalNumber += parseInt(selectedItems[i]);
					totalWeight += parseInt(selectedItems[i] * itemData[i].itemWeight);
					if(!itemData[i].hasFlag("Usable")){
						$("#useItem").hide();
					}
					if(itemData[i].hasFlag("NoDestroy")) {
						$("#discardItem").hide();
					}
				}

				$("#invControlText").text("Selected " + niceNumber(totalNumber) + " items, weighing " + niceNumber(totalWeight));
			} else {
				$("#useItem").hide();
				$("#discardItem").hide();

				$("#invControlText").text("");
				$("#reloadData").show();
				$("#clearSelection").hide();
			}
		}

		//Special actions
		function discardSelected() {
			//All sanity checking is handled server-side, so dont bother checking again here.
			doConfirm("Are you sure you want to permanently discard the selected items?", function() {
				$.post("ajaxRequest.php",
					{"action" : "discardItem", "ajaxType": "ObjectInventory", "objectID": objectID, "itemArray": JSON.stringify(selectedItems)},
					handleInventoryAjax,
					"json"
				).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
				clearSelections();
			}, function(){});
		}

		function useSelected() {
			//All sanity checking is handled server-side, so dont bother checking again here.
			var itemID = Object.keys(selectedItems)[0];
			var numItem = selectedItems[itemID];
			$.post("ajaxRequest.php",
				{"action" : "useItem", "ajaxType": "ObjectInventory", "objectID": objectID, "itemID": itemID, "itemAmount": numItem},
				handleInventoryAjax,
				"json"
			).fail(function() { $("#tabContainer").prepend("An error occurred while getting data"); });
			clearSelections();
		}

		//Sorting
		function sortType() {
			var sortQuery = null;
			if(curSortBy != "Type") {
				curSortBy = "Type";
				sortQuery = function(a,b){
					var type1 = a.getAttribute("data-itemType").toLowerCase();
					var type2 = b.getAttribute("data-itemType").toLowerCase();
					if(type1 == type2) {
						var aVis = parseInt(a.getAttribute("data-itemVisibility"));
						var bVis = parseInt(b.getAttribute("data-itemVisibility"));
						if(aVis == bVis) {
							return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? 1 : -1;
						} else {
							return aVis > bVis ? 1 : -1;
						}
					} else {
						return type1 > type2 ? 1 : -1;
					}
				};
			} else {
				curSortBy = "InvType";
				sortQuery = function(a,b){
					var type1 = a.getAttribute("data-itemType").toLowerCase();
					var type2 = b.getAttribute("data-itemType").toLowerCase();
					if(type1 == type2) {
						var aVis = parseInt(a.getAttribute("data-itemVisibility"));
						var bVis = parseInt(b.getAttribute("data-itemVisibility"));
						if(aVis == bVis) {
							return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? -1 : 1;
						} else {
							return aVis > bVis ? -1 : 1;
						}
					} else {
						return type1 > type2 ? -1 : 1;
					}
				};
			}
			$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
			return false;
		}

		function sortName() {
			var sortQuery = null;
			if(curSortBy != "Name") {
				curSortBy = "Name";
				sortQuery = function(a,b){
					return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? 1 : -1;
				};
			} else {
				curSortBy = "InvName";
				sortQuery = function(a,b){
					return a.getAttribute("data-itemName").toLowerCase() > b.getAttribute("data-itemName").toLowerCase() ? -1 : 1;
				};
			}

			$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
			return false;
		}

		function sortWeight() {
			var sortQuery = null;
			if(curSortBy != "Weight") {
				curSortBy = "Weight";
				sortQuery = function(a,b){
					return parseInt(a.getAttribute("data-itemTotalWeight")) > parseInt(b.getAttribute("data-itemTotalWeight")) ? -1 : 1;
				};
			} else {
				curSortBy = "InvWeight";
				sortQuery = function(a,b){
					return parseInt(a.getAttribute("data-itemTotalWeight")) > parseInt(b.getAttribute("data-itemTotalWeight")) ? 1 : -1;
				};
			}

			$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
			return false;
		}

		function sortQuantity() {
			var sortQuery = null;
			if(curSortBy != "Quantity") {
				curSortBy = "Quantity";
				sortQuery = function(a,b){
					return parseInt(a.getAttribute("data-itemQuantity")) > parseInt(b.getAttribute("data-itemQuantity")) ? -1 : 1;
				};
			} else {
				curSortBy = "InvQuantity";
				sortQuery = function(a,b){
					return parseInt(a.getAttribute("data-itemQuantity")) > parseInt(b.getAttribute("data-itemQuantity")) ? 1 : -1;
				};
			}

			$('.invHolder .invObject').sort(sortQuery).appendTo('.invHolder');
			return false;
		}
	</script>
	{{if $objectID > 0}}
		<script type="text/javascript">
			(function($) {
				$(document).on('gameDataLoaded', function() {
					getObjectData({{$objectID}});
				});
			})(jQuery);
		</script>
	{{/if}}
{{/block}}
