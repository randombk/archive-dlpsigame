{{block name="title" prepend}}{{"Research"}}{{/block}}
{{block name="additionalStylesheets" append}}
	<link rel="stylesheet" href="resources/css/research.css?v={{$VERSION}}">
{{/block}}

{{block name="additionalIncluding" append}}
	<script src="handlebars/researchListItem.js?v={{$VERSION}}"></script>
	<script src="handlebars/researchNoteInfo.js?v={{$VERSION}}"></script>
{{/block}}

{{block name="content"}}
<div id="toggleMaxMain">
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
	<div id="researchQueueHolder"></div>
	<div id="researchInfoHolder">
		<div class="stdBorder abs" style="top: 10px; left: 10px; width: 115px; height: 100px; ">
			<img id="researchInfoImage" width="115" height="100">
		</div>
		<div id="researchInfoTitle" class="stdBorder abs" style="top: 10px; left: 135px; right: 160px; height: 15px; background-color: #1E3E5D; text-align: center;"></div>
		<div id="researchInfoLink" class="stdBorder abs buttonDiv" style="top: 10px; right: 10px; width: 149px; height: 15px; text-align: center;">View in Research Map &gt;&gt;</div>
		<div id="researchInfoDesc" 	class="stdBorder abs" style="top: 35px; left: 135px; right: 10px; height: 75px;"></div>

		<div id="researchInfoControls" class="stdBorder abs" style="top: 120px; left: 10px; width: 270px; height: 120px;"></div>

		<div id="researchInfoEffectsControls" class="stdBorder abs" style="top: 250px; left: 10px; width: 270px; height: 20px;">
			<div id="researchInfoEffectsControlsLeft" class="stdBorder abs buttonDiv" style="text-align: center; left: -1px; top: -1px; width: 20px; height: 100%;">&lt;</div>
			<div id="researchInfoEffectsControlsLevel" class="abs" style="text-align: center; left: 20px; right: 20px; height: 100%;"></div>
			<div id="researchInfoEffectsControlsRight" class="stdBorder abs buttonDiv" style="text-align: center; right: -1px; top: -1px; width: 20px; height: 100%; ">&gt;</div>
		</div>
		<div id="researchInfoEffectsHolder" class="stdBorder abs scrollable" style="top: 271px; left: 10px; width: 265px; bottom: 10px; text-align: left; padding-left: 5px;">
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
{{/block}}

{{block name="winHandlers" append}}
	<script>
		var objectID = {{$objectID}};
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
									parseResearchData(payload.msgData.researchData.research);
									lastAjaxResponse.researchData = payload.msgData.researchData.research;
									loadResearchList(payload.msgData.researchData.research);
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
						handleAjax(data);
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
			var researchesAvaliable = false;
			for ( var i in researchData ) {
				var data = researchData[i];
				if(data.canResearch(researchData)) {
					if(!researchesAvaliable) {
						researchesAvaliable = data.techID;
					}

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
			if(researchesAvaliable) {
				$(".researchListItem").on("click", function() {
					loadResearchInfo(researchData, $(this).attr("data-techID"));
				});
				loadResearchInfo(researchData, researchesAvaliable);
			} else {
				$("#researchList").text("No Researches Avaliable!");
				$("#researchInfoHolder").text("No Researches Avaliable!");
			}
		}

		function loadResearchInfo(researchData, techID) {
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
			loadHovers(lastAjaxResponse);
		}

		function loadResearchNoteInfo(researchData, techID) {
			var tech = researchData[techID];
			var researchNoteInfo = Handlebars.templates['researchNoteInfo.tmpl'];
			$("#researchNoteRequirements").html(researchNoteInfo(tech));
		}
	</script>
{{/block}}
