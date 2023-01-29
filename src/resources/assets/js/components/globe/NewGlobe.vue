<template>
  <div>
    <div class="row m-0">
      <div
        class="col-lg-9 m-0 p-0"
        id="earth_div"
        ref="earth_div"
        style="height: 85.9vh"
      ></div>
      <div
        id="scrollable"
        class="col-lg-3 bg-white pt-5 pb-5"
        style="height: 85.9vh; overflow-y: scroll"
      >
        <div class="row">
          <div class="col-md-12 p-2">
            <div class="card bg-info">
              <div class="card-body">
                <vue-google-autocomplete
                  id="map"
                  classname="form-control"
                  placeholder="Search Location"
                  v-on:placechanged="searchMapLocation"
                  types="geocode"
                  :options="{fields: ['geometry', 'address_components']}">
                </vue-google-autocomplete>
              </div>
            </div>
          </div>

          <div v-if="connections">
            <div class="col-md-12 p-2">
              <div class="card bg-info">
                <div class="card-body">
                  <vue-autosuggest
                    v-model="query"
                    :suggestions="filteredOptions"
                    :input-props="{
                      id: 'autosuggest__input',
                      class: 'form-control',
                      placeholder: 'Select Connection',
                    }"
                    :get-suggestion-value="getSuggestionValue"
                    @selected="onSelected"
                  >
                    <template slot-scope="{ suggestion }">
                      <span class="my-suggestion-item"
                        >{{ suggestion.item.user.first_name }}
                        {{ suggestion.item.user.last_name }}</span
                      >
                    </template>
                  </vue-autosuggest>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" v-for="postArray in postsInternal">
          <div class="col-md-12 p-2">
            <p class="mb-0 text-primary">
              {{ postArray.location.location }}
              <sup
                ><span class="badge badge-primary">{{
                  postArray.location.total
                }}</span></sup
              >
            </p>
            <hr class="mt-1 mb-3 bg-primary" />
            <div class="card custom-card" v-for="post in postArray.posts">
              <div class="card-body">
                <div class="row no-gutters">
                  <div class="col-md-9">
                    <h5 class="truncate h5 mb-1">{{ post.title }}</h5>
                    <p class="mt-1 text-sm mb-0">
                      <span class="badge badge-sm badge-info p-1 mr-1"
                        ><i class="fas fa-calendar-alt mr-1"></i>
                        {{ post.date | formatDate }}</span
                      >
                      <span class="badge badge-sm badge-info p-1 mr-1"
                        ><i class="fas fa-user mr-1"></i>
                        {{
                          post.user.first_name + " " + post.user.last_name
                        }}</span
                      >
                      <span
                        class="badge badge-sm p-1 mr-1"
                        v-if="post.post_category && post.post_category !== null"
                        v-bind:style="{
                          background: post.post_category.color_code,
                        }"
                        ><i class="fas fa-archive mr-1"></i
                        >{{ post.post_category.title }}</span
                      >
                      <span class="badge badge-sm badge-secondary p-1 mr-1"
                        ><i class="fas fa-thumbs-up mr-1"></i>
                        {{ post.kudos.length }} Kudos</span
                      >
                      <span class="badge badge-sm badge-secondary p-1 mr-1"
                        ><i class="fas fa-comments mr-1"></i>
                        {{ post.comments.length }} Comments</span
                      >
                    </p>
                  </div>
                  <div class="col-md-3 text-center my-auto">
                    <div v-if="post.youtube_url">
                      <a
                        :href="
                          'http://www.youtube.com/watch?v=' +
                          post.youtube_video_id
                        "
                      >
                        <img
                          :src="
                            'https://img.youtube.com/vi/' +
                            post.youtube_video_id +
                            '/mqdefault.jpg'
                          "
                          class="img-fluid img-thumbnail"
                        />
                      </a>
                    </div>
                    <div v-if="!post.youtube_url">
                      <img
                        v-if="!post.image"
                        src="https://homestaymatch.com/images/no-image-available.png"
                        class="img-fluid img-thumbnail"
                      />
                      <img
                        v-if="post.image"
                        :src="post.image"
                        class="img-fluid img-thumbnail"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="row no-gutters">
                  <div class="col-md-12 mx-auto">
                    <a
                      class="btn btn-sm btn-primary text-sm"
                      :href="'/?view_notification_post=' + post.id"
                      >View</a
                    >
                    <button
                      class="btn btn-sm btn-primary text-sm"
                      @click="markAsViewed(post)"
                      style=""
                    >
                      Mark as read
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.truncate {
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.text-sm {
  font-size: smaller;
}
.custom-card {
  background-color: whitesmoke;
  border: #1c1c1c;
  border-radius: 5px;
  box-shadow: 0px 3px 4px #00000040;
}
.autosuggest__results {
  position: absolute;
  left: 20px;
  width: auto;
  background: #fff;
  margin: 2px 2px;
  padding: 2px 2px 0px 5px;
  border: 2px solid #0000001c;
  z-index: 1;
  border-radius: 2px;
  font-weight: 600;
}
.autosuggest__results-item {
  padding: 0px 0px;
  border-bottom: 1px solid #0003;
  margin: 5px 5px;
}
.autosuggest__results-item:hover {
  background-color: rgba(178, 204, 236, 0.2);
}
#autosuggest {
  width: 100%;
  display: block;
}
.autosuggest__results-item--highlighted {
  background-color: rgba(178, 204, 236, 0.2);
}
</style>

<script>
import { _getConnectionPosts } from "../../api/timeline";
import { mapState, mapActions } from "vuex";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import messages from "../../mixins/messages";
import { VueAutosuggest } from "vue-autosuggest";

export default {
  name: "NewGlobe",
  mixins: [messages],
  components: {
    VueGoogleAutocomplete,
    VueAutosuggest,
  },
  props: {
    posts: {
      type: Array,
      required: true,
    },
    connections: {
      type: Array,
      required: false,
    },
  },

  data() {
    return {
      globeObject: null,
      markers: [],
      postsInternal: [],
      suggestions: [
        {
          data: this.connections,
        },
      ],
      query: "",
      selected: "",
      mutablePosts: this.posts,
    };
  },
  computed: {
    filteredOptions() {
      return [
        {
          data: this.suggestions[0].data.filter((option) => {
            let fullName =
              option.user.first_name.toLowerCase() +
              " " +
              option.user.last_name.toLowerCase();
            return fullName.indexOf(this.query.toLowerCase()) > -1;
          }),
        },
      ];
    },
  },
  watch: {
    // "globeObject.getBounds": {
    //   handler(val) {
    //     console.log(val);
    //   },
    //   deep: true,
    // },
  },
  methods: {
    ...mapActions("timeline", ["markAs"]),
    initiateGlobe() {
      this.globeObject = null;
      let tileLayer = null;
      this.globeObject = new WE.map("earth_div", {
        sky: true,
        atmosphere: true,
      });
      tileLayer = WE.tileLayer(
        "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      );
      tileLayer.addTo(this.globeObject);
      this.setMarkers();
      let getGlobe = this.globeObject;
      this.showAlert(getGlobe.getBounds());
    },
    setMarkers() {
      // console.log(this.mutablePosts);

      let arrayLength = this.mutablePosts.length;
      let initialView = [55.3781, 3.436];
      this.markers = [];
      if (arrayLength !== 0) {
        for (let i = 0; i < arrayLength; i++) {
          if (i === 0) {
            initialView = [
              this.mutablePosts[i].location.lat,
              this.mutablePosts[i].location.lng,
            ];
          }
          this.markers[i] = WE.marker(
            [
              this.mutablePosts[i].location.lat,
              this.mutablePosts[i].location.lng,
            ],
            "map_icons/number_" + this.mutablePosts[i].location.total + ".png"
          ).addTo(this.globeObject);
          let selectedLocationMarker = this.mutablePosts[i].location;
          let getGlobe = this.globeObject;
          const self = this;
          this.markers[i].on("click", function () {
            document.getElementById("map").value =
              selectedLocationMarker.location;
            document.getElementById("scrollable").scrollTop = 0;
            getGlobe.setView(
              [selectedLocationMarker.lat, selectedLocationMarker.lng],
              6
            );
            let currentBounds = getGlobe.getBounds();
            self.setPostsBasedOnLocation(currentBounds);
          });
        }
      }
      this.globeObject.setView(initialView, 2.5);
    },
    searchMapLocation: function (addressData, placeResultData, id) {
      this.globeObject.setView(
        [addressData.latitude, addressData.longitude],
        9
      );
      let currentBounds = this.globeObject.getBounds();
      this.setPostsBasedOnLocation(currentBounds);
    },
    setPostsBasedOnLocation(bounds) {
      this.postsInternal = [];
      let arrayLength = this.mutablePosts.length;
      for (let i = 0; i < arrayLength; i++) {
        if (
          this.mutablePosts[i].location.lat >= bounds[0] &&
          this.mutablePosts[i].location.lat <= bounds[1] &&
          this.mutablePosts[i].location.lng >= bounds[2] &&
          this.mutablePosts[i].location.lng <= bounds[3]
        ) {
          this.postsInternal.push(this.mutablePosts[i]);
        }
      }
    },
    async markAsViewed(post) {
      try {
        const response = await this.markAs(post);
        if (response.status === "success") {
          this.displaySuccessMessage(
            response.message || "Post mark as viewed!"
          );
          location.reload();
        }
      } catch (error) {
        this.displayErrorMessage(error.message || "Post not mark as viewed!");
      }
    },
    async getConnectionPosts(selectedConnectionId) {
      try {
        const response = await _getConnectionPosts(selectedConnectionId);
        if (response.status === "success") {
          this.mutablePosts = response.data;
          this.postsInternal = this.mutablePosts;
          this.generateYoutubeId();
          this.removeAllMarkers();
          this.setMarkers();
        }
      } catch (error) {
        console.log(error);
      }
    },
    getSuggestionValue(suggestion) {
      return (
        suggestion.item.user.first_name + " " + suggestion.item.user.last_name
      );
    },
    onSelected(item) {
      if (item !== null) {
        this.selected = item.item;
        this.getConnectionPosts(this.selected.user.id);
      } else {
        this.removeAllMarkers();
        this.mutablePosts = this.posts;
        this.postsInternal = this.mutablePosts;
        this.generateYoutubeId();
        this.setMarkers();
      }
    },
    removeAllMarkers() {
      this.markers.forEach((marker) => {
        marker.removeFrom(this.globeObject);
      });
    },
    generateYoutubeId() {
      this.mutablePosts.forEach((postArray) => {
        postArray.posts.forEach((post) => {
          if (post.youtube_url) {
            let youtubeURL = post.youtube_url.split("/embed/");
            post.youtube_video_id = youtubeURL[1];
          }
        });
      });
    },
    showAlert(val) {
      this.globeObject.on("wheel", function () {
        console.log(val);
      });
    },
  },
  mounted() {
    this.generateYoutubeId();
    this.postsInternal = this.mutablePosts;
    this.initiateGlobe();
  },
};
</script>
