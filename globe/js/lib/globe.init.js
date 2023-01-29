// ;(function(){
//     var container = document.getElementById('globe');
//     if (!Detector.webgl && !container) {
//         Detector.addGetWebGLMessage();
//     } else {
//         var globe = new DAT.Globe(container,
//             {
//                 imgDir: '/img/',
//                 colorFn: function (colorStyle) {
//                     var c = new THREE.Color();
//                     c.setStyle(colorStyle);
//                     return c
//                 }
//             }
//         );

//         var points = [{ lat: 52.4004458, lon: 16.7615853, x: 0, y: 0 }, { lat: 45.8402941, lon: 15.8942922, x: 0, y: 0}];

//         $scope.points = points;

//         var meshes = [];

//         for (var i = 0, item; item = points[i]; i++) {
//             globe.addData([item.lat, item.lon, 0, '#ffffff'], { 'format': 'legend', name: 'data' + i });
//             meshes.push( globe.createPoints());
//         }

//         globe.animate();

//         function precisionRound(number, precision) {
//             var factor = Math.pow(10, precision);
//             return Math.round(number * factor) / factor;
//           }

//         var factor = Math.sqrt(3.5);

//         function screenXY(obj, multiplier) {
//             multiplier = multiplier || 1;
//             var vector = obj.clone();
//             var windowWidth = window.innerWidth;

//             var widthHalf = (container.offsetWidth / 2);
//             var heightHalf = (container.offsetHeight / 2);

//             var offset = multiplier * factor;

//             vector.project(globe.camera);

//             vector.x = precisionRound((vector.x * widthHalf / offset) + widthHalf,2);
//             vector.y = precisionRound(- (vector.y * heightHalf / offset) + heightHalf, 2);
//             //vector.z = (vector.z * globe.distanceTarget ) - globe.distanceTarget;

//             return vector;

//         };

//         var intervalFps = 1000 / 75;

//         var planeVector = new THREE.Vector3(0, 1, 0);

//         function mouseinteraction() {

//             for (var i = 0, item; item = meshes[i]; i++) {
//                 var coords = screenXY(item.geometry.vertices[0], item.geometry.boundingSphere.radius);
//                 //console.log(coords.z);
//                 if ($scope.points[i].x != coords.x && $scope.points[i].y != coords.y) {
//                     $scope.points[i].x = coords.x;
//                     $scope.points[i].y = coords.y;
//                     $scope.$apply();
//                 }else
//                 {
//                     return;
//                 }
//             }      
//         }

//         var currentInterval = setInterval(mouseinteraction, intervalFps);

//     }

// })();