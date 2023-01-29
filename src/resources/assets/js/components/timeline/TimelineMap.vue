<template>
    <div class="timeline-map">
        <div class="google-map" :id="mapName"></div>
        <div style="position: absolute; float: left; left: 30px; top: 100px;" id="locationSearchButton">
          <span class="icon has-cursor-pointer location-input search-location" @click="openLocationSearch">
            <i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;Search Location
          </span>
            <transition name="drop" mode="out-in">
                <div v-show="searchLocationIsOpen" class="absolute-container location-input">
                    <div class="card is-rounded">
                        <p class="control has-icons-left">
                          <span class="icon is-small is-left">
                            <i class="fas fa-map-marker-alt"></i>
                          </span>
                            <vue-google-autocomplete
                                    ref="mapLocationSearch"
                                    id="map"
                                    classname="input ghost-input"
                                    placeholder="Type your location"
                                    v-on:placechanged="searchMapLocation"
                                    types="geocode"
                                    :options="{fields: ['geometry', 'address_component']}"
                            >
                            </vue-google-autocomplete>
                        </p>
                    </div>
                </div>
            </transition>
        </div>
        <section class="posts-container is-hidden-touch" id="postSection">
            <timeline-post :post="post" :display-actions="false" v-for="post in posts" :key="post.id"
                           class="has-no-push is-narrow">
            </timeline-post>
        </section>
        <timeline-edit></timeline-edit>
    </div>
</template>

<script>

    import TimelinePost from './TimelinePost'
    import TimelineEdit from './TimelineEdit'
    import {mapState, mapActions} from 'vuex'
    import _ from 'lodash'
    import VueGoogleAutocomplete from 'vue-google-autocomplete'

    export default {
        name: 'TimelineMap',
        components: {
            TimelinePost,
            TimelineEdit,
            VueGoogleAutocomplete
        },
        props: {
            userId: {
                type: String,
                required: true
            },

        },
        computed: mapState('timeline', {
            posts: state => state.posts,
            editedPost: state => state.editedPost,
        }),
        data() {
            return {
                mapName: "timeline-map",
                map: {},
                polygon: null,
                markers: [],
                defaultPosition: {
                    latitude: 0,
                    longitude: 0
                },
                displayPolygon: false,
                postCount: 0,
                user: null,
                currentLocation: '',
                executed: false,
                postData: [],
                searchLocationIsOpen: false,
                isMapFullScreen: false
            }
        },
        async mounted() {
            try {
                const userId = this.userId;
                const limit = 500;
                this.postData=await this.getPosts({userId, limit});
                const postSection = document.getElementById('postSection');
                postSection.style.display = 'none';
                if(!(window.location.href.includes("codex") || window.location.href.includes("profile"))) {
                    this.postData = await this.getAPIFeed('');
                }
                this.initMap()
            } catch (error) {
                console.error(error)
            }
            
        },
        methods: {
            ...mapActions('timeline', [
                'getPosts',
                'getPostsInArea',
                'getPostsInAreaForUser',
                'getAPIFeed'
            ]),
            openLocationSearch() {
                if (this.searchLocationIsOpen === false) {
                    this.$refs.mapLocationSearch.focus();
                    this.searchLocationIsOpen = true;
                } else {
                    this.searchLocationIsOpen = false;
                }
            },
            searchMapLocation: function (addressData, placeResultData, id) {
                const position = {
                    coords: {
                        latitude: addressData.latitude,
                        longitude: addressData.longitude
                    }
                }
                this.displayMapOnSearch(position);
            },
            initMap() {
                const post = this.posts[0]
                if (post) {
                    const position = {
                        coords: {
                            latitude: post.lat,
                            longitude: post.lng
                        }
                    }
                    this.displayMap(position)
                    return
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(this.displayMap, this.displayMap);
                } else {
                    this.displayMap()
                }
            },
            displayMapOnSearch({coords}) {
                const {latitude, longitude} = coords;
                this.map.set('zoom', 11);
                this.map.set('minZoom', 3);
                this.map.set('center', new google.maps.LatLng(latitude, longitude));
                this.map.addListener('bounds_changed', _.debounce(this.updatePostsList, 100));
                this.map.addListener('click', function () {
                    const postSection = document.getElementById('postSection');
                    if (postSection.style.display === 'inline-flex') {
                        postSection.style.display = 'none';
                    }
                });
            },
            displayMap({coords}) {
                const {latitude, longitude} = coords || this.defaultPosition
                const element = document.getElementById(this.mapName)
                const options = {
                    zoom: 0,
                    minZoom: 3,
                    center: new google.maps.LatLng(latitude, longitude)
                }
                this.map = new google.maps.Map(element, options);
                this.map.addListener('bounds_changed', _.debounce(this.updatePostsList, 100))
                this.map.addListener('click', function () {
                    const postSection = document.getElementById('postSection');
                    if (postSection.style.display === 'inline-flex') {
                        postSection.style.display = 'none';
                    }
                });
                this.setMapProperties();
            },
            setMapProperties(){
                const locationSearchButton=document.getElementById('locationSearchButton');
                locationSearchButton.style.index = 1;
                locationSearchButton.style.marginLeft = "10px";
                this.map.controls[google.maps.ControlPosition.LEFT_TOP].push(locationSearchButton);

                const postSection=document.getElementById('postSection');
                locationSearchButton.style.index = 1;
                this.map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(postSection);

                const autoSuggest=this.map.controls[google.maps.ControlPosition.LEFT_TOP];
                document.addEventListener("fullscreenchange", function() {
                    if(!this.isMapFullScreen){
                        const autocomplete = document.getElementsByClassName("pac-container").item(0);
                        autoSuggest.push(autocomplete);
                        this.isMapFullScreen=true;
                    }else {
                        this.isMapFullScreen=false;
                        document.location.reload();
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    }
                });
            },
            async updatePostsList() {
                const userId = this.userId
                const bonds = this.getMapBounds()
                const limit = 6
                if((window.location.href.includes("codex") || window.location.href.includes("profile"))) {
                    await this.getPostsInArea({userId, bonds, limit})
                }else {
                    await this.getPostsInAreaForUser({userId, bonds, limit})
                }

                this.displayMarkers();

                // For query tests
                this.drawRangePolygon()
            },
            getMapBounds() {
                const bounds = this.map.getBounds()
                return {
                    ne: {
                        lat: bounds.getNorthEast().lat(),
                        lng: bounds.getNorthEast().lng()
                    },
                    sw: {
                        lat: bounds.getSouthWest().lat(),
                        lng: bounds.getSouthWest().lng()
                    }
                }
            },
            /**
             * This is function to test query range/
             */
            drawRangePolygon() {
                if (!this.displayPolygon) {
                    return
                }

                let bounds = this.getMapBounds()
                var polygonCoords = [
                    {lat: bounds.ne.lat, lng: bounds.sw.lng},
                    {lat: bounds.ne.lat, lng: bounds.ne.lng},
                    {lat: bounds.sw.lat, lng: bounds.ne.lng},
                    {lat: bounds.sw.lat, lng: bounds.sw.lng},
                ]

                // Remove old polygon
                if (this.polygon) {
                    this.polygon.setMap(null)
                }

                // Construct the polygon.
                this.polygon = new google.maps.Polygon({
                    paths: polygonCoords,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35
                })
                this.polygon.setMap(this.map)
            },
            displayMarkers() {
                this.posts.forEach((post) => {
                    // If marked did exists on map
                    const found = this.markers.find((marker) => {
                        marker.label = this.getPostCount(marker.metadata.location).toString();
                        this.markers.splice(this.markers.indexOf(marker), 1, marker);
                        return post.id === marker.metadata.id
                    });
                    if (!found) {
                        // Add marker
                        let {lat, lng} = post;
                        let marker = new google.maps.Marker({
                            map: this.map,
                            position: {lat, lng},
                            label: this.getPostCount(post.location).toString(),
                            animation: google.maps.Animation.DROP,
                            icon: '/images/map-marker.svg',
                            title: post.title,
                            metadata: {
                                id: post.id,
                                location: post.location
                            }
                        });
                        marker.addListener('click', function () {
                            const postSection = document.getElementById('postSection');
                            if (postSection.style.display === 'none') {
                                postSection.style.display = 'inline-flex';
                            } else if (postSection.style.display === 'inline-flex') {
                                postSection.style.display = 'none';
                            }
                        });
                        this.markers.push(marker);
                    }
                })
            },
            getPostCount(currentLocation){
                let postCountForLocation=0;
                if(window.location.href.indexOf("codex") > -1 || window.location.href.indexOf("profile") > -1) {
                    this.postData.forEach((post)=>{
                        if (post.user_id == this.userId && post.location === currentLocation){
                            ++postCountForLocation;
                        }
                    });
                    return postCountForLocation;
                }else{
                    this.postData.data.forEach(function(postData) {
                        if(postData.location === currentLocation && postData.isOwn === false){
                            ++postCountForLocation;
                        }
                    });
                    return postCountForLocation;
                }
            },

        }
    }
</script>

<style scoped lang="scss">
    .google-map {
        position: absolute;
        width: 100%;
        height: 100%;
        margin-top: 2.5rem;
    }

    .timeline-map .posts-container {
        position: absolute;
        bottom: 0;
        left: 0;
        padding: 2rem;
        display: flex;

        .timeline-post {
            margin-right: 1rem;
            margin-bottom: 0;
            max-height: 475px;
            overflow-y: auto;
        }
    }

    .search-location{
        width: 170px;
        height: 2.3rem;
        text-align: center;
        display: table-cell;
        vertical-align: middle;
        color: rgb(86, 86, 86);
        font-family: Roboto, Arial, sans-serif;
        font-size: 14px;
        background-color: rgb(255, 255, 255);
        padding: 0px 17px;
        border-bottom-right-radius: 2px;
        border-top-right-radius: 2px;
        background-clip: padding-box;
        box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px;
        min-width: 66px;
        border-left: 0px;
    }

    @media screen and (min-width: 320px) and (max-width: 1024px){
        #locationSearchButton{
            width: 190px;
        }

        .search-location{
            width: 191px;
            height: 2.3rem;
            text-align: center;
            display: table-cell;
            vertical-align: middle;
            color: rgb(86, 86, 86);
            font-family: Roboto, Arial, sans-serif;
            font-size: 14px;
            background-color: rgb(255, 255, 255);
            padding: 0px 17px;
            border-bottom-right-radius: 2px;
            border-top-right-radius: 2px;
            background-clip: padding-box;
            box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px;
            min-width: 66px;
            border-left: 0px;
        }
    }

    @media screen and (min-width: 1024px) and (max-width: 2014px){
        #locationSearchButton{
            width: 190px;
        }
    }

    @media screen and (min-width: 2014px) and (max-width: 3287px){
        #locationSearchButton{
            width: 170px;
        }
    }
</style>
