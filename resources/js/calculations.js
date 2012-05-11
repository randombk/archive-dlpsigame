
function getBuildingBaseProduction(buildingID, level) {
	if( typeof dbBuildData[buildingID]["resProduction"] != "undefined" ) {
		var maxBalLevel = dbBuildData[buildingID]["resProduction"].length;
		if(maxBalLevel <= 0 || level <= 0) {
			return null;
		}
		
		if(level > maxBalLevel) {
			var retObject = jQuery.extend(true, {}, dbBuildData[buildingID]["resProduction"][maxBalLevel - 1]);
			
			for ( var i in retObject ) {
				retObject[i] *= Math.pow(2, level - maxBalLevel);
			}
			
			return retObject;
		} else {
			return dbBuildData[buildingID]["resProduction"][level - 1];
		}
	} else {
		return null;
	}
}
	
function getBuildingBaseConsumption(buildingID, level) {
	if( typeof dbBuildData[buildingID]["resConsumption"] != "undefined" ) {
		var maxBalLevel = dbBuildData[buildingID]["resConsumption"].length;
		if(maxBalLevel <= 0 || level <= 0) {
			return null;
		}
		if(level > maxBalLevel) {
			var retObject = jQuery.extend(true, {}, dbBuildData[buildingID]["resConsumption"][maxBalLevel - 1]);
			
			for ( var i in retObject ) {
				retObject[i] *= Math.pow(2, level - maxBalLevel);
			}
			
			return retObject;
		} else {
			return dbBuildData[buildingID]["resConsumption"][level - 1];
		}
		
	} else {
		return null;
	}
}

function getBuildingBaseResearch(buildingID, level) {
	if( typeof dbBuildData[buildingID]["researchPoints"] != "undefined" ) {
		if(level <= 0) {
			return null;
		}
		
		var retObject = jQuery.extend(true, {}, dbBuildData[buildingID]["researchPoints"][level - 1]);
		return retObject;
	} else {
		return null;
	}
}