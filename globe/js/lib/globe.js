/**
 * dat.globe Javascript WebGL Globe Toolkit
 * https://github.com/dataarts/webgl-globe
 *
 * Copyright 2011 Data Arts Team, Google Creative Lab
 *
 * Licensed under the Apache License, Version 2.0 (the 'License');
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 */

 
THREE.SVGLoader = function ( manager ) {

	this.manager = ( manager !== undefined ) ? manager : THREE.DefaultLoadingManager;

};

THREE.SVGLoader.prototype = {

	constructor: THREE.MaterialLoader,

	load: function ( url, onLoad, onProgress, onError ) {

		var parser = new DOMParser();

		var loader = new THREE.XHRLoader();
		loader.setCrossOrigin( this.crossOrigin );
		loader.load( url, function ( svgString ) {

			var doc = parser.parseFromString( svgString, 'image/svg+xml' );  // application/xml

			onLoad( doc.firstChild );

		}, onProgress, onError );

	}
};

var DAT = DAT || {};

DAT.Globe = function(container, opts) {
  opts = opts || {};
  
  var colorFn = opts.colorFn || function (colorStyle) {
    var c = new THREE.Color();
    c.setStyle(colorStyle);
    return c;
  };
  var pie = Math.PI;
  
  var imgDir = opts.imgDir || '/globe/';

  var textureName = opts.texture || 'GlobeLaunchBlankAlternativeBig.svg';

  var textureColor = colorFn(opts.textureColor || null);

  var Shaders = {
    'earth' : {
      uniforms: {
        'texture': { type: 't', value: null },
        'percent': {type: 'f', value:0.5},
        'tr': {type: 'f', value: textureColor.r},
        'tg': {type: 'f', value: textureColor.g},
        'tb': {type: 'f', value: textureColor.b},
        'backLigtness': {type: 'f', value: opts.backTransparency || 0.3},
      },
      vertexShader: [
        'varying vec3 vNormal;',
        'varying vec2 vUv;',
        'void main() {',
          'gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );',
          'vNormal = normalize( normalMatrix * normal );',
          'vUv = uv;',
        '}'
      ].join('\n'),
      fragmentShader: [
        'uniform float percent;',
        'uniform float backLigtness;',
        'uniform float tr;',
        'uniform float tg;',
        'uniform float tb;',
        'uniform sampler2D texture;',
        'varying vec3 vNormal;',
        'varying vec2 vUv;',
        'void main() {',
          // 'vec3 diffuse = texture2D( texture, vUv ).xyz;',
          'vec3 diffuse = vec3( tr, tg, tb );',
          'float intensity = 1.05 - dot( vNormal, vec3( 0.0, 0.0, 1.0 ) );',
          'vec3 atmosphere = vec3( 1.0, 1.0, 1.0 ) * pow( intensity, 3.0 );',
          'if(gl_FrontFacing){',
          'gl_FragColor = vec4( diffuse + atmosphere, 1.0 );',
          '} else {',
          'gl_FragColor = vec4( diffuse , 1) * backLigtness;',
          '}',
          'vec4 tex2 = texture2D( texture, vUv );',
          'if(tex2.a - percent < 0.0) discard;',
        '}'
      ].join('\n')
    },
    'atmosphere' : {
      uniforms: {},
      vertexShader: [
        'varying vec3 vNormal;',
        'void main() {',
          'vNormal = normalize( normalMatrix * normal );',
          'gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );',
        '}'
      ].join('\n'),
      fragmentShader: [
        'varying vec3 vNormal;',
        'void main() {',
          'float intensity = pow( 0.8 - dot( vNormal, vec3( 0, 0, 1.0 ) ), 12.0 );',
          'gl_FragColor = vec4( 1.0, 1.0, 1.0, 1.0 ) * intensity;',
        '}'
      ].join('\n')
    }
  };

  var camera, scene, renderer, w, h;
  var mesh, atmosphere, point, _points = [];

  var overRenderer;

  var curZoomSpeed = 0;
  var zoomSpeed = opts.zoomSpeed || 50;
  var maxZoom = opts.maxZoom || 350;

  var mouse = { x: 0, y: 0 }, mouseOnDown = { x: 0, y: 0 };
  var rotation = { x: 0, y: 0 },
      target = { x: pie*3/2, y: pie / 6.0 },
      targetOnDown = { x: 0, y: 0 };

  var distance = 100000, distanceTarget = 100000;
  var padding = 40;
  var PI_HALF = pie / 2;

  function resetView()
  {
    if(render)
    {
      w = container.offsetWidth || window.innerWidth;
      h = container.offsetHeight || window.innerHeight;
      renderer.setSize(w,h);
      camera.aspect = w/h;
      countPins(true);
    }
  }

  window.onresize = resetView;

  function init() {
    container.style.color = '#fff';
    container.style.font = '13px/20px Arial, sans-serif';

    var shader, uniforms, material;
    w = container.offsetWidth || window.innerWidth;
    h = container.offsetHeight || window.innerHeight;

    camera = new THREE.PerspectiveCamera(30, w / h, 1, 10000);
    camera.position.z = distance;

    scene = new THREE.Scene();

    var geometry = new THREE.SphereGeometry(200, 40, 20);

    shader = Shaders['earth'];
    uniforms = THREE.UniformsUtils.clone(shader.uniforms);

    uniforms['texture'].value = THREE.ImageUtils.loadTexture(imgDir+textureName);

    material = new THREE.ShaderMaterial({

          uniforms: uniforms,
          vertexShader: shader.vertexShader,
          fragmentShader: shader.fragmentShader,
          
        });

    material.side = THREE.DoubleSide;

    mesh = new THREE.Mesh(geometry, material);
    mesh.rotation.y = pie;
    scene.add(mesh);

    geometry = new THREE.BoxGeometry(0.75, 0.75, 1);
    geometry.applyMatrix(new THREE.Matrix4().makeTranslation(0,0,-0.5));

    point = new THREE.Mesh(geometry);

    renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
    renderer.setSize(w, h);

    renderer.domElement.style.position = 'absolute';

    container.insertBefore(renderer.domElement, container.firstChild);

    container.addEventListener('mousedown', onMouseDown, false);

    container.addEventListener('mousewheel', onMouseWheel, false);

    document.addEventListener('keydown', onDocumentKeyDown, false);

    window.addEventListener('resize', onWindowResize, false);

    container.addEventListener('mouseover', function() {
        this.overRenderer = overRenderer = true;
    }, false);

    container.addEventListener('mouseout', function() {
        this.overRenderer = overRenderer = false;
    }, false);

    container.addEventListener('touchstart',  onTouchStart  , false);
    container.addEventListener('touchend',    onTouchEnd    , false);
    container.addEventListener('touchmove',   onTouchMove   , false);
    // container.addEventListener('touch',       onTouchMove   , false);
  }

  function addData(data, opts) {
    var lat, lng, size, color, i, step, colorFnWrapper;

    opts.animated = opts.animated || false;
    this.is_animated = opts.animated;
    opts.format = opts.format || 'magnitude'; // other option is 'legend'
    if (opts.format === 'magnitude') {
      step = 3;
      colorFnWrapper = function(data, i) { return colorFn(data[i+2]); }
    } else if (opts.format === 'legend') {
      step = 4;
      colorFnWrapper = function(data, i) { return colorFn(data[i+3]); }
    } else {
      throw('error: format not supported: '+opts.format);
    }

    if (opts.animated) {
      if (this._baseGeometry === undefined) {
        this._baseGeometry = new THREE.Geometry();
        for (i = 0; i < data.length; i += step) {
          lat = data[i];
          lng = data[i + 1];
//        size = data[i + 2];
          color = colorFnWrapper(data,i);
          size = 0;
          addPoint(lat, lng, size, color, this._baseGeometry);
        }
      }
      if(this._morphTargetId === undefined) {
        this._morphTargetId = 0;
      } else {
        this._morphTargetId += 1;
      }
      opts.name = opts.name || 'morphTarget'+this._morphTargetId;
    }
    var subgeo = new THREE.Geometry();
    for (i = 0; i < data.length; i += step) {
      lat = data[i];
      lng = data[i + 1];
      color = colorFnWrapper(data,i);
      size = data[i + 2];
      size = size*200;
      addPoint(lat, lng, size, color, subgeo);
    }
    if (opts.animated) {
      this._baseGeometry.morphTargets.push({'name': opts.name, vertices: subgeo.vertices});
    } else {
      this._baseGeometry = subgeo;
    }

  };

  function createPoints() {
      if (this._baseGeometry !== undefined) {
          var mat = new THREE.Material(
        //      [
        //      new THREE.MeshBasicMaterial({
        //          color: 0xffffff,
        //          vertexColors: THREE.FaceColors,
        //          morphTargets: false
        //      }),
        //      new THREE.MeshLambertMaterial({
        //          transparent: true,
        //          opacity: 0.0
        //      })
        //  ]
              );

          mat = new THREE.MeshBasicMaterial({
              color: 0xffffff,
              vertexColors: THREE.FaceColors,
              morphTargets: false,
              transparent: true,
              opacity:0
          });

      if (this.is_animated === false) {
        this.points = new THREE.Mesh(this._baseGeometry, mat);
      } else {
        if (this._baseGeometry.morphTargets.length < 8) {
          var padding = 8-this._baseGeometry.morphTargets.length;
          for(var i=0; i<=padding; i++) {
            this._baseGeometry.morphTargets.push({'name': 'morphPadding'+i, vertices: this._baseGeometry.vertices});
          }
        }
        this.points = new THREE.Mesh(this._baseGeometry, mat);
      }
      scene.add(this.points);
          return this.points;
    }
  }

  function addPoint(lat, lng, size, color, subgeo) {

    var phi = (90 - lat) * pie / 180;
    var theta = (180 - lng) * pie / 180;

    point.position.x = 200 * Math.sin(phi) * Math.cos(theta);
    point.position.y = 200 * Math.cos(phi);
    point.position.z = 200 * Math.sin(phi) * Math.sin(theta);

    point.lookAt(mesh.position);

    point.scale.z = 0;// Math.max( size, 0.1 ); // avoid non-invertible matrix
    point.updateMatrix();
    point.scale.x = 0;
    point.scale.y = 0;

    for (var i = 0; i < point.geometry.faces.length; i++) {

      point.geometry.faces[i].color = color;
      //point.geometry.faces[i].materialIndex = 1;
      point.transparent = true;
      point.opacity = 0;

    }
    if(point.matrixAutoUpdate){
      point.updateMatrix();
    }
    subgeo.merge(point.geometry, point.matrix);
  }

  function onMouseDown(event) {
    event.preventDefault();

    container.addEventListener('mousemove', onMouseMove, false);
    container.addEventListener('mouseup', onMouseUp, false);
    container.addEventListener('mouseout', onMouseOut, false);

    mouseOnDown.x = - event.clientX;
    mouseOnDown.y = event.clientY;

    targetOnDown.x = target.x;
    targetOnDown.y = target.y;

    container.style.cursor = 'move';
  }

  function onMouseMove(event) {
    mouse.x = - event.clientX;
    mouse.y = event.clientY;

    var zoomDamp = distance/1000;

    target.x = targetOnDown.x + (mouse.x - mouseOnDown.x) * 0.005 * zoomDamp;
    target.y = targetOnDown.y + (mouse.y - mouseOnDown.y) * 0.005 * zoomDamp;

    target.y = target.y > PI_HALF ? PI_HALF : target.y;
    target.y = target.y < - PI_HALF ? - PI_HALF : target.y;
  }

  function onMouseUp(event) {
    container.removeEventListener('mousemove', onMouseMove, false);
    container.removeEventListener('mouseup', onMouseUp, false);
    container.removeEventListener('mouseout', onMouseOut, false);
    container.style.cursor = 'auto';
  }

  function onMouseOut(event) {
    container.removeEventListener('mousemove', onMouseMove, false);
    container.removeEventListener('mouseup', onMouseUp, false);
    container.removeEventListener('mouseout', onMouseOut, false);
  }

  function onMouseWheel(event) {
    event.preventDefault();
    if (overRenderer) {
      zoom(event.wheelDeltaY * 0.3);
    }
    return false;
  }

  function onDocumentKeyDown(event) {
    switch (event.keyCode) {
      case 38:
        zoom(100);
        event.preventDefault();
        break;
      case 40:
        zoom(-100);
        event.preventDefault();
        break;
    }
  }

  function coordinates2radians(coordinates) {
    var longitude = (90 + coordinates.lon) * pie / 180
      , latitude = (180 - coordinates.lat) * pie / 180;
    return {
      x: camera.position.x > 0 ? longitude - pie: longitude,
      y: pie - latitude
    };
  }

  function center(coordinates) {
    var position = coordinates2radians(coordinates);
    position.x += Math.floor(target.x / pie) * pie;
    target = position;
  }

  var touchEvent, touchMouseEvent, touchZoomFirst, touchZoomSecond, touchesDelta;
  
  function getTouchesDelta(ev1, ev2)
  {
    var x = ev1.clientX - ev2.clientX, y = ev1.clientY - ev2.clientY;
    return Math.round( Math.sqrt( ( x * x ) + ( y * y ) ) ) * 2;
  }

  function touchZoom(event)
  {
    var ev1 = event.touches[0], ev2 =  event.touches[1]; 
    var delta = getTouchesDelta (ev1, ev2); 
    if(!touchZoomFirst && !touchZoomSecond)
    {
      touchZoomFirst = ev1;
      touchZoomSecond = ev2;
      touchesDelta = delta;
    }
    
    zoom( delta - touchesDelta );

    touchZoomFirst = ev1;
    touchZoomSecond = ev2;
    touchesDelta = delta;
  }

  function touchMove(event)
  {
    touchMouseEvent.initMouseEvent("click", true, true, window, 0, 0, 0, event.touches[0].clientX || event.touches[0].pageX, event.touches[0].clientY || event.touches[0].pageY, false, false, false, false, 0, null);
    onMouseMove(touchMouseEvent);
  }

  function touchEndMovement(event)
  {
    onMouseOut(touchMouseEvent);
    touchZoomFirst = touchZoomSecond = touchesDelta = touchMouseEvent = touchEvent = null;
  }

  function onTouchStart(event) {
    event.preventDefault();
    touchEvent = event;
    touchMouseEvent = document.createEvent("MouseEvents");
    touchMouseEvent.initMouseEvent("click", true, true, window, 0, 0, 0, event.touches[0].clientX || event.touches[0].pageX, event.touches[0].clientY || event.touches[0].pageY, false, false, false, false, 0, null);
    onMouseDown(touchMouseEvent);
  }

  function onTouchEnd(event) {
    event.preventDefault();
  }

  function onTouchMove(event) {
    event.preventDefault();
    if(event.targetTouches.length > 1)
    {
      if(touchEvent) touchEndMovement(event);
      touchZoom(event);
    }
    else
    {
      touchMove(event);
    }
  }

  function onWindowResize( event ) {
    camera.aspect = container.offsetWidth / container.offsetHeight;
    camera.updateProjectionMatrix();
    renderer.setSize( container.offsetWidth, container.offsetHeight );
  }

  function zoom(delta) {
    distanceTarget -= delta;
    distanceTarget = distanceTarget > 1000 ? 1000 : distanceTarget;
    distanceTarget = distanceTarget < maxZoom ? maxZoom : distanceTarget;
  }

  function animate() {
    requestAnimationFrame(animate);
    render();
  }
  function render() {
    zoom(curZoomSpeed);
    var posX = (target.x - rotation.x) * 0.1,
        posY = (target.y - rotation.y) * 0.1,
        posZ = (distanceTarget - distance) * 0.3;
    rotation.x += posX;
    rotation.y += posY;
    distance += posZ;

    camera.position.x = distance * Math.sin(rotation.x) * Math.cos(rotation.y);
    camera.position.y = distance * Math.sin(rotation.y);
    camera.position.z = distance * Math.cos(rotation.x) * Math.cos(rotation.y);

    camera.lookAt(mesh.position);

    renderer.render(scene, camera);
    if(countForElems)
    {
      countPins();
    }
  }

  var callback = function(){};

  var _self = this;

  var imgSvg  = new Image();
  imgSvg.onload = function(){
    init();
    _self.callback();
  };

  imgSvg.src = imgDir+textureName;

  this.animate = animate;


  this.__defineGetter__('time', function() {
    return this._time || 0;
  });

  this.__defineSetter__('time', function(t) {
    var validMorphs = [];
    var morphDict = this.points.morphTargetDictionary;
    for(var k in morphDict) {
      if(k.indexOf('morphPadding') < 0) {
        validMorphs.push(morphDict[k]);
      }
    }
    validMorphs.sort();
    var l = validMorphs.length-1;
    var scaledt = t*l+1;
    var index = Math.floor(scaledt);
    for (i=0;i<validMorphs.length;i++) {
      this.points.morphTargetInfluences[validMorphs[i]] = 0;
    }
    var lastIndex = index - 1;
    var leftover = scaledt - index;
    if (lastIndex >= 0) {
      this.points.morphTargetInfluences[lastIndex] = 1 - leftover;
    }
    this.points.morphTargetInfluences[index] = leftover;
    this._time = t;
  });

  var elems = [], intervalFps = 1000 / 75, constantFactor = Math.sqrt(3.5), countForElems = false, interval;

  
  function precisionRound(number, precision) {
    var factor = Math.pow(10, precision);
    return Math.round(number * factor) / factor;
  }


  function screenXY(obj) {
      var vector = obj.clone();
      var windowWidth = window.innerWidth;

      var widthHalf = (container.offsetWidth / 2);
      var heightHalf = (container.offsetHeight / 2);

      vector.project(camera);

      vector.x = precisionRound((vector.x * widthHalf ) + widthHalf,2);
      vector.y = precisionRound(- (vector.y * heightHalf ) + heightHalf, 2);
      vector.z = (Math.sqrt( Math.pow( obj.distanceTo(mesh.position) ,2)  + Math.pow( camera.position.distanceTo(mesh.position), 2 ) ) > obj.distanceTo(camera.position));

      return vector;
  }

  function addPin(pin, options)
  {
    options = options || {};
    var name = options.name || 'data' + elems.length,
      lat = pin.dataset.lat || (function(){if(pin.dataset.coordinates){return JSON.parse( pin.dataset.coordinates ).lat;}})() || options.lat || 0,
      lon = pin.dataset.lon || (function(){if(pin.dataset.coordinates){return JSON.parse( pin.dataset.coordinates ).lon;}})() || options.lon || 0;
    addData([parseFloat(lat), parseFloat(lon), 0, '#ffffff'], { 'format': 'legend', name: name });
    var item = {name:name,elem:pin, mesh:createPoints()};
    item.vector = item.mesh.geometry.vertices[0];
    var coords = screenXY(item.vector);
    item.points = {x:coords.x,y:coords.y};
        item.elem.style.transform = 'translate(' + item.points.x + 'px,' + item.points.y + 'px)';
    elems.push(item);
    countForElems = (elems.length || false);
  }

  function countPins(force)
  {
    for (var i = 0, item; item = elems[i]; i++) {
      var coords = screenXY(item.vector);
      if (force || (item.points.x != coords.x && item.points.y != coords.y)) {
        item.points.x = coords.x;
        item.points.y = coords.y;
        // item.elem.style.left = item.points.x + 'px';
        // item.elem.style.top = item.points.y + 'px';
        item.elem.style.transform = 'translate(' + item.points.x + 'px,' + item.points.y + 'px)';
        if(item.elem.inFront != coords.z)
        {
          item.elem.inFront = coords.z;
          if(coords.z)
          {
            item.elem.classList.add('in-front');
            item.elem.classList.remove('in-back');
          }
          else
          {
            item.elem.classList.add('in-back');
            item.elem.classList.remove('in-front');
          }
        }
      }else
      {
          return;
      }
    }
  }

  this.addData = addData;
  this.createPoints = createPoints;
  this.renderer = renderer;
  this.scene = scene;
  this.camera = camera;
  this.distanceTarget = distanceTarget;
  this.addPin = addPin;
  this.callback = callback; 
  this.center = center; 
  return this;

};