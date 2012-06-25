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
};

Hexagon.prototype.toX = function (size) {
	return (size * Math.sqrt(3) * (this.q + this.r / 2));
};

Hexagon.prototype.toY = function (size) {
	return (size * 3 / 2 * this.r);
};

function UniMap(selector) {
	instanceUniMap = this;
	this.active = true;
	this.selector = selector;
	this.projector = new THREE.Projector();

	this.camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 20, 10000 );
	this.camera.position.z = 4000;

	//Controls
	this.controls = new THREE.TrackballControls( this.camera );
	this.controls.rotateSpeed = 1.0;
	this.controls.zoomSpeed = 1.2;
	this.controls.panSpeed = 0.8;

	this.controls.noZoom = false;
	this.controls.noPan = false;

	this.controls.minDistance = 400;
	this.controls.maxDistance = 8000;

	this.controls.dynamicDampingFactor = 0.5;
	this.controls.keys = [ 65, 83, 68 ];

	//Renderer
	this.renderer = new THREE.WebGLRenderer( {antialiasing: true} );
	this.renderer.setSize( this.selector.width(), this.selector.height() );
	this.renderer.sortElements = true;

	this.selector.append( this.renderer.domElement );

	//Stats
	this.stats = new Stats();
	this.stats.domElement.style.position = 'absolute';
	this.stats.domElement.style.top = '0px';
	this.stats.domElement.style.zIndex = 100;
	this.selector.append( this.stats.domElement );
	window.addEventListener( 'resize', this.onWindowResize, false );

	this.loadView(null);

	this.selector.find("canvas").dblclick(instanceUniMap.handleDblClick);
	this.selector.find("canvas").mousedown(instanceUniMap.handleClick);

	$(window).focus(function(){
		instanceUniMap.active = true;
		instanceUniMap.animate();
	}).blur(function() {
		instanceUniMap.active = false;
	});
}

UniMap.prototype.loadView = function(view) {
	this.currentView = new UniMapViewGalaxy();
	this.camera.position.z = 2000;

};

UniMap.prototype.onWindowResize = function() {
	instanceUniMap.camera.aspect = window.innerWidth / window.innerHeight;
	instanceUniMap.camera.updateProjectionMatrix();
	instanceUniMap.renderer.setSize( instanceUniMap.selector.width(), instanceUniMap.selector.height() );
	instanceUniMap.controls.handleResize();
};

UniMap.prototype.animate = function() {
	if(instanceUniMap.active)
		requestAnimationFrame(instanceUniMap.animate);
	instanceUniMap.render();
};

UniMap.prototype.render = function() {
	this.controls.update();
	this.renderer.render( this.currentView.scene, this.camera );
	this.stats.update();
};

function UniMapViewGalaxy() {
	this.scene =new THREE.Scene();

	this.loadLights();
	this.loadHexGrid();
	this.loadGalaxy();
}

UniMapViewGalaxy.prototype.loadLights = function() {
	var lights = [[1000,1000,1000], [-1000,-1000,-1000]];

	for(var light in lights) {
		var pointLight = new THREE.PointLight(0xFFFFFF);
		pointLight.position.set(light[0], light[1], light[2]);
		this.scene.add(pointLight);
	}
};

UniMapViewGalaxy.prototype.loadHexGrid = function() {
	var shape = new THREE.Shape();
	shape.moveTo( -46,0 );
	shape.lineTo( -23,40 );
	shape.lineTo( 23,40 );
	shape.lineTo( 46,0 );
	shape.lineTo( 23,-40 );
	shape.lineTo( -23,-40 );
	shape.lineTo( -46,0 );

	var geometry = new THREE.ExtrudeGeometry( shape, { amount: 100,  bevelSegments: 1, steps: 1 , bevelSize: 3, bevelThickness: 2 } );

	this.hexagons = [];
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

	this.hexagons = [];
	this.hexObjects = [];

	var textGeo = new THREE.Geometry();
	var stdHexMaterial = new THREE.MeshPhongMaterial({ambient: 0x2288FF, specular: 0x2288FF, shininess: 10, transparent: true, opacity: 0.1});

	var id = 1;
	for (var N = 1; N <= 9; N++) {
		for (var dir = 2; dir <= 7; dir++) {
			var starthex = walkDirection(0, 0, dir - 2, N);
			for (var dis = 0; dis < N; dis++) {
				var hexCoord = walkDirection(starthex[0], starthex[1], dir, dis);
				var hex = new Hexagon(hexCoord[0], hexCoord[1], {id: id});
				this.hexagons.push(hex);

				var hexObj = new THREE.Mesh(geometry, stdHexMaterial);
				hexObj.position.x = hex.toY(50);
				hexObj.position.y = hex.toX(50);
				this.hexObjects.push(hexObj);
				this.scene.add(hexObj);

				var hexLabelGeo = new THREE.TextGeometry(id, {size: 20, height: 1, font: 'helvetiker', curveSegments: 1 });

				var hexLabel = new THREE.Mesh(hexLabelGeo);
				hexLabel.position.x = hex.toY(50) - 8 * id.toString().length;
				hexLabel.position.y = hex.toX(50)-10;
				hexLabel.position.z = 100;
				THREE.GeometryUtils.merge(textGeo, hexLabel);

				id++;
			}
		}
	}

	this.scene.add( new THREE.Mesh(textGeo, new THREE.MeshBasicMaterial({color : 0xFF7711, fog : false, shading: THREE.NoShading})));
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

		function generateSprite() {
			var canvas = document.createElement( 'canvas' );
			canvas.width = 128;
			canvas.height = 128;

			var context = canvas.getContext( '2d' );

			context.beginPath();
			context.arc( 64, 64, 60, 0, Math.PI * 2, false) ;

			context.lineWidth = 0.5; //0.05
			context.stroke();
			context.restore();

			var gradient = context.createRadialGradient( canvas.width / 2, canvas.height / 2, 0, canvas.width / 2, canvas.height / 2, canvas.width / 2 );

			gradient.addColorStop( 0, 'rgba(255,255,255,1)' );
			gradient.addColorStop( 0.2, 'rgba(255,255,255,1)' );
			gradient.addColorStop( 0.4, 'rgba(200,200,200,1)' );
			gradient.addColorStop( 1, 'rgba(0,0,0,1)' );

			context.fillStyle = gradient;

			context.fill();
			return canvas;
		}

		var sprite = generateSprite() ;
		var particles = new THREE.Geometry(), pMaterial = new THREE.Texture( sprite );
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

/*
 function loadMap(data) {

 var curSelect, curCenter, selectMesh;
 var stars = [], overlays = [], labels = [];
 var mapStar = new Image();
 mapStar.onload = function() {
 init();
 animate();
 registerListeners();
 };
 mapStar.src = "resources/images/star.png";

 function init() {
 // world
 scene = new THREE.Scene();
 projector = new THREE.Projector();

 camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 20, 10000 );
 camera.position.z = 4000;

 //Controls
 controls = new THREE.TrackballControls( camera );
 controls.rotateSpeed = 1.0;
 controls.zoomSpeed = 1.2;
 controls.panSpeed = 0.8;

 controls.noZoom = false;
 controls.noPan = false;

 controls.minDistance = 400;
 controls.maxDistance = 8000;

 controls.dynamicDampingFactor = 0.5;
 controls.keys = [ 65, 83, 68 ];

 //Texture
 mapStar.premultiplyAlpha = true;
 mapStar.needsUpdate = true;

 //Stars
 var starHolder = new THREE.Object3D;
 starHolder.sortElements = true;
 scene.add(starHolder);

 for(var i = 0; i < data.stars.length; i++){
 var starMaterial = genStarTexture(mapStar);
 starMaterial.blendEquation = THREE.AddEquation;

 var overlayMaterial = genOverlayTexture(data.stars[i]);
 overlayMaterial.blendEquation = THREE.AddEquation;

 var lineMaterial = new THREE.LineBasicMaterial({color: 0x0000ff, linewidth: 100 });

 var starMesh = new THREE.Mesh( new THREE.CircleGeometry(40), starMaterial );
 starMesh.position.set(data.stars[i].x, data.stars[i].y, data.stars[i].z);
 starMesh.quaternion = camera.quaternion;
 stars.push(starMesh);

 var overlayMesh = new THREE.Mesh( new THREE.PlaneGeometry(200, 100, 1, 1), overlayMaterial );
 overlayMesh.position = starMesh.position;
 overlayMesh.quaternion = camera.quaternion;
 overlays.push(overlayMesh);


 var lineGeometry = new THREE.Geometry();
 lineGeometry.vertices.push(starMesh.position);
 if(i > 0) {
 lineGeometry.vertices.push(stars[i-1].position);
 } else {
 lineGeometry.vertices.push(new THREE.Vector3(0, 0, 0));
 }

 var line = new THREE.Line(lineGeometry, lineMaterial);

 starHolder.add(starMesh);
 starHolder.add(overlayMesh);
 //starHolder.add(line);
 }

 selectMesh = new THREE.Mesh( new THREE.CircleGeometry(40), genSelectTexture());
 selectMesh.position.set(100000, 100000, 100000);
 selectMesh.quaternion = camera.quaternion;
 starHolder.add(selectMesh);

 // renderer
 renderer = new THREE.WebGLRenderer( {antialiasing: true} );
 renderer.setSize( $("#threecontainer").width(), $("#threecontainer").height() );
 renderer.sortElements = true;

 container = $("#threecontainer");
 container.append( renderer.domElement );

 //Stats
 stats = new Stats();
 stats.domElement.style.position = 'absolute';
 stats.domElement.style.top = '0px';
 stats.domElement.style.zIndex = 100;
 container.append( stats.domElement );
 }

 function get_random_color() {
 var letters = '0123456789ABCDEF'.split('');
 var color = '#';
 for (var i = 0; i < 6; i++ ) {
 color += letters[Math.round(Math.random() * 15)];
 }
 return color;
 }

 function genStarTexture(starTexture) {
 var x = document.createElement( "canvas" );
 var xc = x.getContext( "2d" );
 x.width = 256;
 x.height = 256;

 xc.drawImage(starTexture, 0, 0, 256, 256);

 xc.globalAlpha = 0.3;
 xc.globalCompositeOperation = "source-atop";
 xc.fillStyle = get_random_color();
 xc.fillRect( 0, 0, 256, 256);

 var map = new THREE.Texture( x );
 map.needsUpdate = true;

 var material = new THREE.MeshBasicMaterial( { map: map, transparent: true} );
 return material;
 }

 function genOverlayTexture(starData) {
 var x = document.createElement( "canvas" );
 var tintCanvas = document.createElement( "canvas" );

 var c = x.getContext( "2d" );
 var tintContect = tintCanvas.getContext( "2d" );
 x.width = 1024;
 x.height = 512;

 if(starData.playerType == "none") {
 c.fillStyle = "silver";
 c.strokeStyle = "silver";
 } else if(starData.playerType == "neutral") {
 c.fillStyle = "aqua";
 c.strokeStyle = "aqua";
 } else if(starData.playerType == "self") {
 c.fillStyle = "lime";
 c.strokeStyle = "lime";
 } else if(starData.playerType == "ally") {
 c.fillStyle = "green";
 c.strokeStyle = "green";
 } else if(starData.playerType == "enemy") {
 c.fillStyle = "red";
 c.strokeStyle = "red";
 }

 c.lineWidth = 7;

 c.beginPath();
 c.moveTo(0, 100);
 c.lineTo(1024, 100);
 c.stroke();
 c.beginPath();
 c.moveTo(482, 100);
 c.lineTo(512, 150);
 c.lineTo(542, 100);
 c.closePath();
 c.fill();

 c.font = "90px arial bold";
 var ownerName = "(Unowned)";
 if(starData.ownerName != "") {
 ownerName = starData.ownerName;
 }

 var starText = "(" + galaxyID + "." + sectorIndex + "." + starData.index + ") " + ownerName;
 c.fillText(starText, 512 - starText.length / 2 * 45, 80 );

 var starName = "Star System";
 c.fillText(starName, 512 - starName.length / 2 * 45, 450 );

 var map = new THREE.Texture(x);
 map.needsUpdate = true;

 var material = new THREE.MeshBasicMaterial( { map: map, transparent: true} );
 return material;
 }

 function genSelectTexture() {
 var x = document.createElement( "canvas" );
 var tintCanvas = document.createElement( "canvas" );

 var c = x.getContext( "2d" );
 var tintContect = tintCanvas.getContext( "2d" );
 x.width = 512;
 x.height = 512;

 c.fillStyle = "yellow";
 c.beginPath();
 c.moveTo(128,256);
 c.lineTo(20,206);
 c.lineTo(30,256);
 c.lineTo(20,306);
 c.lineTo(128,256);
 c.fill();

 c.beginPath();
 c.moveTo(384,256);
 c.lineTo(492,206);
 c.lineTo(482,256);
 c.lineTo(492,306);
 c.lineTo(384,256);
 c.fill();

 var map = new THREE.Texture(x);
 map.needsUpdate = true;

 var material = new THREE.MeshBasicMaterial( { map: map, transparent: true} );
 return material;
 }

 function onDocumentDblClick( e ) {
 e.preventDefault();
 var x = (e.offsetX || e.clientX - $(e.target).offset().left + window.pageXOffset );
 var y = (e.offsetY || e.clientY - $(e.target).offset().top + window.pageYOffset );
 var vector = new THREE.Vector3( ( x / $("#threecontainer").width() ) * 2 - 1, - ( y / $("#threecontainer").height()  ) * 2 + 1, 0.5 );
 projector.unprojectVector( vector, camera );

 var raycaster = new THREE.Raycaster( camera.position, vector.sub( camera.position ).normalize() );
 var intersects = raycaster.intersectObjects( stars );
 if(intersects.length) {
 if(selectMesh)
 selectMesh.position = intersects[0].object.position;

 controls.target.set( intersects[ 0 ].object.position.x, intersects[ 0 ].object.position.y, intersects[ 0 ].object.position.z );
 //if(curSelect) curSelect.material.color.setHex( 0xaa3333 );
 //curCenter.material.color.setHex( 0x333333 );
 }
 }

 function onDocumentClick( e ) {
 e.preventDefault();
 var x = (e.offsetX || e.clientX - $(e.target).offset().left + window.pageXOffset );
 var y = (e.offsetY || e.clientY - $(e.target).offset().top + window.pageYOffset );
 var vector = new THREE.Vector3( ( x / $("#threecontainer").width() ) * 2 - 1, - ( y / $("#threecontainer").height()  ) * 2 + 1, 0.5 );
 projector.unprojectVector( vector, camera );

 var raycaster = new THREE.Raycaster( camera.position, vector.sub( camera.position ).normalize() );
 var intersects = raycaster.intersectObjects( stars );
 if(intersects.length) {
 if(selectMesh)
 selectMesh.position = intersects[0].object.position;

 //controls.target.set( intersects[ 0 ].object.position.x, intersects[ 0 ].object.position.y, intersects[ 0 ].object.position.z );
 //if(curSelect) curSelect.material.color.setHex( 0xaa3333 );
 //curCenter.material.color.setHex( 0x333333 );
 }
 }
 }
 */

