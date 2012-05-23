"use strict";
function tickService(unix_timestamp) {
	this.offset = 0;
	this.calcOffset(unix_timestamp);

	//Start ticking
	this.tickID = window.setInterval(tick, 1000);
}

tickService.prototype.calcOffset = function(unix_timestamp) {
	var serverTime = new Date(unix_timestamp * 1000);
	var localTime = new Date();
	this.offset = serverTime - localTime;
};

tickService.prototype.getServerTime = function() {
	var locDate = new Date();
	locDate.setTime(locDate.getTime() + this.offset);
	return locDate;
};

function updateCountdowns(time) {
	$(".countdown").each(function() {
		var startTime = $(this).attr("data-beginning");
		var endTime = $(this).attr("data-end");
		var callback = $(this).attr("data-callback");
		var timeLeft = endTime - time / 1000;

		if (timeLeft > 0) {
			if ($(this).attr("data-progressbar") == "yes") {
				$(this).progressbar("value", Math.min(time / 1000 - startTime, endTime - startTime));
			} else {
				$(this).text(niceETA(moment.duration(timeLeft, 'seconds')));
			}
		} else {
			if ($(this).attr("data-progressbar") == "yes") {
				$(this).progressbar("value", Math.min(time / 1000 - startTime, endTime - startTime));
			} else {
				$(this).text("DONE!").addClass('green').removeClass('countdown');
			}
			if (callback) {
				eval(callback);
			}
		}
	});
}

function tick() {
	var time = instance.getServerTime();
	$(".timeBanner").text(moment(time).format('YYYY-MM-DD h:mm:ssA [GMT]ZZ'));
	updateCountdowns(time);
}
