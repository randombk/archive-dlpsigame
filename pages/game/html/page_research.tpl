{block name="title" prepend}{"Info"}{/block}

{block name="additionalIncluding" append}
<script type="text/javascript" src="resources/js/vendor/jQuerySVG/jquery.svg.js?v={$VERSION}"></script>
<script type="text/javascript" src="resources/js/vendor/jQuerySVG/jquery.svgdom.js?v={$VERSION}"></script>
<script type="text/javascript" src="resources/js/vendor/jquery.overscroll.js?v={$VERSION}"></script>
{/block}

{block name="additionalStylesheets" append}
<link rel="stylesheet" href="resources/css/research.css?v={$VERSION}">
{/block}

{block name="content"}

<div class="absFill">
	<div id="researchHolder" class="absFill">
		<svg 
			id="researchSVG" 
			xmlns="http://www.w3.org/2000/svg" 
			version="1.1" 
			width="6000"
			height="6000"
			viewBox="-2500 -2500 5000 5000"
		>
			<defs>
				<mask id="active" maskUnits="userSpaceOnUse" x="-46" y="-40" width="92" height="80">
					<polygon id="center" points="-46,0 -23,40 23,40 46,0 23,-40 -23,-40" fill="white"/>
				</mask>
				<mask id="inactive" maskUnits="userSpaceOnUse" x="-36" y="-29" width="72" height="58">
					<polygon id="center" points="-36,0 -18,29 18,29 36,0 18,-29 -18,-29" fill="grey"/>
				</mask>
			
				<filter id="glowWeapons" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
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
				<filter id="glowDefense" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
					<feGaussianBlur in="SourceGraphic" stdDeviation="6.000000" result="blur"/>
					<feColorMatrix result="bluralpha" type="matrix" 
						values="1	0	0	0 
								0	1	0	0 
								0	0	0	0 
								0	0	0	0 
								0	0	1	0"/>
					<feMerge>
						<feMergeNode />
						<feMergeNode  in="SourceGraphic"/>
					</feMerge>
				</filter>
				<filter id="glowDiplomatic" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
					<feGaussianBlur in="SourceGraphic" stdDeviation="6.000000" result="blur"/>
					<feColorMatrix result="bluralpha" type="matrix" 
						values="0	0	0	0 
								0	1	0	0 
								0	0	1	0 
								0	0	1	0 
								0	0	1	0"/>
					<feMerge>
						<feMergeNode />
						<feMergeNode  in="SourceGraphic"/>
					</feMerge>
				</filter>
				<filter id="glowEconomic" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
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
				<filter id="glowFleet" filterUnits="userSpaceOnUse" x="-100" y="-86" width="200" height="192">
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
			
			<!--g transform="translate(-86, -50)">
				<polygon points="-50,0 -25,43 25,43 50,0 25,-43 -25,-43" fill="#031A3A" stroke-width="5" stroke="#00FFFF" style="filter:url(#glowResearched)"></polygon>
				<image xlink:href="resources/images/research/test.png" x="-46" y="-40" width="92" height="80" mask="url(#active)"></image>
				<polygon class="text" points="-46,0 46,0 32,-25 -32,-25" fill="rgba(20,100,20,0.5)" style="display: none; filter:url(#glowNormal)"></polygon>
				<text class="text" x="0" y="-15" text-anchor="middle" style="font-size: 8; fill: rgba(255,255,255,0.8); display: none;" >
					Advanced
				</text>
				<text class="text" x="0" y="-5" text-anchor="middle" style="font-size: 8; fill: rgba(255,255,255,0.8); display: none;" >
					X-Ray Lasers
				</text>
				
				<polygon points="-30,30 -23,40 23,40 30,30 " fill="rgba(13,100,20,0.6)" style="filter:url(#glowNormal)"></polygon>
				<text x="0" y="39" text-anchor="middle" style="font-size: 8; fill: white;" >
					Level 20
				</text>
			</g -->
			
		</svg>
	</div>

	<div id="researchControlsToggle">
		&lt; Hide Controls
	</div>

	<div id="researchInfoHolder"></div>

	<div id="researchObjectListHolder">

	</div>

	<div id="researchActivityListHolder">

	</div>

</div>

<div id="researchOverlayHolder" class="absFill" style="text-align: center; vertical-align: middle; display: none;">
	<div id="researchInfoOverlay" style="z-index: 901; position: relative; top: 20%; margin: auto; width: 650px; height: 300px; background: rgba(3,26,58,0.9); border: 1px #D3D3D3 solid;">
		<div id="researchInfoOverlayClose" class="buttonDiv red-over" style="position: absolute; top: 10px; right: 10px; width: 10px; height: 15px; border: 1px #D3D3D3 solid;">X</div>
		<div style="position: absolute; top: 10px; left: 10px; width: 115px; height: 100px; border: 1px #D3D3D3 solid;">
			<img id="researchInfoOverlayImage" height="100" width="115"/>
		</div>
		<div id="researchInfoOverlayTitle" style="position: absolute; top: 10px; left: 135px; right: 20px; height: 15px; border: 1px #D3D3D3 solid; background-color: #1E3E5D; text-align: center;"></div>
		<div id="researchInfoOverlayDesc" style="position: absolute; top: 35px; left: 135px; right: 10px; height: 75px; border: 1px #D3D3D3 solid;"></div>
		
		<div style="position: absolute; top: 120px; left: 10px; width: 280px; bottom: 10px; border: 1px #D3D3D3 solid;">
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
				
				<line id="researchInfoOverlayPositionBottomLine" x1="0" y1="30" x2="0" y2="45" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-100" y="52" width="200" height="30">
					<span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPositionBottom"></span>
				</foreignObject>
				
				<line id="researchInfoOverlayPositionTopLine" x1="0" y1="-30" x2="0" y2="-45" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-100" y="-80" width="200" height="30">
					<span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPositionTop"></span>
				</foreignObject>
				
				<line id="researchInfoOverlayPositionTopRightLine" x1="25" y1="-15" x2="40" y2="-25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="40" y="-40" width="100" height="30">
					<span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPositionTopRight"></span>
				</foreignObject>
				
				<line id="researchInfoOverlayPositionBottomRightLine" x1="25" y1="15" x2="40" y2="25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="40" y="20" width="100" height="30">
					<span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPositionBottomRight"></span>
				</foreignObject>
				
				<line id="researchInfoOverlayPositionTopLeftLine" x1="-25" y1="-15" x2="-40" y2="-25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-140" y="-40" width="100" height="30">
					<span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPositionTopLeft"></span>
				</foreignObject>
				
				<line id="researchInfoOverlayPositionBottomLeftLine" x1="-25" y1="15" x2="-40" y2="25" style="stroke:#D3D3D3; stroke-width:1;"/>
				<foreignObject x="-140" y="20" width="100" height="30">
					<span xmlns="http://www.w3.org/1999/xhtml" id="researchInfoOverlayPositionBottomLeft"></span>
				</foreignObject>
			</svg>
		</div>
		<div id="researchInfoOverlayEffects" style="position: absolute; top: 120px; left: 300px; right: 10px; height: 80px; border: 1px #D3D3D3 solid; text-align: left; padding-left: 5px;"></div>
		<div id="" style="position: absolute; top: 208px; left: 300px; right: 10px; height: 80px; border: 1px #D3D3D3 solid;"></div>
	</div>
</div>

{/block}

{block name="winHandlers" append}
{literal}
<script>
	//Override container size
	$('#gamePageContainer').addClass("absFill").css("width", "100%").css("height", "100%"); 

	function toY(q, r) {
		return (q + r/2) * 100;
	}

	function toX(r) {
		return 86 * r;
	}
	
	$("#gameMenu").ready(function() {
		$("#gameMenu #pageResearch").addClass("active");		
	}); 
	
	$("#researchSVG").ready(function() {
		$("#researchHolder").overscroll().overscrollTo("#center");
	});
	
	function showResearchInfoOverlay(techID) {
		var data = dbResearchData[techID];
		
		$("#researchInfoOverlayImage").attr("src", "resources/images/research/" + data.techImage);
		$("#researchInfoOverlayTitle").text(data.techName);
		$("#researchInfoOverlayDesc").text(data.techDesc);
		$("#researchInfoOverlayEffects").html(data.techEffects);
		
		$("#researchInfoOverlayPositionCenter1").text("Not");
		$("#researchInfoOverlayPositionCenter2").text("Researched");
		
		if(typeof(dbResearchPosData[(data.q+1)+":"+(data.r)]) !== "undefined") {
			var adjacent = dbResearchPosData[(data.q+1)+":"+(data.r)];
			$("#researchInfoOverlayPositionBottomLine").show();
			$("#researchInfoOverlayPositionBottom").html(adjacent.techName + "<br>(Not Researched)");
		} else {
			$("#researchInfoOverlayPositionBottomLine").hide();
			$("#researchInfoOverlayPositionBottom").text("");
		}
		
		if(typeof(dbResearchPosData[(data.q-1)+":"+(data.r)]) !== "undefined") {
			var adjacent = dbResearchPosData[(data.q-1)+":"+(data.r)];
			$("#researchInfoOverlayPositionTopLine").show();
			$("#researchInfoOverlayPositionTop").html(adjacent.techName + "<br>(Not Researched)");
		} else {
			$("#researchInfoOverlayPositionTopLine").hide();
			$("#researchInfoOverlayPositionTop").text("");
		}
		
		if(typeof(dbResearchPosData[(data.q)+":"+(data.r+1)]) !== "undefined") {
			var adjacent = dbResearchPosData[(data.q)+":"+(data.r+1)];
			$("#researchInfoOverlayPositionBottomRightLine").show();
			$("#researchInfoOverlayPositionBottomRight").html(adjacent.techName + "<br>(Not Researched)");
		} else {
			$("#researchInfoOverlayPositionBottomRightLine").hide();
			$("#researchInfoOverlayPositionBottomRight").text("");
		}
		
		if(typeof(dbResearchPosData[(data.q)+":"+(data.r-1)]) !== "undefined") {
			var adjacent = dbResearchPosData[(data.q)+":"+(data.r-1)];
			$("#researchInfoOverlayPositionTopLeftLine").show();
			$("#researchInfoOverlayPositionTopLeft").html(adjacent.techName + "<br>(Not Researched)");
		} else {
			$("#researchInfoOverlayPositionTopLeftLine").hide();
			$("#researchInfoOverlayPositionTopLeft").text("");
		}
		
		if(typeof(dbResearchPosData[(data.q+1)+":"+(data.r-1)]) !== "undefined") {
			var adjacent = dbResearchPosData[(data.q+1)+":"+(data.r-1)];
			$("#researchInfoOverlayPositionBottomLeftLine").show();
			$("#researchInfoOverlayPositionBottomLeft").html(adjacent.techName + "<br>(Not Researched)");
		} else {
			$("#researchInfoOverlayPositionBottomLeftLine").hide();
			$("#researchInfoOverlayPositionBottomLeft").text("");
		}
		
		if(typeof(dbResearchPosData[(data.q-1)+":"+(data.r+1)]) !== "undefined") {
			var adjacent = dbResearchPosData[(data.q-1)+":"+(data.r+1)];
			$("#researchInfoOverlayPositionTopRightLine").show();
			$("#researchInfoOverlayPositionTopRight").html(adjacent.techName + "<br>(Not Researched)");
		} else {
			$("#researchInfoOverlayPositionTopRightLine").hide();
			$("#researchInfoOverlayPositionTopRight").text("");
		}
		
		loadModHover();
		$("#researchOverlayHolder").show();
		$("#blankOut").show();
		
		function hideInfoBox() {
			$("#blankOut").hide();
			$("#researchOverlayHolder").hide();
		}
		$("#researchInfoOverlayClose").on("click", hideInfoBox);
		$("#blankOut").on("click", hideInfoBox);
	}
	
	//Load research data
	(function($) {
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
			$(".scrollable").tinyscrollbar_update();
		});
			
		$(document).on('gameDataLoaded', function() {					
			var svg = document.getElementById('researchSVG');
			for( var techID in dbResearchData ) {
				if(techID == "code") continue;
				
				var data = dbResearchData[techID];

				var hexColor = "none";
				switch(data.researchType) {
					case "Weapons": hexColor = "#FF0000"; break;
					case "Defense": hexColor = "#FF6600"; break;
					case "Diplomatic": hexColor = "#3300FF"; break;
					case "Economic": hexColor = "#00CC00"; break;
					case "Fleet": hexColor = "#00FFFF"; break;
				}
				
				var hex = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
				hex.setAttribute("points", "-50,0 -25,43 25,43 50,0 25,-43 -25,-43");
				hex.setAttribute("fill" , "#031A3A");
				hex.setAttribute("stroke-width", "5");
				hex.setAttribute("stroke", hexColor);
				hex.setAttribute("style", "filter:url(#glow" + data.researchType + ")");
				
				var image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
				image.setAttributeNS("http://www.w3.org/1999/xlink","href", "resources/images/research/" + data.techImage);
				image.setAttribute("x", -46);
				image.setAttribute("y", -40);
				image.setAttribute("width" , "92");
				image.setAttribute("height", "80");
				image.setAttribute("mask"  , "url(#active)");
				
				var bgHex = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
				bgHex.setAttribute("class" , "text");
				bgHex.setAttribute("points", "-46,0 46,0 32,-25 -32,-25");
				bgHex.setAttribute("fill" , "rgba(20,100,20,0.5)");
				bgHex.setAttribute("style", "display: none; filter:url(#glowNormal)");
				
				var txtLine1 = document.createElementNS('http://www.w3.org/2000/svg', 'text');
				txtLine1.setAttribute("class" , "text");
				txtLine1.setAttribute("x", "0");
				txtLine1.setAttribute("y", "-15");
				txtLine1.setAttribute("text-anchor", "middle");
				txtLine1.setAttribute("style", "font-size: 8; fill: rgba(255,255,255,0.8); display: none;");
				txtLine1.textContent = data.techNameLine1;				
				
				var txtLine2 = document.createElementNS('http://www.w3.org/2000/svg', 'text');
				txtLine2.setAttribute("class" , "text");
				txtLine2.setAttribute("x", "0");
				txtLine2.setAttribute("y", "-5");
				txtLine2.setAttribute("text-anchor", "middle");
				txtLine2.setAttribute("style", "font-size: 8; fill: rgba(255,255,255,0.8); display: none;");
				txtLine2.textContent = data.techNameLine2;
				
				var group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
				group.setAttribute("transform", "translate(" + toX(data.r) + "," + toY(data.q, data.r) + ")");
				group.setAttribute("id", techID);
				
				group.appendChild(hex);
				group.appendChild(image);
				group.appendChild(bgHex);
				group.appendChild(txtLine1);
				group.appendChild(txtLine2);
				
				$(group).bind("mouseover", function () {
					$(this).attr("style", "filter:url(#glow" + data.researchType + ")"); 
					$(this).find(".text").show();
				}).bind("mouseout", function () {
					$(this).attr("style", ""); 
					$(this).find(".text").hide();
				}).bind("click", function () {
					showResearchInfoOverlay($(this).attr("id"));
				});
				
				svg.appendChild(group);
			}
		});
	})(jQuery); 
</script>
{/literal}

{/block}
