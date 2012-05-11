var dbResData = null;
var dbBuildData = null;
var dbModData = null;
var dbResearchData = null;
var dbResearchPosData = null;

function loadGameDataFailure(code) {
	showMessage("Error "+ code +": Unable to get game data", "red");
}

function loadGameData() {
	$.post("ajaxRequest.php", 
		{"action" : "getGameData", "ajaxType": "DataLoader"},
		function(data){
			if(data.code >= 0) {
				
				dbResData = data.dataRESOURCES;				
				dbBuildData = data.dataBUILDINGS;				
				dbModData = data.dataMODIFIERS;				
				dbResearchData = data.dataRESEARCH;
				dbResearchPosData = data.dataRESEARCHPOS;
				
				$(document).trigger('gameDataLoaded');
			} else {
				loadGameDataFailure(-data.code);
			}
			
		}, "json")
	.fail(function() { loadGameDataFailure(1); });
}

$(document).ready(function(){
	loadGameData();
})
