/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

"use strict";
var instanceUniMap;
function Hexagon(q, r, data) {
	this.q = q;
	this.r = r;
	this.data = data;
}

Hexagon.prototype.toX = function (size) {
	return (size * Math.sqrt(3) * (this.q + this.r / 2));
};

Hexagon.prototype.toY = function (size) {
	return (size * 3 / 2 * this.r);
};

function UniMap(selectorGraphics, selectorOverlay) {
	instanceUniMap = this;
	//this.active = true;

	this.selectorGraphics = selectorGraphics;
	this.selectorOverlay = selectorOverlay;

	this.projector = new THREE.Projector();

	this.camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 20, 100000 );
	this.camera.position.z = 4000;

	//Controls
	this.controls = new THREE.TrackballControls( this.camera );
	this.controls.rotateSpeed = 1.0;
	this.controls.zoomSpeed = 1.2;
	this.controls.panSpeed = 0.8;

	this.controls.noZoom = false;
	this.controls.noPan = false;

	this.controls.minDistance = 400;
	this.controls.maxDistance = 6000;

	this.controls.dynamicDampingFactor = 0.5;
	this.controls.keys = [ 65, 83, 68 ];

	//Renderers
	this.rendererGraphics = new THREE.WebGLRenderer( {antialiasing: true} );
	this.rendererGraphics.setSize( this.selectorGraphics.width(), this.selectorGraphics.height() );
	this.rendererGraphics.sortElements = true;

	this.selectorGraphics.append( this.rendererGraphics.domElement );

	//Stats
	//this.stats = new Stats();
	//this.stats.domElement.style.position = 'absolute';
	//this.stats.domElement.style.top = '0px';
	//this.stats.domElement.style.zIndex = 100;
	//this.selectorGraphics.append( this.stats.domElement );
	window.addEventListener( 'resize', this.onWindowResize, false );

	this.loadView(null);

	this.selectorGraphics.find("canvas").dblclick(instanceUniMap.handleDblClick);
	this.selectorGraphics.find("canvas").click(instanceUniMap.handleClick);
	this.selectorGraphics.find("canvas").mousemove(instanceUniMap.handleMouseMove);
}

UniMap.prototype.loadView = function(view) {
	this.currentView = new UniMapViewGalaxy();
};

UniMap.prototype.onWindowResize = function() {
	instanceUniMap.camera.aspect = window.innerWidth / window.innerHeight;
	instanceUniMap.camera.updateProjectionMatrix();
	instanceUniMap.rendererGraphics.setSize( instanceUniMap.selectorGraphics.width(), instanceUniMap.selectorGraphics.height() );
	instanceUniMap.controls.handleResize();
};

UniMap.prototype.animateCamera = function() {
	if(this.cameraAnimationFrames > 0) {
		var numFrames = Math.min(this.cameraAnimationFrames, Math.ceil((this.cameraAnimationFrames - this.cameraAnimationSpeed) / 10));

		this.controls.target.x -= this.cameraAnimationTargetDelta[0] * numFrames;
		this.controls.target.y -= this.cameraAnimationTargetDelta[1] * numFrames;
		this.controls.target.z -= this.cameraAnimationTargetDelta[2] * numFrames;

		this.controls.object.position.x -= this.cameraAnimationPositionDelta[0] * numFrames;
		this.controls.object.position.y -= this.cameraAnimationPositionDelta[1] * numFrames;
		this.controls.object.position.z -= this.cameraAnimationPositionDelta[2] * numFrames;

		this.controls.object.up.x -= this.cameraAnimationUpDelta[0] * numFrames;
		this.controls.object.up.y -= this.cameraAnimationUpDelta[1] * numFrames;
		this.controls.object.up.z -= this.cameraAnimationUpDelta[2] * numFrames;

		this.cameraAnimationFrames -= numFrames;
	}
};

UniMap.prototype.enforceCameraLimits = function() {
	this.controls.target.x = Math.min(Math.max(this.controls.target.x , -1000), 1000);
	this.controls.target.y = Math.min(Math.max(this.controls.target.y , -1000), 1000);
	this.controls.target.z = Math.min(Math.max(this.controls.target.z , -1000), 1000);
};

UniMap.prototype.handleZoomLevels = function() {
	this.currentView.handleZoomLevels();
};

UniMap.prototype.animate = function() {
	requestAnimationFrame(instanceUniMap.animate);
	instanceUniMap.animateCamera();
	instanceUniMap.enforceCameraLimits();
	instanceUniMap.handleZoomLevels();
	instanceUniMap.render();
};

UniMap.prototype.render = function() {
	this.controls.update();
	this.rendererGraphics.render( this.currentView.scene, this.camera );
	//this.stats.update();
};

UniMap.prototype.panCameraTo = function(target, position, up, rotation, speed) {
	var cameraCurrentTarget = this.controls.target;
	var cameraCurrentPosition = this.controls.object.position;
	var cameraCurrentUp = this.controls.object.up;
	var numFrames = Math.pow(speed, 2);
	this.cameraAnimationSpeed = speed;
	this.cameraAnimationFrames = numFrames;

	this.cameraAnimationTargetDelta = [(cameraCurrentTarget.x - target.x)/numFrames, (cameraCurrentTarget.y - target.y)/numFrames, (cameraCurrentTarget.z - target.z)/numFrames];
	this.cameraAnimationPositionDelta = [(cameraCurrentPosition.x - position.x)/numFrames, (cameraCurrentPosition.y - position.y)/numFrames, (cameraCurrentPosition.z - position.z)/numFrames];
	this.cameraAnimationUpDelta = [(cameraCurrentUp.x - up.x)/numFrames, (cameraCurrentUp.y - up.y)/numFrames, (cameraCurrentUp.z - up.z)/numFrames];
};

UniMap.prototype.handleDblClick = function(e) {
	instanceUniMap.currentView.onDocumentDblClick(e);
};

UniMap.prototype.handleClick = function(e) {
	instanceUniMap.currentView.onDocumentClick(e);
};

UniMap.prototype.handleMouseMove = function(e) {
	instanceUniMap.currentView.onMouseMove(e);
};

function UniMapViewGalaxy() {
	this.scene =new THREE.Scene();
	instanceUniMap.camera.position.z = 2000;

	this.loadSkyBox();
	this.loadLights();
	this.loadHexGrid();
	this.loadGalaxy();

	this.loadOverlayInterface(0);

	this.targetOpacity = 1;
}

UniMapViewGalaxy.prototype.loadSkyBox = function() {
	var imagePrefix = "resources/images/map/skybox/skyboxGalaxy_";
	var directions  = ["xpos", "xneg", "ypos", "yneg", "zpos", "zneg"];
	var imageSuffix = ".jpg";
	var skyGeometry = new THREE.CubeGeometry( 30000, 30000, 30000 );
	var imageURLs = [];
	for (var i = 0; i < 6; i++)
		imageURLs.push( imagePrefix + directions[i] + imageSuffix );

	var textureCube = THREE.ImageUtils.loadTextureCube( imageURLs );
	var shader = THREE.ShaderLib[ "cube" ];
	shader.uniforms[ "tCube" ].value = textureCube;
	var skyMaterial = new THREE.ShaderMaterial( {
		fragmentShader: shader.fragmentShader,
		vertexShader: shader.vertexShader,
		uniforms: shader.uniforms,
		depthWrite: false,
		side: THREE.BackSide
	} );
	var skyBox = new THREE.Mesh( skyGeometry, skyMaterial );
	this.scene.add( skyBox );

};

UniMapViewGalaxy.prototype.loadLights = function() {
	var lights = [[0,0, 60], [0,0, -60]];

	for(var light in lights) {
		var pointLight = new THREE.PointLight(0xffffff);
		pointLight.position.set(light[0], light[1], light[2]);
		this.scene.add(pointLight);
	}
};

UniMapViewGalaxy.prototype.loadHexGrid = function() {
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

	var hexPoints = [
		new THREE.Vector3(50, 0, 0),
		new THREE.Vector3(25, 43.3, 0),
		new THREE.Vector3(-25, 43.3, 0),
		new THREE.Vector3(-50, 0, 0),
		new THREE.Vector3(-25, -43.3, 0),
		new THREE.Vector3(25, -43.3, 0)
	];

	var hexShape = new THREE.Shape( hexPoints );
	var hexGeometry = hexShape.makeGeometry();

	var lineMaterial = new THREE.LineBasicMaterial( { color: 0x003344, width: 10 } );
	this.hexLineMaterial = new THREE.LineBasicMaterial( { color: 0x003344, width: 10, transparent: true } );
	this.hexLabelMaterial = new THREE.MeshBasicMaterial({color : 0xFF7711, fog : false, transparent: true });

	this.hexagons = [];
	this.hexObjs = [];

	var textGeo = new THREE.Geometry();
	var id = 1;
	for (var N = 2; N <= 9; N++) {
		for (var dir = 2; dir <= 7; dir++) {
			var starthex = walkDirection(0, 0, dir - 2, N);
			for (var dis = 0; dis < N; dis++) {
				//Generate hexagon data
				var hexCoord = walkDirection(starthex[0], starthex[1], dir, dis);
				var hex = new Hexagon(hexCoord[0], hexCoord[1], {id: id});
				this.hexagons[id] = hex;

				//Draw hexagon outline
				var lineGeo = new THREE.Geometry();
				lineGeo.vertices.push(new THREE.Vector3( 50 + hex.toY(50), 0     + hex.toX(50), 45));
				lineGeo.vertices.push(new THREE.Vector3( 25 + hex.toY(50), 43.3  + hex.toX(50), 45));
				lineGeo.vertices.push(new THREE.Vector3(-25 + hex.toY(50), 43.3  + hex.toX(50), 45));
				lineGeo.vertices.push(new THREE.Vector3(-50 + hex.toY(50), 0     + hex.toX(50), 45));
				lineGeo.vertices.push(new THREE.Vector3(-25 + hex.toY(50), -43.3 + hex.toX(50), 45));
				lineGeo.vertices.push(new THREE.Vector3( 25 + hex.toY(50), -43.3 + hex.toX(50), 45));
				lineGeo.vertices.push(new THREE.Vector3( 50 + hex.toY(50), 0     + hex.toX(50), 45));
				this.scene.add(new THREE.Line(lineGeo, this.hexLineMaterial));

				//Draw hexagon label
				var hexLabelGeo = new THREE.TextGeometry(id, {size: 20, height: 2, font: 'helvetiker', curveSegments: 1});
				var hexLabel = new THREE.Mesh(hexLabelGeo);
				hexLabel.position.x = hex.toY(50) - 8 * id.toString().length;
				hexLabel.position.y = hex.toX(50) - 10;
				hexLabel.position.z = 44;
				THREE.GeometryUtils.merge(textGeo, hexLabel);

				//Draw invisible hex shape
				var hexFilled = new THREE.Mesh(hexGeometry, new THREE.MeshBasicMaterial({transparent: true, opacity: 0, color: 0x00BBFF, side: THREE.DoubleSide}));
				hexFilled.position.x = hex.toY(50);
				hexFilled.position.y = hex.toX(50);
				hexFilled.position.z = 45;
				hexFilled.id = id;
				this.scene.add(hexFilled);
				this.hexObjs.push(hexFilled);

				id++;
			}
		}
	}

	for( var r = 824; r <= 7000; r += 1000) {
		var resolution = 100;
		var amplitude = r;
		var size = 360 / resolution;

		var circleGeometry = new THREE.Geometry();
		for(var i = 0; i <= resolution; i++) {
			var circleSegment = ( i * size ) * Math.PI / 180;
			circleGeometry.vertices.push( new THREE.Vector3( Math.cos( circleSegment ) * amplitude, Math.sin( circleSegment ) * amplitude, 0) );
		}

		var line = new THREE.Line(circleGeometry, lineMaterial);
		line.position.z = 45;
		this.scene.add(line);
	}

	for( var l = 0; l < 12; l++) {
		var vector1 = new THREE.Vector3( 824, 0, 0 );
		var vector2 = new THREE.Vector3( 10000, 0, 0 );

		var axis = new THREE.Vector3( 0, 0, 1 );
		var angle = Math.PI / 6 * l;
		var matrix = new THREE.Matrix4().makeRotationAxis( axis, angle );

		vector1.applyMatrix4( matrix );
		vector2.applyMatrix4( matrix );

		var lineGeometry = new THREE.Geometry();
		lineGeometry.vertices.push(vector1);
		lineGeometry.vertices.push(vector2);

		var line = new THREE.Line( lineGeometry , lineMaterial);

		line.position.z = 45;
		this.scene.add(line);
	}

	this.scene.add(new THREE.Mesh(textGeo, this.hexLabelMaterial));
};

UniMapViewGalaxy.prototype.loadGalaxy = function() {
	var galaxyImage = new Image();
	var instance = this;
	galaxyImage.onload = function() {
		var x = document.createElement( "canvas" );
		var xc = x.getContext( "2d" );
		x.width = 1024;
		x.height = 1024;

		xc.drawImage(galaxyImage, 0, 0, 128, 128);

		var particles = new THREE.Geometry();
		var attributes = {
			size: {	type: 'f', value: [] },
			customColor: { type: 'c', value: [] }
		};

		var uniforms = {
			amplitude: { type: "f", value: 1.0 },
			color:     { type: "c", value: new THREE.Color( 0xffffff ) },
			texture:   { type: "t", value: THREE.ImageUtils.loadTexture( "resources/images/star.png" ) },
		};

		var shaderMaterial = new THREE.ShaderMaterial( {
			uniforms: 		uniforms,
			attributes:     attributes,
			vertexShader:   document.getElementById( 'vertexshader' ).textContent,
			fragmentShader: document.getElementById( 'fragmentshader' ).textContent,

			blending: 		THREE.AdditiveBlending,
			depthTest: 		false,
			transparent:	true
		});

		var imageData = xc.getImageData(0, 0, 128, 128);
		for(var x = 0; x < 128; x++) {
			for(var y = 0; y < 128; y++) {
				var red = imageData.data[((y - 1) * (128 * 4)) + ((x - 1) * 4)];
				for(var i = 0; i < red / 20; i++) {
					var posX = (8*x-512)*1.4 + Math.random() * 50 - 25;
					var posY = (8*y-512)*1.4 + Math.random() * 50 - 25;

					var varZ = Math.max(20, Math.min(90, 7000 / Math.max(1, (Math.abs(posX) + Math.abs(posY)))));
					var posZ = 45+(Math.random()-0.5) * varZ;

					var particle = new THREE.Vector3(posX, posY, posZ);
					particles.vertices.push(particle);
				}
			}
		}

		// create the particle system
		var particleSystem = new THREE.ParticleSystem(particles, shaderMaterial);
		particleSystem.dynamic = true;

		var vertices = particleSystem.geometry.vertices;
		var values_size = attributes.size.value;
		var values_color = attributes.customColor.value;

		for( var v = 0; v < vertices.length; v++ ) {
			values_size[ v ] = Math.min(70, Math.max(15, 4000 / Math.max(2, (Math.abs(vertices[ v ].x) + Math.abs(vertices[ v ].y)))));
			values_color[ v ] = new THREE.Color( 0xffaa00 ).setHSL( 0.5 + 0.1 * ( v / vertices.length ), 0.7, 0.5 );
		}
		// add it to the scene
		instance.scene.add(particleSystem);
	};
	galaxyImage.src = "resources/images/map/galaxy.png";
};

UniMapViewGalaxy.prototype.handleZoomLevels = function() {
	var distanceSquaredFromCenter = instanceUniMap.controls.object.position.lengthSq();
	this.targetOpacity = Math.max(0, Math.min(1, 1 - (distanceSquaredFromCenter / 300000) / 50));

	this.hexLineMaterial.opacity = this.targetOpacity;
	this.hexLabelMaterial.opacity = this.targetOpacity;
	if(this.lastHoverTarget) {
		this.lastHoverTarget.object.material.opacity = 0.7 * this.targetOpacity;
	}
	if(this.lastSelectTarget) {
		this.lastSelectTarget.object.material.opacity = this.targetOpacity;
	}
};

UniMapViewGalaxy.prototype.loadOverlayInterface = function(sectorID) {
	if(sectorID) {
		//Load sector info

	} else {
		//Load galaxy info

	}
};

UniMapViewGalaxy.prototype.onDocumentDblClick = function( e ) {
	e.preventDefault();
	var x = (e.offsetX || e.clientX - $(e.target).offset().left + window.pageXOffset );
	var y = (e.offsetY || e.clientY - $(e.target).offset().top + window.pageYOffset );

	var vector = new THREE.Vector3( ( x / instanceUniMap.selectorGraphics.width() ) * 2 - 1, - ( y / instanceUniMap.selectorGraphics.height()  ) * 2 + 1, 0.5 );
	instanceUniMap.projector.unprojectVector( vector, instanceUniMap.camera );

	var raycaster = new THREE.Raycaster( instanceUniMap.camera.position, vector.sub( instanceUniMap.camera.position ).normalize() );
	var intersects = raycaster.intersectObjects( this.hexObjs );

	if(intersects.length) {
		var camTarget = intersects[ 0 ].object.position;
		var camPosition = clone(intersects[ 0 ].object.position);

		camPosition.z += 750;
		instanceUniMap.panCameraTo(camTarget, camPosition, new THREE.Vector3(0,1,0), 0, 50 );
	} else {
		instanceUniMap.panCameraTo(new THREE.Vector3(0,0,0), new THREE.Vector3(0,0,2500), new THREE.Vector3(0,1,0), 0, 50 );
	}
};

UniMapViewGalaxy.prototype.onDocumentClick = function( e ) {
	e.preventDefault();
	var x = (e.offsetX || e.clientX - $(e.target).offset().left + window.pageXOffset );
	var y = (e.offsetY || e.clientY - $(e.target).offset().top + window.pageYOffset );

	var vector = new THREE.Vector3( ( x / instanceUniMap.selectorGraphics.width() ) * 2 - 1, - ( y / instanceUniMap.selectorGraphics.height()  ) * 2 + 1, 0.5 );
	instanceUniMap.projector.unprojectVector( vector, instanceUniMap.camera );

	var raycaster = new THREE.Raycaster( instanceUniMap.camera.position, vector.sub( instanceUniMap.camera.position ).normalize() );
	var intersects = raycaster.intersectObjects( this.hexObjs );

	if(intersects.length) {
		if(this.lastSelectTarget && this.lastSelectTarget !== intersects[0]) {
			this.lastSelectTarget.object.material.opacity = 0;
		}

		this.lastSelectTarget = intersects[0];
		this.lastSelectTarget.object.material.opacity = 1;

		this.loadOverlayInterface(this.lastSelectTarget.object.id);
	} else {
		if(this.lastSelectTarget)
			this.lastSelectTarget.object.material.opacity = 0;
		this.lastSelectTarget = null;
		this.loadOverlayInterface(0);
	}
};

UniMapViewGalaxy.prototype.onMouseMove = function( e ) {
	e.preventDefault();
	var x = (e.offsetX || e.clientX - $(e.target).offset().left + window.pageXOffset );
	var y = (e.offsetY || e.clientY - $(e.target).offset().top + window.pageYOffset );

	var vector = new THREE.Vector3( ( x / instanceUniMap.selectorGraphics.width() ) * 2 - 1, - ( y / instanceUniMap.selectorGraphics.height()  ) * 2 + 1, 0.5 );
	instanceUniMap.projector.unprojectVector( vector, instanceUniMap.camera );

	var raycaster = new THREE.Raycaster( instanceUniMap.camera.position, vector.sub( instanceUniMap.camera.position ).normalize() );
	var intersects = raycaster.intersectObjects( this.hexObjs );

	if(intersects.length && intersects[0] !== this.lastSelectTarget) {
		if(this.lastHoverTarget && this.lastHoverTarget !== intersects[0]) {
			this.lastHoverTarget.object.material.opacity = 0;
		}

		this.lastHoverTarget = intersects[0];
		this.lastHoverTarget.object.material.opacity = 0.7 * this.targetOpacity;
	} else {
		if(this.lastHoverTarget && this.lastHoverTarget !== this.lastSelectTarget)
			this.lastHoverTarget.object.material.opacity = 0;
		this.lastHoverTarget = null;
	}
};
