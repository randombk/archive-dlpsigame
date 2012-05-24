/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

"use strict";
var dbItemData = null;
var dbBuildData = null;
var dbModData = null;
var dbResearchData = null;
var dbResearchPosData = null;

function loadGameDataFailure(code) {
	showMessage("Error " + code + ": Unable to get game data", "red");
}

function loadCachedData() {
	dbItemData = JSON.parse(localStorage.getItem("dbItemData"));
	dbBuildData = JSON.parse(localStorage.getItem("dbBuildData"));
	dbModData = JSON.parse(localStorage.getItem("dbModData"));
	dbResearchData = JSON.parse(localStorage.getItem("dbResearchData"));
	dbResearchPosData = JSON.parse(localStorage.getItem("dbResearchPosData"));

	if (dbItemData !== null && dbBuildData !== null && dbModData !== null && dbResearchData !== null && dbResearchPosData !== null) {
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
			if (data.code < 0) {
				loadGameDataFailure(-data.code);
			} else {
				localStorage.setItem("dbItemData", JSON.stringify(data.dataITEMS));
				localStorage.setItem("dbBuildData", JSON.stringify(data.dataBUILDINGS));
				localStorage.setItem("dbModData", JSON.stringify(data.dataMODIFIERS));
				localStorage.setItem("dbResearchData", JSON.stringify(data.dataRESEARCH));
				localStorage.setItem("dbResearchPosData", JSON.stringify(data.dataRESEARCHPOS));
				localStorage.setItem("cacheTime", JSON.stringify(data.cacheTime));

				loadCachedData();
			}
		}, "json").fail(function() {
			loadGameDataFailure(1);
		});
	}
}
