angular.module('globe.ui',['ngRoute']);
angular.module('globe.ui')
    .config(['$routeProvider',function($routeProvider) {
        $routeProvider
        .when('/category', {
            templateUrl: 'partials/category.html',
            controller: 'CategoryCtrl'
        })
        .when('/item/:id', {
            templateUrl: 'partials/item.html',
            controller: 'ItemCtrl'
        })
        .when('/globe', {
            templateUrl: 'partials/globe.html',
            controller: 'GlobeCtrl'
        })
        .otherwise('/globe');

    }]);
angular.module('globe.ui')
    .directive('avRadioList', function(){
    return {
        restrict: 'AEC',
        scope: {
            list: '=',
            selected: '=',
        },
        templateUrl: '/partials/directives/av-radio-list.html',
        controller:['$scope','$timeout', function($scope,$timeout){
            $scope.select = function(item) {
                
                $scope.selected = item;
            }
            
            $scope.currentPage = 0;
            
            $scope.pageSize = 10;
            
            $scope.pageLast = function(){
                return Math.floor( $scope.list.length / $scope.pageSize);
            };
            
            $scope.pagePrevIndex = function(){
                var ret = ($scope.currentPage - $scope.pageSize) / $scope.pageSize;
                if(ret < 0) ret = 0;
                return ret;
            };
            
            $scope.pageNextIndex = function(){
                var ret = $scope.currentPage + $scope.pageSize;
                if(ret > $scope.list.length) ret = $scope.currentPage;
                return ret / $scope.pageSize;
            };
            
            $scope.setPage = function(index){
                $scope.currentPage = $scope.pageSize * index;
            }
            
            $scope.paginationList = [];
            
            function setPagination() {
                $scope.paginationList = [];
                if($scope.list){
                    var size = Math.ceil( $scope.list.length / $scope.pageSize);
                    for(var i = 0; i < size; i++ ){
                        $scope.paginationList.push(i);
                    }
                }
            }
                
            $scope.$watch('list',setPagination);
            $scope.$watch('pageSize',setPagination);
            
            function setPageSize(){
                if($scope.list){
                    if(window.matchMedia("(min-width: 992px)").matches){
                        $scope.$apply(function(){
                            $scope.pageSize = 5;
                        });
                    }else{
                        $scope.$apply(function(){
                            $scope.pageSize = 10;
                        });
                    }
                }
            }
            $timeout(setPageSize);
            
            if(window.matchMedia){
                window.onresize = setPageSize;
            }
            
        }],
    }
});
angular.module('globe.ui')
    .controller('CategoryCtrl',['$scope','$http', function($scope, $http){
    $scope.itemList = [];
    $scope.selectedItem;
    $http({
        method: 'GET',
        url: '/items.json'
    }).then(
        function(res){
            $scope.itemList = res.data.filter(function(i){if(i.visible)return i;});
        },
        function(res) {
            console.log([res.status, res.statusText]);
        }
    );
}]);
angular.module('globe.ui')
    .controller('ItemCtrl',['$scope','$http', '$route', function($scope, $http,$route){
    $scope.selectedItem;
    $http({
        method: 'GET',
        url: '/items.json'
    }).then(
        function(res){
            var arr = res.data.filter(function(i){if(i.id == $route.current.params.id)return i;})
            if(arr.length) $scope.selectedItem = arr[0];
        },
        function(res) {
            console.log([res.status, res.statusText]);
        }
    );
}]);
angular.module('globe.ui')
    .controller('GlobeCtrl', ['$scope', '$http', '$route', function ($scope, $http, $route) {
        return
        var container = document.getElementById('globe');
        if (!Detector.webgl && !container) {
            Detector.addGetWebGLMessage();
        } else {
            var globe = new DAT.Globe(container,
                {
                    imgDir: '/img/',
                    colorFn: function (colorStyle) {
                        var c = new THREE.Color();
                        c.setStyle(colorStyle);
                        return c
                    }
                }
            );

            var points = [{ lat: 52.4004458, lon: 16.7615853, x: 0, y: 0 }, { lat: 45.8402941, lon: 15.8942922, x: 0, y: 0}];

            $scope.points = points;

            var meshes = [];

            for (var i = 0, item; item = points[i]; i++) {
                globe.addData([item.lat, item.lon, 0, '#ffffff'], { 'format': 'legend', name: 'data' + i });
                meshes.push( globe.createPoints());
            }

            globe.animate();

            function precisionRound(number, precision) {
                var factor = Math.pow(10, precision);
                return Math.round(number * factor) / factor;
              }

            var factor = Math.sqrt(3.5);

            function screenXY(obj, multiplier) {
                multiplier = multiplier || 1;
                var vector = obj.clone();
                var windowWidth = window.innerWidth;

                var widthHalf = (container.offsetWidth / 2);
                var heightHalf = (container.offsetHeight / 2);

                var offset = multiplier * factor;

                vector.project(globe.camera);

                vector.x = precisionRound((vector.x * widthHalf / offset) + widthHalf,2);
                vector.y = precisionRound(- (vector.y * heightHalf / offset) + heightHalf, 2);
                //vector.z = (vector.z * globe.distanceTarget ) - globe.distanceTarget;

                return vector;

            };

            var intervalFps = 1000 / 75;

            var planeVector = new THREE.Vector3(0, 1, 0);

            function mouseinteraction() {

                for (var i = 0, item; item = meshes[i]; i++) {
                    var coords = screenXY(item.geometry.vertices[0], item.geometry.boundingSphere.radius);
                    //console.log(coords.z);
                    if ($scope.points[i].x != coords.x && $scope.points[i].y != coords.y) {
                        $scope.points[i].x = coords.x;
                        $scope.points[i].y = coords.y;
                        $scope.$apply();
                    }else
                    {
                        return;
                    }
                }      
            }

            var currentInterval = setInterval(mouseinteraction, intervalFps);

        }
}]);