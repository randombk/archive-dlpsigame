/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

"use strict";
function MapGalaxy(holderID) {
	this.holderID = holderID;
	this.width = $("#" + this.holderID).width();
	this.height = 500;
	this.contentWidth = 3000;
	this.contentHeight = 3000;
	this.container = document.getElementById(this.holderID);
	var instance = this;

	this.stage = new Kinetic.Stage({
		container: this.holderID,
		width: this.width,
		height: this.height
	});

	this.bgLayer = new Kinetic.Layer();
	this.loadImage("resources/images/map/galaxy_1s.png", 150, 150, 2700, 2700, this.bgLayer);
	this.stage.add(this.bgLayer);

	var hexLayer = new Kinetic.Layer();
	var tempLayer = new Kinetic.Layer();
	var hexGroup = new Kinetic.Group();
	var labelGroup = new Kinetic.Group({listening: false});

	this.hexImage = new Image();
	this.hexImage.onload = function () {
		function walkDirection(q, r, direction, distance) {
			var directions = [
				[1, 0],
				[0, 1],
				[-1, 1],
				[-1, 0],
				[0, -1],
				[1, -1],
				[1, 0],
				[0, 1],
			];
			return [q + distance * directions[direction][0], r + distance * directions[direction][1]];
		}

		var id = 1;
		var hexes = [];
		for (var N = 1; N <= 9; N++) {
			for (var dir = 2; dir <= 7; dir++) {
				var starthex = walkDirection(0, 0, dir - 2, N);
				for (var dis = 0; dis < N; dis++) {
					var hex = walkDirection(starthex[0], starthex[1], dir, dis);
					instance.drawHexagon(hex[0], hex[1], 60, id, hexGroup, instance);
					instance.labelHexagon(hex[0], hex[1], 60, id, tempLayer, instance);
					id++;
				}
			}
		}

		tempLayer.toImage({
			top: 0,
			left: 0,
			height: 3000,
			width: 3000,
			callback: function (img) {
				tempLayer.destroy();
				var image = new Kinetic.Image({
					image: img,
					x: 0,
					y: 0,
					draggable: false,
					listening: false
				});
				hexLayer.add(image);
			}
		});

		instance.scroller.zoomTo(0.3, true, 1500, 1500);
	};
	this.hexImage.src = 'resources/images/map/hex_off.png';

	hexLayer.add(hexGroup);
	this.stage.add(hexLayer);
	this.stage.add(tempLayer);

	this.scroller = this.initPanZoom();
	this.reflow();
};

MapGalaxy.prototype.loadImage = function (url, x, y, w, h, layer) {
	var newImage = new Image();
	var instance = this;
	newImage.src = url;
	newImage.onload = function () {
		var imgObj = new Kinetic.Image({
			x: x,
			y: y,
			image: newImage,
			width: w,
			height: h
		});
		layer.add(imgObj);
		instance.stage.draw();
	};
	return newImage;
};

MapGalaxy.prototype.resize = function (newW, newH) {
	this.stage.setSize(newW, newH);
	this.reflow();
};

MapGalaxy.prototype.reflow = function () {
	this.width = $("#" + this.holderID).width() - 10;
	this.height = 500;
	this.scroller.setDimensions(this.container.clientWidth, this.container.clientHeight, this.contentWidth, this.contentHeight);
	this.stage.draw();
};

MapGalaxy.prototype.initPanZoom = function () {
	var mousedown = false;
	var instance = this;

	function render(left, top, zoom) {
		instance.stage.setOffset(left / zoom, top / zoom);
		instance.stage.setScale(zoom);
		instance.stage.draw();
	};

	// Initialize Scroller
	var scroller = new Scroller(render, {
		zooming: true,
		animating: true,
		locking: false,
		minZoom: 0.25,
		maxZoom: 1
	});

	var rect = this.container.getBoundingClientRect();

	scroller.setPosition(rect.left + this.container.clientLeft, rect.top + this.container.clientTop);

	$(this.container).find("div")[0].addEventListener("mousedown", function (e) {
		if (e.target.tagName.match(/input|textarea|select/i)) {
			return;
		}

		scroller.doTouchStart([
			{
				pageX: e.pageX,
				pageY: e.pageY
			}
		], e.timeStamp);

		mousedown = true;
	}, false);

	document.addEventListener("mousemove", function (e) {
		if (!mousedown) {
			return;
		}

		scroller.doTouchMove([
			{
				pageX: e.pageX,
				pageY: e.pageY
			}
		], e.timeStamp);

		instance.stage.setListening(false);
		mousedown = true;
	}, false);

	document.addEventListener("mouseup", function (e) {
		if (!mousedown) {
			return;
		}
		instance.stage.setListening(true);
		scroller.doTouchEnd(e.timeStamp);
		mousedown = false;
	}, false);

	this.container.addEventListener(navigator.userAgent.indexOf("Firefox") > -1 ? "DOMMouseScroll" : "mousewheel", function (e) {
		instance.stage.setListening(true);
		scroller.doMouseZoom(e.detail ? (e.detail * -120) : e.wheelDelta, e.timeStamp, e.pageX, e.pageY);
	}, false);

	return scroller;
};

MapGalaxy.prototype.toX = function (q, r, size) {
	return this.contentWidth / 2 + (size * Math.sqrt(3) * (q + r / 2));
};

MapGalaxy.prototype.toY = function (q, r, size) {
	return this.contentHeight / 2 + (size * 3 / 2 * r);
};

MapGalaxy.prototype.drawHexagon = function (q, r, size, id, group, instance) {

	var hexagon = new Kinetic.Image({
		x: instance.toX(q, r, size),
		y: instance.toY(q, r, size),
		offsetX: 52,
		offsetY: 66,
		image: instance.hexImage,
		draggable: false
	});

	hexagon.on('click', function () {
		window.location = 'game.php?page=map&mode=sectorMap&galaxy=1&sector=' + id;
	});

	hexagon.on('mouseover', function () {
		this.setFilter(Kinetic.Filters.Brighten);
		this.setFilterBrightness(40);
		instance.stage.draw();
		document.body.style.cursor = 'pointer';
	});

	hexagon.on('mouseout', function () {
		this.clearFilter();
		instance.stage.draw();
		document.body.style.cursor = 'default';
	});


	group.add(hexagon);
};

MapGalaxy.prototype.labelHexagon = function (q, r, size, id, group, instance) {
	var label = new Kinetic.Text({
		x: instance.toX(q, r, size) - 9 * id.toString().length,
		y: instance.toY(q, r, size) - 24,
		text: id,
		fontSize: 45,
		fontFamily: 'Calibri',
		fill: 'red',
		align: 'right'
	});
	label.setAlign('center');
	group.add(label);
};

