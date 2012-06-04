/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

"use strict";
function parseBuildingData(data) {
	for(var i in data) {
		if(!(data[i] instanceof Building)) {
			data[i] = new Building(i, data[i]);
		}
	}
	return data;
}

function Building(buildID, buildingLevel) {
	this.buildID = buildID;
	this.buildBaseData = dbBuildData[this.buildID];
	if(isset(buildingLevel) && typeof buildingLevel[0] === "number") {
		this.level = buildingLevel[0];
	} else {
		this.level = 0;
	}

	if(isset(buildingLevel) && typeof buildingLevel[1] === "number") {
		this.activity = buildingLevel[1];
	} else {
		this.activity = 100;
	}

	//Load base data
	this.buildImage		= clone(this.buildBaseData.buildImage);
	this.buildName      = clone(this.buildBaseData.buildName);
	this.buildDesc		= clone(this.buildBaseData.buildDesc);
	this.buildType		= clone(this.buildBaseData.buildType);
	this.buildMax		= clone(this.buildBaseData.buildMax);
	this.techReq		= clone(this.buildBaseData.techReq);
	this.buildingReq    = clone(this.buildBaseData.buildingReq);
	this.buildDifficulty	= clone(this.buildBaseData.buildDifficulty);
	this.resBaseReq         = clone(this.buildBaseData.resReq);
	this.resBaseConsumption	= clone(this.buildBaseData.resConsumption) || [];
	this.resBaseProduction	= clone(this.buildBaseData.resProduction) || [];
	this.modifiers          = clone(this.buildBaseData.modifiers) || [];
}

Building.prototype.getMaxLevel = function() {
	return this.buildMax;
};

Building.prototype.getBaseBuildingProduction = function(level) {
	if(!isset(level)) {
		level = this.level;
	}
    var maxBalLevel = Object.keys(this.resBaseProduction).length;
	if(maxBalLevel === 0 || level === 0) {
		return {};
	}
	if(level > maxBalLevel) {
		var res = {};
		for(var resID in this.resBaseProduction[maxBalLevel-1]) {
			res[resID] = this.resBaseProduction[maxBalLevel-1][resID] * Math.pow(2, level - maxBalLevel);
		}
		return res;
	} else {
		return this.resBaseProduction[level-1];
	}
};

Building.prototype.getBaseBuildingConsumption = function(level) {
	if(!isset(level)) {
		level = this.level;
	}

	var maxBalLevel = Object.keys(this.resBaseConsumption).length;
	if(maxBalLevel === 0 || level === 0) {
		return {};
	}

	if(level > maxBalLevel) {
		var res = {};
		for(var resID in this.resBaseConsumption[maxBalLevel-1]) {
			res[resID] = this.resBaseConsumption[maxBalLevel-1][resID] * Math.pow(2, level - maxBalLevel);
		}
		return res;
	} else {
		return this.resBaseConsumption[level-1];
	}
};

Building.prototype.getBaseBuildingMods = function(level) {
	if(!isset(level)) {
		level = this.level;
	}

	var maxBalLevel = Object.keys(this.modifiers).length;
	if(maxBalLevel === 0) {
		return {};
	}
	if(level > maxBalLevel || level === 0) {
		var mods = {};
		for(var modID in this.modifiers[maxBalLevel-1]) {
			mods[modID] = this.modifiers[maxBalLevel-1][modID] * Math.pow(2, level - maxBalLevel);
		}
		return mods;
	} else {
		return this.modifiers[level-1];
	}
};
