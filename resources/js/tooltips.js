function staticTT(element, options) {
	var GUID = getGUID();
	
	element.addClass("staticTT-" + GUID);
	element.addClass("tt-init");
	element.hover(function(event) {
		element.tooltip("enable");
		element.tooltip("open");
		event.stopImmediatePropagation();
	}, function(event) {
		var fixed = window.setTimeout(function() {
			element.tooltip("close");
		    element.tooltip("disable");
		}, 250);
		
		element.on("mouseover", function(){window.clearTimeout(fixed);});
		
		$(".tooltip-" + GUID).hover(
		    function(){window.clearTimeout(fixed);},
		    function(){
		    	element.tooltip("close");
		    	element.tooltip("disable");
		    }
		);
		event.stopImmediatePropagation();
	}).tooltip(
		{
			items: ".staticTT-" + GUID,
			tooltipClass: "tooltip-main tooltip-" + GUID,
			show: options.show,
			hide: false,
			position: options.position || { my: "left top+5", at: "left bottom", collision: "flipfit" },
			track: options.track,
			content : options.content,
			open: options.open
		}
	);
}

function getTTInvItem(resName, quantity) {
	var resObj = dbResData[resName];
	var template = Handlebars.templates['hoverResource.tmpl'];
	var context = {};
	if(quantity < 0)
		quantity = -quantity;
	if(quantity > 0) {
		context = {
			quantity: niceNumber(quantity),
			resName: resObj.resName,
			resDesc: resObj.resDesc,
			resType: resObj.resType,
			resWeight: niceNumber(resObj.resWeight),
			resTotalWeight: niceNumber(resObj.resWeight * quantity),
			resValue: niceNumber(resObj.resWeight),
			resTotalValue: niceNumber(resObj.resWeight * quantity),
			resImage: resObj.resImage
		};
	} else {
		context = {
			quantity: null,
			resName: resObj.resName,
			resDesc: resObj.resDesc,
			resType: resObj.resType,
			resWeight: niceNumber(resObj.resWeight),
			resValue: niceNumber(resObj.resWeight),
			resImage: resObj.resImage
		};
	}
	return template(context);
}

function loadResHover(resources) {
	$(".resLink").each(function() {
		var quantity = $(this).attr("data-quantity");
		var resID = $(this).attr("data-res");
		if(quantity) {
			$(this).text(niceNumber(quantity) + " " + dbResData[resID].resName);
			if($(this).attr("data-type") == "diff") {
				if(quantity > 0) {
					$(this).addClass("green");
				} else {
					$(this).addClass("red")
				}		
			} else {
				if(typeof resources !== 'undefined') {
					if(resources[resID] >= quantity) {
						$(this).addClass("green");
					} else {
						$(this).addClass("red")
					}	
				}
			}
		} else {
			$(this).text(dbResData[resID].resName);
			$(this).addClass("green");
		}
		
		if($(this).hasClass("tt-init")) {
			$(this).tooltip("option", "content", function() {
				return getTTInvItem($(this).attr("data-res"), quantity);
			});
		} else {
			staticTT(
				$(this), 
				{
					content : function() {
						return getTTInvItem($(this).attr("data-res"), quantity);
					}, 
					open: function( event, ui ) {
						loadHovers({resources: resources});
					},
					show: { delay: 300, effect: "show" }
				}
			);	
		}
	});
}

function loadModHover() {
	$(".modLink").each(function() {
		var amount = $(this).attr("data-amount");
		var modID = $(this).attr("data-modID");
		if(amount) {
			if( amount > 0) {
				$(this).text("+" + niceFloat(amount) + " " + dbModData[modID].modName);
				if( dbModData[modID].modType != "buff") {
					$(this).addClass("red");
				} else {
					$(this).addClass("green")
				}
			} else {
				$(this).text(niceFloat(amount) + " " + dbModData[modID].modName);
				if( dbModData[modID].modType != "buff") {
					$(this).addClass("green");
				} else {
					$(this).addClass("red")
				}
			}
		} else {
			$(this).text(niceNumber(amount) + " " + dbModData[modID].modName);
			if( dbModData[modID].modType != "buff") {
				$(this).addClass("red");
			} else {
				$(this).addClass("green")
			}
		}
		
		$(this).tooltip({
			items: "span.modLink",
			tooltipClass: "tooltip-main tooltip-mod",
			track : true,
			content : function() {
				return dbModData[modID].modDesc;
			},
			show: { delay: 300, effect: "show" }
		});
	});
}

function loadHovers(data) {
	loadResHover(data.resources);
	loadModHover();
}
