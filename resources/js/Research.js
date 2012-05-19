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
