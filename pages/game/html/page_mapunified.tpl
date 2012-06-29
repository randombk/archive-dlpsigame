{{block name="title" prepend}}{{"Info"}}{{/block}}
{{block name="additionalIncluding" append}}
	<script src="http://threejs.org/build/three.min.js?v={{$VERSION}}"></script>
	<script src="http://threejs.org/examples/js/controls/TrackballControls.js?v={{$VERSION}}"></script>
	<script src="http://threejs.org/examples/js/Detector.js?v={{$VERSION}}"></script>
	<script src="http://threejs.org/examples/js/libs/stats.min.js?v={{$VERSION}}"></script>

	<script src="http://mrdoob.github.com/three.js/examples/fonts/helvetiker_regular.typeface.js"></script>
	<script src="resources/js/UniMap.js?v={{$VERSION}}"></script>
{{/block}}
{{block name="content"}}
<div id="threecontainer_graphical" style="position: absolute; top: 0px; bottom: 0; left: 0; right: 0; min-height: 500px;"></div>
<div id="threecontainer_bgInteract" style="position: absolute; top: 0px; bottom: 0; left: 0; right: 0; min-height: 500px; pointer-events: none;"></div>
{{/block}}
{{block name="winHandlers" append}}
	<script type="x-shader/x-vertex" id="vertexshader">
		uniform float amplitude;
		attribute float size;
		attribute vec3 customColor;

		varying vec3 vColor;

		void main() {
		vColor = customColor;

		vec4 mvPosition = modelViewMatrix * vec4( position, 1.0 );

		//gl_PointSize = size;
		gl_PointSize = size * ( 300.0 / length( mvPosition.xyz ) );

		gl_Position = projectionMatrix * mvPosition;
		}
	</script>

	<script type="x-shader/x-fragment" id="fragmentshader">
		uniform vec3 color;
		uniform sampler2D texture;

		varying vec3 vColor;

		void main() {

		gl_FragColor = vec4( color * vColor, 1.0 );
		gl_FragColor = gl_FragColor * texture2D( texture, gl_PointCoord );

		}
	</script>

	<script>
		var galaxyID = {{$galaxyID}};
		var sectorIndex = {{$sectorIndex}};
		//Override container size
		$('#gamePageContainer').addClass("absFill").css("width", "100%").css("height", "100%");

		(function($) {
			var uniMap = new UniMap($("#threecontainer_graphical"), $("#threecontainer_bgInteract"));
			uniMap.animate();
		})(jQuery);
	</script>
{{/block}}
