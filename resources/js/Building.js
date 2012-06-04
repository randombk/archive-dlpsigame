/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

"use strict";
function parseBuildingData(data) {
	for(var i in data) {
		if(data[i] instanceof Building) {
			//Data array has already been processed
			continue;
		} else {
			data[i] = new Building(i, data[i]);
		}
	}
	return data;
}

function Building(buildID, buildingLevel) {
	this.buildID = buildID;
	this.buildBaseData = dbBuildData[this.buildID];
	console.log(buildingLevel);
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
	this.buildingReq		    = clone(this.buildBaseData.buildingReq);
	this.buildDifficulty		= clone(this.buildBaseData.buildDifficulty);
	this.resBaseReq		        = clone(this.buildBaseData.resBaseReq);
	this.resBaseConsumption		= clone(this.buildBaseData.resBaseConsumption);
	this.resBaseProduction		= clone(this.buildBaseData.resBaseProduction);
}

Building.prototype.getMaxLevel = function() {
	return this.buildMax;
};
