function clone(obj) {
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
