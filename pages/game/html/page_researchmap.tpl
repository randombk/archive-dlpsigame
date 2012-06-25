{{block name="title" prepend}}{{"Research Map"}}{{/block}}

{{block name="additionalIncluding" append}}
	<script type="text/javascript" src="resources/js/vendor/jquery.overscroll.js?v={{$VERSION}}"></script>
{{/block}}

{{block name="additionalStylesheets" append}}
	<link rel="stylesheet" href="resources/css/research.css?v={{$VERSION}}">
{{/block}}

{{block name="content"}}
<!--div id="researchControlsToggle">
	&lt; Hide Controls
</div-->
<div class="absFill">
	<div id="researchHolder" class="absFill">
		<svg id="researchSVG" xmlns="http://www.w3.org/2000/svg" version="1.1" width="6000" height="6000" viewBox="-2500 -2500 5000 5000">
			<defs>
				<mask id="active" maskUnits="userSpaceOnUse" x="-46" y="-40" width="92" height="80">
					<polygon points="-46,0 -23,40 23,40 46,0 23,-40 -23,-40" fill="white"/>
				</mask>

				<filter id="glowRed" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
					<feGaussianBlur in="SourceGraphic" stdDeviation="6.000000" result="blur"/>
					<feColorMatrix result="bluralpha" type="matrix"
						values="1	0	0	0
								0	0	0	0
								0	0	0	0
								0	0	0	0
								0	0	1	0"/>
					<feMerge>
						<feMergeNode />
						<feMergeNode  in="SourceGraphic"/>
					</feMerge>
				</filter>

				<filter id="glowOrange" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
					<feGaussianBlur in="SourceGraphic" stdDeviation="6.000000" result="blur"/>
					<feColorMatrix result="bluralpha" type="matrix"
						values="1	0	0	0
								0	0.5	0	1
								0	0	0	0
								0	0	0	0
								0	0	1	0"/>
					<feMerge>
						<feMergeNode />
						<feMergeNode  in="SourceGraphic"/>
					</feMerge>
				</filter>

				<filter id="glowGreen" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
					<feGaussianBlur in="SourceGraphic" stdDeviation="6.000000" result="blur"/>
					<feColorMatrix result="bluralpha" type="matrix"
						values="0	0	0	0
								0	1	1	0
								0	0	0	0
								0	0	0	0
								0	0	1	0"/>
					<feMerge>
						<feMergeNode />
						<feMergeNode  in="SourceGraphic"/>
					</feMerge>
				</filter>

				<filter id="glowCyan" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
					<feGaussianBlur in="SourceGraphic" stdDeviation="6.000000" result="blur"/>
					<feColorMatrix result="bluralpha" type="matrix"
						values="0	0	0	0
								0	0	1	0
								0	1	1	0
								0	0	1	0
								0	0	1	0"/>
					<feMerge>
						<feMergeNode />
						<feMergeNode  in="SourceGraphic"/>
					</feMerge>
				</filter>

				<filter id="glowNormal" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
					<feGaussianBlur in="SourceGraphic" stdDeviation="4.00" result="blur"/>
					<feColorMatrix result="bluralpha" type="matrix"
						values="0	0	0	0
								0.1	0.1	0	0
								0	0.1	0.1	0
								0	0.1	0	0.1
								0	0	0.9	0"/>
					<feMerge>
						<feMergeNode />
						<feMergeNode in="SourceGraphic"/>
					</feMerge>
				</filter>
			</defs>
			<g id="researchTileHolder">
			</g>
		</svg>
	</div>
</div>

<div id="researchOverlayHolder" class="absFill" style="text-align: center; vertical-align: middle; display: none;">
	<div id="researchInfoOverlay" style="z-index: 901; position: relative; top: 20%; margin: auto; width: 650px; height: 300px; background: rgba(3,26,58,0.9); border: 1px #D3D3D3 solid;">
		<div id="researchInfoOverlayClose" class="stdBorder abs buttonDiv red-over" style="top: 10px; right: 10px; width: 10px; height: 15px;">X</div>
		<div class="stdBorder abs" style="top: 10px; left: 10px; width: 115px; height: 100px;">
			<img id="researchInfoOverlayImage" width="115" height="100"/>
		</div>
		<div id="researchInfoOverlayTitle" class="stdBorder abs" style="top: 10px; left: 135px; right: 20px; height: 15px; background-color: #1E3E5D; text-align: center;"></div>
		<div id="researchInfoOverlayDesc" class="stdBorder abs" style="top: 35px; left: 135px; right: 10px; height: 75px;"></div>

		<div class="stdBorder abs" style="top: 120px; left: 10px; width: 280px; bottom: 10px;">
			<svg
				id="researchInfoOverlayPosition"
				xmlns="http://www.w3.org/2000/svg"
				version="1.1"
				width="280"
				height="160"
				viewBox="-140 -80 280 160"
			>
				<polygon points="-40,0 -20,35 20,35 40,0 20,-35 -20,-35" fill="none" stroke-width="1" stroke="#D3D3D3"></polygon>
				<text id="researchInfoOverlayPositionCenter1" x="0" y="-5" text-anchor="middle" style="fill: #D3D3D3;"></text>
				<text id="researchInfoOverlayPositionCenter2" x="0" y="5" text-anchor="middle" style="fill: #D3D3D3;"></text>

				<line id="researchInfoOverlayPosition_1_0_Line" x1="0" y1="30" x2="0" y2="45" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-100" y="52" width="200" height="30"><span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPosition_1_0"></span></foreignObject>

				<line id="researchInfoOverlayPosition_-1_0_Line" x1="0" y1="-30" x2="0" y2="-45" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-100" y="-80" width="200" height="30"><span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPosition_-1_0"></span></foreignObject>

				<line id="researchInfoOverlayPosition_-1_1_Line" x1="25" y1="-15" x2="40" y2="-25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="40" y="-40" width="100" height="30"><span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPosition_-1_1"></span></foreignObject>

				<line id="researchInfoOverlayPosition_0_1_Line" x1="25" y1="15" x2="40" y2="25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="40" y="20" width="100" height="30"><span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPosition_0_1"></span></foreignObject>

				<line id="researchInfoOverlayPosition_0_-1_Line" x1="-25" y1="-15" x2="-40" y2="-25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-140" y="-40" width="100" height="30"><span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPosition_0_-1"></span></foreignObject>

				<line id="researchInfoOverlayPosition_1_-1_Line" x1="-25" y1="15" x2="-40" y2="25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-140" y="20" width="100" height="30"><span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPosition_1_-1"></span></foreignObject>

			</ svg>
		</div>

		<div id="researchInfoOverlayEffectsControls" class="stdBorder abs" style="top: 120px; left: 300px; width: 19px; height: 80px;">
			<div id="researchInfoOverlayEffectsControlsUp" class="stdBorder abs buttonDiv" style="text-align: center; padding-top: 5px; top: -1px; left: -1px; right: -1px; height: 25px;">&uarr;</div>
			<div id="researchInfoOverlayEffectsControlsLevel" class="abs" style="text-align: center; padding-top: 10px; top: 22px; left: -1px; right: -1px; height: 25px;"></div>
			<div id="researchInfoOverlayEffectsControlsDown" class="stdBorder abs buttonDiv" style="text-align: center; padding-top: 5px; bottom: -1px; left: -1px; right: -1px; height: 25px;">&darr;</div>
		</div>

		<div id="researchInfoOverlayEffectsHolder" class="stdBorder abs scrollable" style="top: 120px; left: 320px; right: 10px; height: 80px; text-align: left; padding-left: 5px;">
			<div class="scrollbar">
			<div class="track">
					<div class="thumb green-over">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div id="researchInfoOverlayEffects" class="overview"></div>
			</div>
		</div>
		<div id="" class="stdBorder abs" style="top: 208px; left: 300px; right: 10px; height: 80px;"></div>
	</div>
</div>
{{/block}}

{{block name="winHandlers" append}}
<script>
	var centerTech = "{{$techID}}";

	//Override container size
	$('#gamePageContainer').addClass("absFill").css("width", "100%").css("height", "100%");

	$("#gameMenu").ready(function() {
		$("#gameMenu #pageResearchMap").addClass("active");
	});

	$("#researchSVG").ready(function() {
		$("#researchHolder").overscroll();
	});

	function toY(q, r) {
		return (q + r/2) * 100;
	}

	function toX(r) {
		return 86 * r;
	}

	$(document).on('gameDataLoaded', function() {
		$("#researchControlsToggle").on("click", function(){
			if($(this).hasClass("maxMain")) {
				$(this).removeClass('maxMain');
				$(this).css("left", 251);
				$(this).css("top", 101);
				$("#researchInfoHolder").show();
				$("#researchObjectListHolder").show();
				$("#researchActivityListHolder").show();
				$(this).text("< Hide Controls");
			} else {
				$(this).addClass('maxMain');
				$(this).css("top", 0);
				$(this).css("left", 0);
				$("#researchInfoHolder").hide();
				$("#researchObjectListHolder").hide();
				$("#researchActivityListHolder").hide();
				$(this).text("Show Controls >");
			}
			updateAllScrollbars()
		});

		$.jStorage.subscribe("dataUpdater", function(channel, payload) {
			if (channel == "dataUpdater" && payload.objectType == "windowMessage") {
				if (inArray(payload.msgTarget, "all")) {
					switch (payload.msgType) {
						case "msgUpdateResearchInfo":
							parseResearchData(payload.msgData.researchData);
							latestGameData.researchData = payload.msgData.researchData;
							loadResearchMap(payload.msgData.researchData);
							$("#researchHolder").overscrollTo("#" + centerTech);
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
					}
				}
			}
		});

		getResearchData();
	});

	function handleResearchAjax(data) {
		if(data.code < 0) {
			showMessage("Error " + (-data.code) + ": " + data.message, "red", 30000);
		}
		handleAjax(data);

		if(isset(data.researchData)) {
			$.jStorage.publish("dataUpdater", new Message("msgUpdateResearchInfo", {"researchData" : data.researchData}, ["all"], window.name));
		}
	}

	function getResearchData() {
		$.post("ajaxRequest.php",
			{"action" : "getResearch", "ajaxType": "ResearchHandler"},
			handleResearchAjax,
			"json"
		).fail(function() {showMessage("An error occurred while getting data", "red", 30000);});
	}

	function showResearchInfoOverlay(researchData, techID) {
		var tech = researchData[techID];

		$("#researchInfoOverlayImage").attr("src", "resources/images/research/" + tech.techImage);
		$("#researchInfoOverlayTitle").text(tech.techName);
		$("#researchInfoOverlayDesc").text(tech.techDesc);
		$("#researchInfoOverlayEffects").html(tech.getResearchEffect(Math.max(tech.techLevel, 1)));

		$("#researchInfoOverlayEffectsControlsLevel").text(Math.max(tech.techLevel, 1)).attr("data-level", Math.max(tech.techLevel, 1)).attr("data-techID", tech.techID);

		$('#researchInfoOverlayEffectsControlsUp').unbind('click').bind('click', function() {
			var level = parseInt($("#researchInfoOverlayEffectsControlsLevel").attr("data-level"));
			if(level < tech.techLevel + 10) {
				var newLevel = level + 1;
				$("#researchInfoOverlayEffects").html(tech.getResearchEffect(newLevel));
				$("#researchInfoOverlayEffectsControlsLevel").text(newLevel).attr("data-level", newLevel).attr("data-techID", tech.techID);
				loadModHover();
				updateAllScrollbars();
			}
		});

		$('#researchInfoOverlayEffectsControlsDown').unbind('click').bind('click', function() {
			var level = parseInt($("#researchInfoOverlayEffectsControlsLevel").attr("data-level"));
			if(level > 1) {
				var newLevel = level - 1;
				$("#researchInfoOverlayEffects").html(tech.getResearchEffect(newLevel));
				$("#researchInfoOverlayEffectsControlsLevel").text(newLevel).attr("data-level", newLevel).attr("data-techID", tech.techID);
				loadModHover();
				updateAllScrollbars();
			}
		});

		if(tech.techLevel) {
			$("#researchInfoOverlayPositionCenter1").text("Level");
			$("#researchInfoOverlayPositionCenter2").text(tech.techLevel);
		} else if(tech.canResearch(researchData)) {
			$("#researchInfoOverlayPositionCenter1").text("");
			$("#researchInfoOverlayPositionCenter2").text("Unlocked");
		} else {
			$("#researchInfoOverlayPositionCenter1").text("Not");
			$("#researchInfoOverlayPositionCenter2").text("Researched");
		}

		for(var i in tech.directions) {
			var direction = tech.directions[i];
			var directionString = direction[0] + "_" + direction[1];

			var id = tech.getOffsetID(direction);
			if(id) {
				var adjacent = researchData[id];

				$("#researchInfoOverlayPosition_" + directionString + "_Line").show();

				if(adjacent.techLevel) {
					$("#researchInfoOverlayPosition_" + directionString).html(adjacent.techName + "<br>(Level " + adjacent.techLevel + ")");
				} else if(adjacent.canResearch(researchData)) {
					$("#researchInfoOverlayPosition_" + directionString).html(adjacent.techName + "<br>(Unlocked)");
				} else {
					$("#researchInfoOverlayPosition_" + directionString).html(adjacent.techName + "<br>(Not Researched)");
				}
			} else {
				$("#researchInfoOverlayPosition_" + directionString + "_Line").hide();
				$("#researchInfoOverlayPosition_" + directionString).text("");
			}
		}

		loadModHover();

		function hideInfoBox() {
			$("#blankOut").hide();
			$("#researchOverlayHolder").hide();
		}

		$("#researchOverlayHolder").show();
		$("#blankOut").show().on("click", hideInfoBox);

		$("#researchInfoOverlayClose").on("click", hideInfoBox);
	}

	function resetResearchMap() {
		$("#researchTileHolder").empty();
	}

	function loadResearchMap(researchData) {
		resetResearchMap();
		var svg = document.getElementById('researchTileHolder');
		for( var techID in researchData ) {
			var tech = researchData[techID];

			var hexColor = "#FF0000";
			var glowColor = "#glowRed";

			if(tech.techPoints) {
				hexColor = "#00FFFF";
				glowColor = "#glowCyan";
			} else if(tech.techLevel) {
				hexColor = "#00CC00";
				glowColor = "#glowGreen";
			} else if(tech.canResearch(researchData)) {
				hexColor = "#FF6600";
				glowColor = "#glowOrange";
			}

			var hex = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
			hex.setAttribute("points", "-50,0 -25,43 25,43 50,0 25,-43 -25,-43");
			hex.setAttribute("fill" , "#031A3A");
			hex.setAttribute("stroke-width", "5");
			hex.setAttribute("stroke", hexColor);
			hex.setAttribute("style", "filter:url(" + glowColor + ")");

			var image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
			image.setAttributeNS("http://www.w3.org/1999/xlink","href", "resources/images/research/" + tech.techImage);
			image.setAttribute("x", -46);
			image.setAttribute("y", -40);
			image.setAttribute("width" , "92");
			image.setAttribute("height", "80");
			image.setAttribute("mask"  , "url(#active)");

			var bgHex = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
			bgHex.setAttribute("class" , "text");
			bgHex.setAttribute("points", "-46,0 -41,10 41,10 46,0 32,-25 -32,-25");
			bgHex.setAttribute("fill" , "rgba(20,20,20,0.8)");
			bgHex.setAttribute("style", "display: none; filter:url(#glowNormal)");

			var txtLine1 = document.createElementNS('http://www.w3.org/2000/svg', 'text');
			txtLine1.setAttribute("class" , "text");
			txtLine1.setAttribute("x", "0");
			txtLine1.setAttribute("y", "-15");
			txtLine1.setAttribute("text-anchor", "middle");
			txtLine1.setAttribute("style", "font-size: 8; fill: rgba(255,255,255,0.8); display: none;");
			txtLine1.textContent = tech.techNameLine1;

			var txtLine2 = document.createElementNS('http://www.w3.org/2000/svg', 'text');
			txtLine2.setAttribute("class" , "text");
			txtLine2.setAttribute("x", "0");
			txtLine2.setAttribute("y", "-5");
			txtLine2.setAttribute("text-anchor", "middle");
			txtLine2.setAttribute("style", "font-size: 8; fill: rgba(255,255,255,0.8); display: none;");
			txtLine2.textContent = tech.techNameLine2;

			var txtLine3 = document.createElementNS('http://www.w3.org/2000/svg', 'text');
			txtLine3.setAttribute("class" , "text");
			txtLine3.setAttribute("x", "0");
			txtLine3.setAttribute("y", "5");
			txtLine3.setAttribute("text-anchor", "middle");
			txtLine3.setAttribute("style", "font-size: 8; fill: rgba(255,255,255,0.8); display: none;");
			txtLine3.textContent = tech.techNameLine3;

			var group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
			group.setAttribute("transform", "translate(" + toX(tech.r) + "," + toY(tech.q, tech.r) + ")");
			group.setAttribute("id", techID);
			group.setAttribute("data-hoverGlow", glowColor);

			group.appendChild(hex);
			group.appendChild(image);
			group.appendChild(bgHex);
			group.appendChild(txtLine1);
			group.appendChild(txtLine2);
			group.appendChild(txtLine3);

			if(tech.techLevel) {
				var levelBar = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
				levelBar.setAttribute("class" , "text");
				levelBar.setAttribute("points", "-30,30 -23,40 23,40 30,30");
				levelBar.setAttribute("fill" , "rgba(20,20,20,0.8)");
				levelBar.setAttribute("style", "display: none; filter:url(#glowNormal)");

				var levelText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
				levelText.setAttribute("class" , "text");
				levelText.setAttribute("x", "0");
				levelText.setAttribute("y", "38");
				levelText.setAttribute("text-anchor", "middle");
				levelText.setAttribute("style", "font-size: 8; fill: white; display: none;");
				levelText.textContent = "Level " + tech.techLevel;

				group.appendChild(levelBar);
				group.appendChild(levelText);
			}

			svg.appendChild(group);
			$(group).bind("mouseover", function () {
				var hover = document.getElementById($(this).attr("id")).getAttribute("data-hoverGlow");
				$(this).attr("style", "filter:url(" + hover + ")");
				$(this).find(".text").show();
			}).bind("mouseout", function () {
				$(this).attr("style", "");
				$(this).find(".text").hide();
			}).bind("click", function () {
				showResearchInfoOverlay(researchData, $(this).attr("id"));
			});
		}
	}
</script>
{{/block}}
