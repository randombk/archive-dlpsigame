var dbResData = null;
var dbBuildData = null;
var dbModData = null;
var dbResearchData = null;
var dbResearchPosData = null;

function loadGameDataFailure(code) {
	showMessage("Error " + code + ": Unable to get game data", "red");
}

function loadCachedData() {
	dbResData = JSON.parse(localStorage.getItem("dbResData"));
	dbBuildData = JSON.parse(localStorage.getItem("dbBuildData"));
	dbModData = JSON.parse(localStorage.getItem("dbModData"));
	dbResearchData = JSON.parse(localStorage.getItem("dbResearchData"));
	dbResearchPosData = JSON.parse(localStorage.getItem("dbResearchPosData"));

	if (dbResData != null && dbBuildData != null && dbModData != null && dbResearchData != null && dbResearchPosData != null) {
		$(document).trigger('gameDataLoaded');
	} else {
		loadGameDataFailure(5);
	}
}

function loadGameData(cacheTime) {
	if (localStorage.getItem("cacheTime") == cacheTime) {
		loadCachedData();
	} else {
		$.post("ajaxRequest.php", {
			"action" : "getGameData",
			"ajaxType" : "DataLoader"
		}, function(data) {
			if (data.code >= 0) {
				localStorage.setItem("dbResData", JSON.stringify(data.dataITEMS));
				localStorage.setItem("dbBuildData", JSON.stringify(data.dataBUILDINGS));
				localStorage.setItem("dbModData", JSON.stringify(data.dataMODIFIERS));
				localStorage.setItem("dbResearchData", JSON.stringify(data.dataRESEARCH));
				localStorage.setItem("dbResearchPosData", JSON.stringify(data.dataRESEARCHPOS));
				localStorage.setItem("cacheTime", JSON.stringify(data.cacheTime));

				loadCachedData();
			} else {
				loadGameDataFailure(-data.code);
			}
		}, "json").fail(function() {
			loadGameDataFailure(1);
		});
	}
}
