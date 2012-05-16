
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