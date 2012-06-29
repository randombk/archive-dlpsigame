{{block name="title" prepend}}{{"Info"}}{{/block}}
{{block name="additionalIncluding" append}}
	<script src="resources/js/vendor/three.min.js?v={{$VERSION}}"></script>
	<script src="http://threejs.org/examples/js/controls/TrackballControls.js?v={{$VERSION}}"></script>
	<script src="http://threejs.org/examples/js/Detector.js?v={{$VERSION}}"></script>
	<script src="http://threejs.org/examples/js/libs/stats.min.js?v={{$VERSION}}"></script>
{{/block}}
{{block name="content"}}
<table class="pageTable">
	<tr>
		<th>Sector {{$sectorIndex}} Map <a href="game.php?page=map&mode=uniMap">(Back to galaxy view)</a></th>
	</tr>
</table>
<div id="threecontainer" style="top: 17px; bottom: 0; left: 0; right: 0; min-height: 500px; position: absolute; background: rgba(10,10,20, 0.8);">
</div>
{{/block}}
{{block name="winHandlers" append}}
	<script>
		var galaxyID = {{$galaxyID}};
		var sectorIndex = {{$sectorIndex}};

		//Override container size
		$('#gamePageContainer').addClass("absFill").css("width", "100%").css("height", "100%");

		$(document).ready(function() {
			$.post("data.json.php", {"action" : "getInventory", "galaxyID": galaxyID, "sectorIndex": sectorIndex}, loadMap, "json")
			.fail(function() {  })
			.always(function() {  });
		});

		function loadMap(data) {
			if ( !Detector.webgl ) Detector.addGetWebGLMessage();
			var container, stats, projector;
			var camera, controls, scene, renderer;

			var curSelect, curCenter, stars = [], selectMesh;
			var mapStar = new Image();
			mapStar.onload = function() {
				init();
				animate();
				registerListeners();
			};
			mapStar.src = "resources/images/star.png";

			function animate() {
				requestAnimationFrame( animate );
				controls.update();
				render();
			}

			function render() {
				renderer.render( scene, camera );
				stats.update();
			}

			function registerListeners() {
				window.addEventListener( 'resize', onWindowResize, false );
				$("#threecontainer").find("canvas").dblclick(onDocumentDblClick);
				$("#threecontainer").find("canvas").click(onDocumentClick);
			}

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
					starMesh.rotation = camera.rotation;

					var overlayMesh = new THREE.Mesh( new THREE.PlaneGeometry(200, 100, 1, 1), overlayMaterial );
					overlayMesh.position = starMesh.position;
					overlayMesh.rotation = camera.rotation;

					var lineGeometry = new THREE.Geometry();
                    lineGeometry.vertices.push(starMesh.position);
                    if(i > 0) {
                        lineGeometry.vertices.push(stars[i-1].position);
                    } else {
                        lineGeometry.vertices.push(new THREE.Vector3(0, 0, 0));
					}

					var line = new THREE.Line(lineGeometry, lineMaterial);

					stars.push(starMesh);

					starHolder.add(starMesh);
					starHolder.add(overlayMesh);
					//starHolder.add(line);
				}

				selectMesh = new THREE.Mesh( new THREE.CircleGeometry(40), genSelectTexture());
				selectMesh.position.set(100000, 100000, 100000);
				selectMesh.rotation = camera.rotation;
				starHolder.add(selectMesh);

				//renderer
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

			function onWindowResize() {
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
				renderer.setSize( $("#threecontainer").width(), $("#threecontainer").height() );
				controls.handleResize();
				render();
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

					controls.target.set( intersects[ 0 ].object.position.x, intersects[ 0 ].object.position.y, intersects[ 0 ].object.position.z );
					//if(curSelect) curSelect.material.color.setHex( 0xaa3333 );
					//curCenter.material.color.setHex( 0x333333 );
				}
			}
		};
	</script>
{{/block}}
