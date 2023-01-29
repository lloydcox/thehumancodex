<template>
  <form class="post-form spacer" @submit.prevent="submit">
    <div class="card">
      <div class="card-content">
        <div class="media">
          <div class="media-left">
            <figure class="image is-64x64">
              <img :src="avatarUrl" alt="Avatar" class="is-rounded">
            </figure>
          </div>
          <div class="media-content">
            <h5 class="title is-6">{{ fullName }}</h5>
            <p class="subtitle is-size-7 has-text-black">
              <span class="icon is-left has-text-grey-light is-text-icon">
                <i class="fas fa-map-marker-alt"></i>
              </span>
              {{ post.location }}
              <span class="icon is-left has-text-grey-light is-text-icon">
                <i class="fas fa-calendar-alt"></i>
              </span>
              {{ post.date }}
            </p>
          </div>
        </div>
        <div class="content">
          <input type="text" v-model="post.title" class="title-input" placeholder="Share the moment, write a title">
          <textarea-autosize
                  id="message"
              v-model="post.content"
              class="content-input"
              placeholder="Feel the moment. Share the moment. Remember the moment."
          ></textarea-autosize>

          <div class="embed-responsive embed-responsive-16by9 mb-4"  v-if="post.youtube_url !== ''">
            <iframe id="youtubeVideo"
                    class="embed-responsive-item mt-4"
                    :src="post.youtube_url">
            </iframe>
          </div>

          <select v-model="post.category" class="form-control">
            <option value="null">Please select a Post Category</option>
            <option v-for="option in options"
                    :value="option.id">{{ option.title }} </option>
          </select>
        </div>
        <figure class="image" v-if="image">
          <img :src="image" alt="Uploaded">
        </figure>
      </div>
      <div class="level is-mobile form-footer">
        <input type="file" class="is-hidden" accept="image/*;capture=camera" @change="onImageSelect" ref="cameraInput">
        <input type="file" class="is-hidden" accept="image/*" @change="onImageSelect" ref="imageInput">
        <div class="level-left">
          <span class="is-size-7">Add to moment</span>
        </div>
        <div class="level-right">
          <span class="icon has-cursor-pointer location-input" @click="openLocationInput">
            <i class="fas fa-map-marker-alt"></i>
          </span>
          <transition name="drop" mode="out-in">
            <div v-show="locationInputIsOpen" class="absolute-container location-input">
              <div class="card is-rounded">
                <p class="control has-icons-left">
                  <span class="icon is-small is-left">
                    <i class="fas fa-map-marker-alt"></i>
                  </span>
                  <vue-google-autocomplete
                          ref="address"
                          id="timelineFormSearch"
                          classname="input ghost-input"
                          placeholder="Type your location"
                          v-on:placechanged="searchLocation"
                          v-on:keyup="searchLocationOnChange"
                          v-model="post.location"
                          types="geocode"
                          :options="{fields: ['geometry','address_components']}"
                          >
                  </vue-google-autocomplete>
                </p>
              </div>
            </div>
          </transition>
          <span class="icon has-cursor-pointer" @click="openDateInput">
            <i class="fas fa-calendar-alt"></i>
            <flat-pickr
                class="is-invisible picker"
                ref="datePicker"
                :config="{maxDate: 'today'}"
                v-model="post.date"
            ></flat-pickr>
          </span>
          <span class="icon has-cursor-pointer youtube-input" @click="openYoutubeInput">
            <i class="fab fa-youtube"></i>
          </span>
          <transition name="drop" mode="out-in">
            <div v-show="youtubeInputIsOpen" class="absolute-container youtube-input">
              <div class="card is-rounded">
                <p class="control has-icons-left" style="margin-bottom:0px;">
                  <span class="icon is-small is-left">
                    <i class="fab fa-youtube"></i>
                  </span>
                  <input
                          type="text"
                          id="youtubeUrlChange"
                          class="input ghost-input pac-target-input"
                          placeholder="Paste the Youtube URL"/>
                </p>
                <span v-show="youtubeUrlIsInvalid" class="invalid-feedback" style="display: block">
                  <small class="ml-3">Youtube URL is invalid!</small>
                </span>
                <button type="button" class="btn btn-primary" v-on:click="YoutubeOnChange">Add</button>
              </div>
            </div>
          </transition>
          <span class="icon has-cursor-pointer" @click="openImageInput">
            <i class="fas fa-file-image"></i>
          </span>
          <span class="icon has-cursor-pointer" @click="openCameraInput">
            <i class="fas fa-camera"></i>
          </span>
        </div>
      </div>
    </div>
    <button
        @click="locationInputIsOpen = !post.location"
        type="submit"
        class="button is-rounded is-primary is-wide is-flat"
        :class="{'is-loading': isSending}"
        :disabled="!post.title"
        :title="!post.title ? 'You have to write moment title' : ''"
        v-if="!postIsEmpty">
      {{ useEditMode ? 'Update the moment' : 'Post a moment' }}
    </button>
  </form>
</template>

<script>
  import moment from 'moment'
  import messages from '../../mixins/messages'
  import { mapActions } from 'vuex'
  import VueGoogleAutocomplete from 'vue-google-autocomplete'


  export default {
    name: 'TimelineForm',
    mixins: [
      messages
    ],
    props: {
      user: {
        type: Object,
        required: true
      },
      postCategories: {
        type: Array,
        default: () => [],
      },
      editedPost: {
        type: Object,
        required: false
      }
    },
    components: { VueGoogleAutocomplete },
    computed: {
      image() {
        if (!this.post.image) {
          return ''
        }
        if (this.imageFormat) {
          return `${this.imageFormat},${this.post.image}`
        }
        return this.post.image
      },
      avatarUrl () {
        return this.user.avatar
      },
      fullName () {
        return `${this.user.first_name} ${this.user.last_name}`
      },
      postIsEmpty() {
        return this.post.title === '' && this.post.content === '' && this.post.image === ''
      },
      date() {
        return moment(this.post.date).format('DD / MM / YYYY')
      },
      useEditMode() {
        return this.editedPost !== undefined
      },
      options () {
        return this.postCategories
      },
    },
    watch: {
      editedPost (val) {
        if (val) {
          this.post = val
        }
      }
    },
    data() {
      return {
        post: {
          title: '',
          content: '',
          date: moment().format('YYYY-MM-DD'),
          location: '',
          image: '',
          youtube_url: '',
          category: null
        },
        selectedFile: null,
        imageFormat: null,
        isSending: false,
        locationInputIsOpen: false,
        youtubeInputIsOpen: false,
        youtubeUrlIsInvalid: false
      }
    },
    mounted() {
      this.post.location = this.user.location;
      if (this.editedPost) {
        this.post = {...this.editedPost};
        this.post.category =this.editedPost.valueOf().post_category_id;
      }

      this.$refs.address.focus();
    },
    beforeDestroy: function () {
      document.removeEventListener('click', this.onBodyClick)
      document.removeEventListener('click', this.onBodyClickYT)
    },
    methods: {
      ...mapActions('timeline', [
        'addPost',

        'updatePost',
        'getPosts'
      ]),
      searchLocation: function (addressData, placeResultData, id) {
        this.post.location=placeResultData.formatted_address;
      },
      searchLocationOnChange: function(){
        this.post.location=document.getElementById('timelineFormSearch').value;
      },
      YoutubeOnChange: function(){
        let inputYoutubeUrl = document.getElementById('youtubeUrlChange').value;
        if (inputYoutubeUrl !== undefined || inputYoutubeUrl !== '') {
          let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
          let match = inputYoutubeUrl.match(regExp);
          if (match && match[7].length === 11) {
            // Do anything for being valid
            // if need to change the url to embed url then use below line
            this.post.youtube_url = 'https://www.youtube.com/embed/' + match[7];
            this.youtubeUrlIsInvalid = false;
            this.youtubeInputIsOpen = false;
            //$('#youtubeVideo').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0');
          }
          else {
            // Do anything for not being valid
            this.youtubeInputIsOpen = true;
            this.post.youtube_url = '';
            this.youtubeUrlIsInvalid = true;
          }
        }
        //this.post.youtube_url=document.getElementById('youtubeUrlChange').value;
      },
      moment() {
        return moment()
      },
      openCameraInput() {
        this.$refs.cameraInput.click()
      },
      openImageInput() {
        this.$refs.imageInput.click()
      },
      openDateInput() {
        this.$refs.datePicker.fp.open()
      },
      openLocationInput() {
        this.locationInputIsOpen = !this.locationInputIsOpen;
        if (this.locationInputIsOpen) {
          document.body.addEventListener('click', this.onBodyClick)
        } else {
          document.body.removeEventListener('click', this.onBodyClick)
        }
      },
      openYoutubeInput() {
        this.youtubeInputIsOpen = !this.youtubeInputIsOpen;
        if (this.youtubeInputIsOpen) {
          document.body.addEventListener('click', this.onBodyClickYT)
        } else {
          document.body.removeEventListener('click', this.onBodyClickYT)
        }
      },
      onImageSelect(event) {
        this.selectedFile = event.target.files[0] || null;
        if (this.selectedFile) {
          this.codeImage()
            .then(this.setImage)
            .catch(this.displayErrorMessage)
        }
      },
      onSuccess(response) {
        this.isSending = false
        this.displaySuccessMessage(response.message || 'Post was added!');
        this.resetForm();
        location.reload();
        this.emitSuccessEvent()
      },
      onError(error) {
        this.isSending = false
        this.displayErrorMessage(error.message || 'We can not add this post!')
      },
      onBodyClick(event) {
        let t = event.target
        for (let i = 0; i < 5; i++) {
          if(t) {
            if (t.classList.contains('location-input')) return
            t = t.parentElement
          }
        }
        this.locationInputIsOpen = false
      },
      onBodyClickYT(event) {
        let t = event.target
        for (let i = 0; i < 5; i++) {
          if(t) {
            if (t.classList.contains('youtube-input')) return
            t = t.parentElement
          }
        }
        this.youtubeInputIsOpen = false;
      },
      setImage(result) {
        let data = result.split(',')
        this.post.image = data[1] || ''
        this.imageFormat = data[0] || ''
      },
      codeImage() {
        return new Promise((resolve, reject) => {
          const reader = new FileReader()
          reader.readAsDataURL(this.selectedFile)
          reader.onload = () => resolve(reader.result)
          reader.onerror = error => reject(error)
        })
      },
      async submit() {
        this.isSending = true
        try {
          let postData = this.post
          let response = {}
          if (this.useEditMode) {
            const postId = this.editedPost.id
            response = await this.updatePost({postId, postData})
          } else {
            response = await this.addPost(postData)
          }
          this.onSuccess(response)
        } catch (error) {
          this.onError(error)
        }
      },
      resetForm() {
        this.post.title = ''
        this.post.content = ''
        this.post.image = ''
        this.post.date = moment().format('YYYY-MM-DD');
        this.post.location = this.user.location
        this.post.category = null;
      },
      emitSuccessEvent() {
        this.$emit('success')
      }
    }
  }
</script>
