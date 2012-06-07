/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

"use strict";
function parseResearchData(data) {
	for(var i in dbResearchData) {
		var techData = data[i];
		if(techData) {
			data[i] = new Research(i, techData[0], techData[1]);
		} else {
			data[i] = new Research(i, 0, 0);
		}
	}
	return data;
}

function Research(techID, techLevel, techPoints) {
	this.techID = techID;
	this.techBaseData = dbResearchData[this.techID];
	this.techLevel = techLevel || 0;
	this.techPoints = techPoints || 0;

	//Load base data
	this.q			= clone(this.techBaseData.q);
	this.r			= clone(this.techBaseData.r);
	this.techImage	= clone(this.techBaseData.techImage);
	this.techName	= clone(this.techBaseData.techName);
	this.techNameLine1	= clone(this.techBaseData.techNameLine1);
	this.techNameLine2	= clone(this.techBaseData.techNameLine2);
	this.techNameLine3	= clone(this.techBaseData.techNameLine3);
	this.techDesc		= clone(this.techBaseData.techDesc);
	this.techEffects	= clone(this.techBaseData.techEffects);
	this.techMods		= clone(this.techBaseData.techMods);
	this.researchCost	= clone(this.techBaseData.researchCost);
	this.researchNoteBuildingReq    = clone(this.techBaseData.researchNoteBuildingReq);
	this.researchNotePassive	    = clone(this.techBaseData.researchNotePassive);
	this.researchNoteCost	        = clone(this.techBaseData.researchNoteCost);
	this.researchNoteConsumption	= clone(this.techBaseData.researchNoteConsumption);
}

Research.prototype.directions = [ [1, 0], [0, 1], [-1, 1], [-1, 0], [0, -1], [1, -1] ];

Research.prototype.getOffsetID = function(offset) {
	var positionID = (this.q+offset[0]) + ":" + (this.r+offset[1]);
	if(isset(dbResearchPosData[positionID])) {
		return dbResearchPosData[positionID].techID;
	} else {
		return null;
	}
};

Research.prototype.getNeighborIDs = function() {
	var ids = [];
	for(var i in this.directions) {
		var direction = this.directions[i];
		var id = this.getOffsetID(direction);
		if(id) {
			ids.push(id);
		}
	}
	return ids;
};

Research.prototype.canResearch = function(researchData) {
	if(this.techLevel || this.techPoints) {
		return true;
	}

	var neighborIDs = this.getNeighborIDs();
	for(var i in neighborIDs) {
		var id = neighborIDs[i];
		if(isset(researchData[id]) && researchData[id].techLevel) {
			return true;
		}
	}

	return false;
};

Research.prototype.getResearchColor = function(researchData) {
	if(this.techPoints) {
		return "cyan";
	} else if(this.techLevel) {
		return "green";
	} else if(this.canResearch(researchData)) {
		return "orange";
	} else {
		return "red";
	}
};

Research.prototype.getResearchMods = function(level) {
	if(!level && level !== 0) {
		level = this.techLevel;
	}
	var mods = {};

	for(var i in this.techMods) {
		var modInfo = this.techMods[i];
		if(level >= modInfo[0]) {
			mods[i] = modInfo[1]*Math.pow(level-modInfo[0], modInfo[2]) + modInfo[3];
		}
	}
	return mods;
};

Research.prototype.getResearchEffect = function(level) {
	if(!level && level !== 0) {
		level = this.techLevel;
	}
	var effectHTML = "";
	for(var i = 1; i <= level; i++) {
		if(isset(this.techEffects[i])) {
			effectHTML += this.techEffects[i] + "<br>";
		}
	}

	var researchMods = this.getResearchMods(level);
	for(var i in researchMods) {
		effectHTML += "<span class='modLink' data-modID='" + i + "' data-amount='" + researchMods[i] + "'></span>";
	}

	return effectHTML;
};

Research.prototype.getTotalNotesRequired = function(){
	return 200;
};

Research.prototype.getResearchTime = function(objectData){
	return 60;
};
