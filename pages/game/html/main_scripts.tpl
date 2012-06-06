<!-- Load base libraries -->
<script src="resources/js/vendor/modernizr-2.6.2.min.js?v={{$VERSION}}"></script>
<script src="resources/js/vendor/jquery.min.js?v={{$VERSION}}"></script>
<!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js?v={{$VERSION}}"></script-->

<!-- Load misc. libraries -->
<script src="resources/js/vendor/jstorage.min.js?v={{$VERSION}}"></script>
<script src="resources/js/vendor/moment.min.js?v={{$VERSION}}"></script>
<script src="resources/js/vendor/handlebars.runtime.js?v={{$VERSION}}"></script>
<script src="resources/js/vendor/sprintf.min.js?v={{$VERSION}}"></script>

<!-- Load jQuery plug-ins -->
<script src="resources/js/vendor/jQueryUI/jquery-ui.min.js?v={{$VERSION}}"></script>
<!--script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js?v={{$VERSION}}"></script-->
<script src="resources/js/vendor/jquery.cookie.js?v={{$VERSION}}"></script>
<script src="resources/js/vendor/jquery.validationEngine.js?v={{$VERSION}}"></script>
<script src="resources/js/vendor/jquery.tinyscrollbar.min.js?v={{$VERSION}}"></script>

<!-- http://www.vinylfox.com/lib/latest/examples/grid/array-grid-datadrop.html -->
<script src="resources/js/main.js?v={{$VERSION}}"></script>
<script src="resources/js/tickService.js?v={{$VERSION}}"></script>
<script src="resources/js/Message.js?v={{$VERSION}}"></script>
<script src="resources/js/gameData.js?v={{$VERSION}}"></script>
<script src="resources/js/objectUtils.js?v={{$VERSION}}"></script>

<!-- Load global handlebars templates -->
<script src="handlebars/hoverItem.js"></script>
<script src="handlebars/objectListItem.js?v={{$VERSION}}"></script>

<!-- Load Tool Tips -->
<script src="resources/js/tooltips.js?v={{$VERSION}}"></script>

<!-- Load handlers -->
<script src="resources/js/Item.js?v={{$VERSION}}"></script>
<script src="resources/js/Building.js?v={{$VERSION}}"></script>
<script src="resources/js/Research.js?v={{$VERSION}}"></script>

{{block name="additionalIncluding"}}{{/block}}
{{block name="winHandlers"}}{{/block}}

<script id="ga" type="text/javascript">
	//GOOGLE ANALYTICS
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-41226902-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();
</script>

<script type="text/javascript">
	var latestGameData = {};

	//Load scrollbars on page
	(function($) {

		$('.scrollable').each(function() {
			$(this).tinyscrollbar();
		});

		$('.scrollbar').on('dragstart', function(event) {
			event.preventDefault()
		});

		$(window).on("resize", function() {
			updateAllScrollbars();
		});
		window.name = getGUID();
	})(jQuery);

	function updateAllScrollbars() {
		$('.scrollable').each(function() {
			$(this).tinyscrollbar_update();
		});
	}

	function handleAjax(data) {
		for(var i in data) {
			latestGameData[i] = data[i];
		}

		if(isset(data.objectItems)) {
			$.jStorage.publish("dataUpdater", new Message("msgUpdateItems", {"objectID" : data.objectID, "itemData" : data.objectItems}, ["all"], window.name));
		}

		if(isset(data.objectBuildings)) {
			$.jStorage.publish("dataUpdater", new Message("msgUpdateBuildings", {"objectID" : data.objectID, "buildingData" : data.objectBuildings}, ["all"], window.name));
		}

		if(isset(data.notifications)) {
			$.jStorage.publish("dataUpdater", new Message("msgUpdateNotifications", {"notificationData" : data.notifications}, ["all"], window.name));
		}
	}

</script>

<script>
	//Load tickService
	var instance = null;

	(function($) {
		loadGameData({{$cacheTime}});
		instance = new tickService({{$timestamp}});
	})(jQuery);
</script>
