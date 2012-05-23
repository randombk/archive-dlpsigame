"use strict";
function clone(obj) {
	if(typeof obj !== "object") return obj;
	
	var newObj = {};
	jQuery.extend(newObj, obj);
	return newObj;
}

function objAdd(target, obj) {
	for(var i in obj) {
		target[i] = target[i] || 0;
		target[i] += obj[i];
	}
	if(jQuery.isEmptyObject(target)) target = null;
	return target;
}

function objSub(target, obj) {
	for(var i in obj) {
		target[i] = target[i] || 0;
		target[i] -= obj[i];
	}
	if(jQuery.isEmptyObject(target)) target = null;
	return target;
}

function mergeAdd(obj1, obj2) {
	return objAdd(clone(obj1), obj2);
}

function mergeSub(obj1, obj2) {
	return objSub(clone(obj1), obj2);
}

function mergeItemData(obj1, obj2, operation) {
	var result = obj1;
	for(var i in obj2) {
		if(isset(result[i])) {
			if(operation == "+") {
				result[i].quantity += obj2[i].quantity;
			} else {
				result[i].quantity -= obj2[i].quantity;	
			}
		} else {
			result[i] = clone(obj2[i]);
			if(operation == "-") {
				result[i].quantity = -result[i].quantity;	
			}
		}
	}
	return result;
}

function mergeItemDataClone(obj1, obj2, operation) {
	return mergeItemData(clone(obj1), clone(obj2), operation);
}
