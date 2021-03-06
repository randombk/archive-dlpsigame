/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

"use strict";
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

		element.on("mouseover", function() {
			window.clearTimeout(fixed);
		});

		$(".tooltip-" + GUID).hover(function() {
			window.clearTimeout(fixed);
		}, function() {
			element.tooltip("close");
			element.tooltip("disable");
		});
		event.stopImmediatePropagation();
	}).tooltip({
		items : ".staticTT-" + GUID,
		tooltipClass : "tooltip-main tooltip-" + GUID,
		show : options.show,
		hide : false,
		position : options.position || {
			my : "left top+5",
			at : "left bottom",
			collision : "flipfit"
		},
		track : options.track,
		content : options.content,
		open : options.open
	});
}

function loadItemHover(data) {
	var items = data.objectItems;
	$(".itemLink").each(function() {
		var parameters = $(this).attr("data-parameters");
		var itemID = $(this).attr("data-item");
		var negative = $(this).attr("data-quantitysign") === "-";

		var item = new Item(itemID, JSON.parse(parameters));
		if (item.quantity) {
			$(this).text(niceNumber(item.quantity) + " " + item.itemName);
			if ($(this).attr("data-type") == "diff") {
				if (negative && item.quantity > 0) {
					setColor($(this), "red");
				} else {
					if (item.quantity > 0) {
						setColor($(this), "green");
					} else {
						setColor($(this), "red");
					}
				}
			} else {
				if (isset(items)) {
					if (isset(items[itemID]) && items[itemID].quantity >= item.quantity) {
						setColor($(this), "green");
					} else {
						setColor($(this), "red");
					}
				}
			}
		} else {
			$(this).text(item.itemName);
			setColor($(this), "green");
		}

		if ($(this).hasClass("tt-init")) {
			$(this).tooltip("option", "content", function() {
				return item.getHoverContent();
			});
		} else {
			staticTT($(this), {
				content : function() {
					return item.getHoverContent();
				},
				open : function(event, ui) {
					loadHovers(data);
				},
				show : {
					delay : 300,
					effect : "show"
				}
			});
		}
	});
}

function loadModHover(data) {
	$(".modLink").each(function() {
		var amount = $(this).attr("data-amount");
		var modID = $(this).attr("data-modID");
		if (amount) {
			if (amount > 0) {
				$(this).text("+" + niceFloat(amount) + " " + dbModData[modID].modName);
				if (dbModData[modID].modType != "buff") {
					setColor($(this), "red");
				} else {
					setColor($(this), "green");
				}
			} else {
				$(this).text(niceFloat(amount) + " " + dbModData[modID].modName);
				if (dbModData[modID].modType != "buff") {
					setColor($(this), "green");
				} else {
					setColor($(this), "red");
				}
			}
		} else {
			$(this).text(niceNumber(amount) + " " + dbModData[modID].modName);
			if (dbModData[modID].modType != "buff") {
				setColor($(this), "red");
			} else {
				setColor($(this), "green");
			}
		}

		$(this).tooltip({
			items : "span.modLink",
			tooltipClass : "tooltip-main tooltip-mod",
			track : true,
			content : function() {
				return dbModData[modID].modDesc;
			},
			show : {
				delay : 300,
				effect : "show"
			},
			hide : false
		});
	});
}

function loadBuidingHover(data) {
	var buildings = data.objectBuildings;
	$(".buildingLink").each(function() {
		var level = $(this).attr("data-buildLevel");
		var buildID = $(this).attr("data-buildID");
		var linkType = $(this).attr("data-linkType");
		if (level) {
			$(this).text("Level " + level + " " + dbBuildData[buildID].buildName);
			if(linkType == "req") {
				if(isset(buildings[buildID]) && buildings[buildID].level >= level) {
					setColor($(this), "green");
				} else {
					setColor($(this), "red");
				}
			}
		} else {
			$(this).text(dbBuildData[buildID].buildName);
		}

		/*
		$(this).tooltip({
			items : "span.buildingLink",
			tooltipClass : "tooltip-main tooltip-mod",
			track : true,
			content : function() {
				return dbModData[modID].modDesc;
			},
			show : {
				delay : 300,
				effect : "show"
			},
			hide : false
		});
		*/
	});
}

function loadTextHover(data) {
	$(".textLink").each(function() {
		$(this).tooltip({
			items : "span.textLink",
			tooltipClass : "tooltip-main tooltip-link",
			track : true,
			content : $(this).attr("data-hover"),
			show : {
				delay : 300,
				effect : "show"
			},
			hide : false
		});
	});
}

function loadHovers(data) {
	loadItemHover(data);
	loadModHover(data);
	loadTextHover(data);
	loadBuidingHover(data);
}
