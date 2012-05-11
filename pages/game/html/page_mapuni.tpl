{block name="title" prepend}{"Info"}{/block}
{block name="additionalIncluding" append}
	<script src="resources/js/vendor/kinetic-v4.5.4.js?v={$VERSION}"></script>
	<script src="resources/js/vendor/Raf.js?v={$VERSION}"></script>
	<script src="resources/js/vendor/Animate.js?v={$VERSION}"></script>
	<script src="resources/js/vendor/Scroller.js?v={$VERSION}"></script>
	<script src="resources/js/MapGalaxy.js"></script>
{/block}

{block name="content"}
<table class="pageTable">
	<tr>
		<th>Universe Map</th>
	</tr>
</table>
<div id="mapHolder" style="top: 17px; bottom: 0px; left: 0px; right: 0px; min-height: 500px; position: absolute; background: rgba(10,10,20, 0.8);"></div>
{/block}

{block name="winHandlers" append}
	<script>
		//Override container size
		$('#gamePageContainer').addClass("absFill").css("width", "100%").css("height", "100%"); 
	</script>
	{literal}
	<script>
		$("#gameMenu").ready(function() {
			$("#gameMenu #pageMap").addClass("active");
		});
		
		var mapGalaxy = null;
        window.onload = function () {
            mapGalaxy = new MapGalaxy("mapHolder");
        	mapGalaxy.resize($("#mapHolder").width(), $("#mapHolder").height());
        };
        
        $(window).resize(function() {
        	mapGalaxy.resize($("#mapHolder").width(), $("#mapHolder").height());
		});
	</script>
	{/literal}
{/block}
