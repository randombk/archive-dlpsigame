"use strict";
function parseItemData(data, params) {
	for(var i in data) {
		params = clone(data[i]);
		data[i] = new Item(i, params);
	}
	return data;
}

function Item(itemID, itemParams) {
	this.itemID = itemID;
	this.itemIDArray = this.itemID.split("_", 2);
	this.itemBaseID = this.itemIDArray[0];
	this.itemSpecialID = this.itemIDArray[1] || "";
	this.itemBaseData = dbItemData[this.itemBaseID];
	this.itemParams = itemParams;
	this.quantity = itemParams.quantity || 0;
	
	//Load base data
	this.itemType		= clone(this.itemBaseData.itemType);
	this.itemVisibility	= clone(this.itemBaseData.itemVisibility);
	this.itemImage		= clone(this.itemBaseData.itemImage);
	this.itemName		= clone(this.itemBaseData.itemName);
	this.itemDesc		= clone(this.itemBaseData.itemDesc);
	this.itemWeight		= clone(this.itemBaseData.itemWeight);
	this.itemFlags		= clone(this.itemBaseData.itemFlags);
	
	this.runHandlers();
}

Item.prototype.hasFlag = function(flag) {
	return isset(this.itemFlags[flag]);
};

Item.prototype.runHandlers = function() {
	if(this.hasFlag("MultiItem")) {
		this.itemhandlerDisplayData();
	}
};

Item.prototype.getTotalWeight = function() {
	return this.quantity * this.itemWeight;
};

Item.prototype.getHoverContent = function() {
	var template = Handlebars.templates['hoverItem.tmpl'];
	var context = {};
	context.quantity = this.quantity;
	if(context.quantity < 0)
		context.quantity = -context.quantity;
	if(context.quantity > 0) {
		context = {
			quantity: niceNumber(context.quantity),
			itemName: this.itemName,
			itemDesc: this.itemDesc,
			itemType: this.itemType,
			itemFlags: this.itemFlags,
			itemWeight: niceNumber(this.itemWeight),
			itemTotalWeight: niceNumber(context.quantity * this.itemWeight),
			itemValue: "NYI",
			itemTotalValue: "NYI",
			itemImage: this.itemImage
		};
	} else {
		context = {
			quantity: null,
			itemName: this.itemName,
			itemDesc: this.itemDesc,
			itemType: this.itemType,
			itemFlags: this.itemFlags,
			itemWeight: niceNumber(this.itemWeight),
			itemValue:  "NYI",
			itemImage:  this.itemImage
		};
	}
	return template(context);
};

//Item Handlers
Item.prototype.itemhandlerDisplayData = function() {
	if(isset(this.itemParams.formatNameParams)) {
		this.itemName = vsprintf(this.itemBaseData.itemName, this.itemParams.formatNameParams); 
	}
	
	if(isset(this.itemParams.formatDescParams)) {
		this.itemDesc = vsprintf(this.itemBaseData.itemDesc, this.itemParams.formatDescParams); 
	}
	
	if(isset(this.itemParams.formatImageParams)) {
		this.itemImage = vsprintf(this.itemBaseData.itemImage, this.itemParams.formatImageParams); 
	}
	
	if(isset(this.itemParams.formatNewVisibility)) {
		this.itemVisibility = this.itemParams.formatNewVisibility; 
	}
	
	return this;
};