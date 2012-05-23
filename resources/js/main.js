"use strict";
function getGUID() {
	// http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
		return v.toString(16);
	});
}

function pad(number) {
	var r = String(number);
	if (r.length === 1) {
		r = '0' + r;
	}
	return r;
}

function inArray(array, value) {
	return $.inArray(value, array) >= 0;
}

function isset(variable) {
	return (typeof variable !== 'undefined');
}

function isEmpty(obj) {
	return jQuery.isEmptyObject(obj);
}

function runOnceCondition(params, condition, func) {
    if(condition(params)) {
        func(params);
    } else {
        setTimeout(function() {
        	runOnceCondition(win, condition, func);
        }, 10);
    }
}

//Popup Handlers
function popupwindow(url, id, w, h) {
	var left = (screen.width / 2) - (w / 2);
	var top = (screen.height / 2) - (h / 2);
	return window.open(url, id, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

//Confirmation box
function doConfirm(msg, yesFn, noFn) {
	var confirmBox = $("#confirmBox");
	var blankOut = $("#blankOut");
	confirmBox.find(".message").html(msg);
	confirmBox.find(".yes,.no").unbind().click(function() {
		confirmBox.hide();
		blankOut.hide();
	});
	confirmBox.find(".yes").click(yesFn);
	confirmBox.find(".no").click(noFn);
	blankOut.click(function() {
		if(noFn)
			noFn();
		confirmBox.hide();
		blankOut.hide();
	});
	
	confirmBox.show();
	blankOut.show();
}

function doInput(msg, func, type, defaultValue) {
	var inputBox = $("#inputBox");
	var blankOut = $("#blankOut");
	inputBox.find(".inputText").html(msg);
	inputBox.find(".inputField").attr("type", type).val(defaultValue);
	inputBox.find(".yes,.no").unbind().click(function() {
		inputBox.hide();
		blankOut.hide();
	});
	
	inputBox.find(".yes").click(function() {
		func(inputBox.find(".inputField").val());
	});
	
	blankOut.click(function() {
		inputBox.hide();
		blankOut.hide();
	});
	
	inputBox.show();
	blankOut.show();
}

//Text Formatters
function niceNumber(i) {
	var val = Math.round(i ? i : 0);
	var post = "";
	/*if(val >= 1000000000) {
		val = Math.floor(val / 1000000);
		post = "M";
	} else if (val >= 1000000) {
		val = Math.floor(val / 1000);
		post = "K";
	}
	*/
	while (/(\d+)(\d{3})/.test(val.toString())){
		val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
	}
	return val + post;
}

function niceFloat(i, places) {
	places = places || 3;
	var val = Math.round(i*Math.pow(10,places)) / Math.pow(10,places);
	while (/(\d+)(\d{3})/.test(val.toString())){
		val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
	}
	return val;
}

function niceETA(eta) {
	var ret = Math.ceil(eta.seconds()) + "s";
	if(eta.minutes())
		ret = Math.ceil(eta.minutes()) + "m " + ret;
	if(eta.hours())
		ret = Math.ceil(eta.hours()) + "h " + ret;
	if(eta.days())
		ret = Math.ceil(eta.days()) + "days " + ret;
	if(eta.months())
		ret = Math.ceil(eta.months()) + "months " + ret;
	return ret;
}

function formatDateTime(date) {
	return moment(date).format('YYYY-MM-DD HH:mm:ss');
}

function formatTime(date) {
	return moment(date).format('HH:mm:ss');
}

function showMessage(text, color, timeout) {
	var message = $("<div></div>")
	.addClass("message")
	.addClass(color+"-over")
	.html(text)
	.append(
		$("<div></div>")
		.addClass("close")
		.attr("onClick", "$(this).parent().remove();")
		.text("X")
	);
	
	$("#messageBanner").append(message);
	
	if(timeout) {
		window.setTimeout(function() {
			message.remove();
		}, timeout);	
	}
}

//Register Ajax Error handler
$( document ).ajaxError(function(event, jqxhr, settings, exception) {
  	console.log(event);
  	console.log(jqxhr);
  	console.log(settings);
  	console.log(exception);
});

//Register Handlebars helpers
Handlebars.registerHelper('key_value', function (obj, hash) {
    var buffer, key, value;
    buffer = "";
    for (key in obj) {
        if (!Object.hasOwnProperty.call(obj, key)) {
            continue;
        }
        buffer += hash.fn({
            key: key,
            value: obj[key]
        }) || '';
    }
    return buffer;
});

//Register Handlebars helpers
Handlebars.registerHelper('key_value_object', function (obj, hash) {
    var buffer, key, value;
    buffer = "";
    for (key in obj) {
        if (!Object.hasOwnProperty.call(obj, key)) {
            continue;
        }
        buffer += hash.fn({
            key: key,
            value: JSON.stringify(obj[key])
        }) || '';
    }
    return buffer;
});

Handlebars.registerHelper('ifdef', function(conditional, options) {
	if(conditional || conditional === 0) {
		return options.fn(this);
	}
});

Handlebars.registerHelper('ifnotempty', function(conditional, options) {
	if(!isEmpty(conditional)) {
		return options.fn(this);
	}
});			