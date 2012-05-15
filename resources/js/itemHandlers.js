function itemhandlerDisplayData(oldData, additionalParams) {
	var newData = clone(oldData);
	if(isset(additionalParams.formatNameParams)) {
		newData.itemName = vsprintf(newData.itemName, additionalParams.formatNameParams); 
	}
	
	if(isset(additionalParams.formatDescParams)) {
		newData.itemDesc = vsprintf(newData.itemDesc, additionalParams.formatDescParams); 
	}
	
	if(isset(additionalParams.formatImageParams)) {
		newData.itemImage = vsprintf(newData.itemImage, additionalParams.formatImageParams); 
	}
	
	if(isset(additionalParams.formatNewVisibility)) {
		newData.itemVisibility = additionalParams.formatNewVisibility; 
	}
	
	return newData;
}