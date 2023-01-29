<template>
  <article class="card timeline-post">
    <div class="card-content">
      <!-- Media -->
      <section class="media">
        <div class="media-left">
          <figure class="image is-64x64">
            <a :href="routes.profile + post.user.username">
              <img :src="post.user.avatar" alt="Avatar" class="is-rounded">
            </a>
          </figure>
        </div>
        <div class="media-content">
          <h5 class="title is-size-6 has-font-sans">
            <a :href="routes.profile + post.user.username" class="has-text-black">
              {{ name }}
            </a>
          </h5>
          <p class="subtitle is-size-7 has-text-black">
            <span class="icon is-left has-text-grey-light is-text-icon">
              <i class="fas fa-map-marker-alt"></i>
            </span>
            {{ post.location }}
            <span class="icon is-left has-text-grey-light is-text-icon">
              <i class="fas fa-calendar-alt"></i>
            </span>
            {{ date }}
            <span class="icon is-left has-text-grey-light is-text-icon" v-if="post.post_category && post.post_category !== null">
              <i class="fas fa-bars"></i>
            </span>
            <span class="badge p-2 category-tag"  v-if="post.post_category && post.post_category !== null"
                  v-bind:style="{ background: post.post_category.color_code }">
               {{post.post_category.title}}
            </span>
          </p>
        </div>
        <thc-dropdown class="is-pulled-right" v-show="isThcDropDownShow()">
          <button v-show="isMarkAsReadShow()" type="button" class="button is-primary mark-as-btn is-rounded is-small is-flat" @click="markAsViewed(post)" style="">
            Mark as read
          </button>
          <button v-if="post.user_id === user.id" type="button" class="button is-primary is-rounded is-small is-flat" @click="openEditBox(post)">
            Edit moment
          </button>
          <button v-if="post.user_id === user.id" type="button" class="button is-danger is-rounded is-small is-flat" @click="removePost">
            Delete moment
          </button>
        </thc-dropdown>
      </section>
      <!-- End Media -->
      <section class="push has-text-left">
        <!-- Post Content -->
        <h3 class="title post-title is-size-5">{{ post.title }}</h3>
        <div class="post-content" style="white-space:pre-line">

          {{ post.content }}
        </div>

        <div class="embed-responsive embed-responsive-16by9 mb-4"  v-if="post.youtube_url">
          <iframe class="embed-responsive-item mt-2"
                  :src="post.youtube_url">
          </iframe>
        </div>

        <figure class="image has-cursor-pointer post-image-holder" v-if="post.image" v-viewer.static="viewerSettings">
          <img :src="post.image" alt="Image">
        </figure>
        <!-- End Post Content -->
        <p class="spacer is-size-7 has-text-weight-bold">
          <template v-if="kudos.length">
            <span class="has-cursor-pointer kudos-spacer" @click="toggleKudosList">
              {{ kudos.length }} kudos
            </span>
          </template>
          <template v-if="comments.length">
            <span class="has-cursor-pointer" @click="toggleCommentsList">
              {{ comments.length }} {{ comments.length > 1 ? 'comments' : 'comment' }}
            </span>
          </template>
        </p>
        <!-- Actions -->
        <form @submit.prevent="submitComment" v-if="displayActions">
          <div class="columns is-mobile is-variable is-1 is-multiline">
            <div class="column is-7-desktop is-12-touch">
              <div class="comment-input">
                <input type="text" class="input is-rounded" v-model="commentText" placeholder="Write a comment">
                <button class="button is-primary is-small is-rounded comment-submit is-flat"
                        :disabled="!commentText.length"
                        :class="{'is-loading': isSending}">
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
            </div>
            <div class="column is-6-touch">
              <button type="button" class="button is-flat has-text-dark" @click="addKudosAction">
                <span class="icon is-small has-text-primary">
                  <i class="far fa-thumbs-up"></i>
                </span>
                Kudos
              </button>
            </div>
            <div class="column is-6-touch">
              <button type="button" class="button is-flat has-text-dark" @click="addToCodexAction">
                <span class="icon is-small has-text-primary">
                  <i class="fas fa-plus-circle"></i>
                </span>
                Codex
              </button>
            </div>
          </div>
        </form>
        <!-- End Actions -->
        <hr class="spacer" v-if="showComments || showKudos"/>
        <template v-if="showComments">
          <p class="spacer is-size-7 has-text-weight-bold has-text-grey-light">Comments</p>
          <transition-group name="fade" mode="out-in">
            <timeline-post-comment :comment="comment" v-for="comment in comments" :key="comment.id" :impose-comment="imposeComment === comment.id"></timeline-post-comment>
          </transition-group>
        </template>
        <template v-if="showKudos">
          <p class="spacer is-size-7 has-text-weight-bold has-text-grey-light">Kudos</p>
          <transition-group name="fade" mode="out-in">
            <timeline-post-kudo :kudo="kudo" v-for="kudo in kudos" :key="kudo.id"></timeline-post-kudo>
          </transition-group>
        </template>
      </section>
    </div>
  </article>
</template>

<script>
  import moment from 'moment'
  import {mapActions, mapMutations, mapState} from 'vuex'
  import messages from '../../mixins/messages'
  import TimelinePostComment from './TimelinePostComment'
  import TimelinePostKudo from './TimelinePostKudo'
  import Confirm from '../dialogs/Confirm'
  import {routes} from '../../config'

  import {create} from 'vue-modal-dialogs'

  const confirm = create(Confirm, 'title', 'content')

  export default {
    name: 'TimelinePost',
    mixins: [
      messages
    ],
    components: {
      TimelinePostComment,
      TimelinePostKudo
    },
    props: {
      isComments: false,
      post: {
        type: Object,
        required: true
      },
      displayActions: {
        type: Boolean,
        required: false,
        default: true
      },
      activeComment:null
    },
    computed: mapState('user', {
      user: state => state.user,
      date() {
        return moment(this.post.date).format('DD / MM / YYYY')
      },
      name() {
        return `${this.post.user.first_name} ${this.post.user.last_name}`
      },
      comments() {
        return this.post.comments || []
      },
      kudos() {
        return this.post.kudos || []
      }
    }),
    data() {
      return {
        commentText: '',
        viewerSettings: {
          "inline": false,
          "button": true,
          "navbar": false,
          "title": false,
          "toolbar": false,
          "tooltip": false,
          "movable": true,
          "zoomable": true,
          "rotatable": false,
          "scalable": false,
          "transition": true,
          "fullscreen": true,
          "keyboard": true,
          "url": "data-source"
        },
        showComments: false,
        showKudos: false,
        isSending: false,
        routes: routes,
        imposeComment : null
      }
    },
    methods: {
      ...mapMutations('timeline', [
        'openEditBox'
      ]),
      ...mapActions('timeline', [
        'addComment',
        'addKudos',
        'addToCodex',
        'deletePost',
        'markAs'
      ]),
      isThcDropDownShow(){
        if(window.location.href.indexOf("profile") > -1 || window.location.href.indexOf("settings") > -1  || window.location.pathname === "/" || window.location.href.indexOf(this.user.username) > -1){
          return true;
        }
      },
      isMarkAsReadShow(){
        if(window.location.href.indexOf("profile") === -1 && window.location.href.indexOf("settings") === -1  && window.location.href.indexOf(this.post.user.username) === -1){
          return true;
        }
      },
      async markAsViewed(post){
        try {
          const response = await this.markAs(post)
          if(response.status==='success'){
            this.displaySuccessMessage(response.message || 'Post mark as viewed!');
          }
        } catch (error) {
          this.displayErrorMessage(error.message|| 'Post not mark as viewed!')
        }
      },
      async submitComment () {
        this.isSending = true
        try {
          const response = await this.addComment({
            postId: this.post.id,
            commentData: {
              comment: this.commentText
            }
          })
          this.commentText = ''
          if (!this.showComments) {
            this.toggleCommentsList()
          }
          if(window.location.href.indexOf("settings") > -1){
            window.location.reload();
          }
          this.displaySuccessMessage(response.message || 'Comment was added!')
        } catch (error) {
          this.displayErrorMessage(error.message || 'We can not add this comment!')
        }
        this.isSending = false
      },
      async addKudosAction () {
        try {
          const response = await this.addKudos(this.post.id)
          if(window.location.href.indexOf("settings") > -1){
            window.location.reload();
          }
          this.displaySuccessMessage(response.message || 'Kudos was added!')
        } catch (error) {
          this.displayErrorMessage(error.message || 'We can not add this Kudos!')
        }
      },
      async addToCodexAction () {
        try {
          const response = await this.addToCodex(this.post.id)
          this.displaySuccessMessage(response.message || 'Post was added to your codex!')
        } catch (error) {
          this.displayErrorMessage(error.message || 'We can not add this post to your codex!')
        }
      },
      async removePost () {
        if (await confirm('Hey', 'Are You sure You want to remove this moment?')) {
          try {
            const response = await this.deletePost(this.post.id)
            if(window.location.href.indexOf("settings") > -1){
              window.location.reload();
            }
            this.displaySuccessMessage(response.message || 'Post was removed!')
          } catch (error) {
            this.displayErrorMessage(error.message || 'We can not remove this post now!')
          }
        }
      },
      toggleKudosList () {
        this.showKudos = !this.showKudos
        this.showComments = false;
      },
      toggleCommentsList () {
        this.showComments = !this.showComments
        this.showKudos = false;
      }
    },
    mounted() {
      this.showComments= this.isComments;
      this.imposeComment = this.activeComment;
    },

  }

</script>

<style scoped>
  .kudos-spacer {
    display: inline-block;
    margin-right: 1rem;
  }
</style>
