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

function getTTInvItem(itemName, quantity) {
	var itemObj = dbItemData[itemName];
	var template = Handlebars.templates['hoverItem.tmpl'];
	var context = {};
	if(quantity < 0)
		quantity = -quantity;
	if(quantity > 0) {
		context = {
			quantity: niceNumber(quantity),
			itemName: itemObj.itemName,
			itemDesc: itemObj.itemDesc,
			itemType: itemObj.itemType,
			itemWeight: niceNumber(itemObj.itemWeight),
			itemTotalWeight: niceNumber(itemObj.itemWeight * quantity),
			itemValue: niceNumber(itemObj.itemWeight),
			itemTotalValue: niceNumber(itemObj.itemWeight * quantity),
			itemImage: itemObj.itemImage
		};
	} else {
		context = {
			quantity: null,
			itemName: itemObj.itemName,
			itemDesc: itemObj.itemDesc,
			itemType: itemObj.itemType,
			itemWeight: niceNumber(itemObj.itemWeight),
			itemValue: niceNumber(itemObj.itemWeight),
			itemImage: itemObj.itemImage
		};
	}
	return template(context);
}

function loadResHover(items) {
	$(".itemLink").each(function() {
		var quantity = $(this).attr("data-quantity");
		var itemID = $(this).attr("data-item");
		if(quantity) {
			$(this).text(niceNumber(quantity) + " " + dbItemData[itemID].itemName);
			if($(this).attr("data-type") == "diff") {
				if(quantity > 0) {
					$(this).addClass("green");
				} else {
					$(this).addClass("red")
				}		
			} else {
				if(typeof items !== 'undefined') {
					if(items[itemID] >= quantity) {
						$(this).addClass("green");
					} else {
						$(this).addClass("red")
					}	
				}
			}
		} else {
			$(this).text(dbItemData[itemID].itemName);
			$(this).addClass("green");
		}
		
		if($(this).hasClass("tt-init")) {
			$(this).tooltip("option", "content", function() {
				return getTTInvItem($(this).attr("data-item"), quantity);
			});
		} else {
			staticTT(
				$(this), 
				{
					content : function() {
						return getTTInvItem($(this).attr("data-item"), quantity);
					}, 
					open: function( event, ui ) {
						loadHovers({items: items});
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
	loadResHover(data.items);
	loadModHover();
}
