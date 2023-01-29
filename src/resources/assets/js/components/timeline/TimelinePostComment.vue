<template>
  <section class="media comment" v-bind:class="{'bg-comment-active p-1 mb-1':imposeComment}">
    <div class="media-left">
      <figure class="image is-40x40">
        <img :src="comment.user.avatar" alt="Avatar" class="is-rounded">
      </figure>
    </div>
    <div class="media-content">
      <a :href="`codex/${comment.user.username}`" class="title is-size-7 has-font-sans has-text-primary">{{ name }}</a>
      <div class="is-size-7 has-text-black">{{ comment.content }}</div>
      <p class="subtitle is-size-7 has-text-black spacer">
        <span class="icon is-left has-text-grey-light">
          <i class="fas fa-calendar-alt"></i>
        </span>
        {{ date }}
      </p>
    </div>
  </section>
</template>

<script>
  import moment from 'moment'

  export default {
    name: 'TimelinePostComment',
    props: {
      comment: {
        required: true,
        type: Object
      },
      imposeComment: false
    },
    computed: {
      date() {
        return moment(this.comment.date).format('DD / MM / YYYY')
      },
      name() {
        return `${this.comment.user.first_name} ${this.comment.user.last_name}`
      }
    }
  }
</script>

<style scoped>
  .comment.media {
    border: 0;
    margin-bottom: 0;
    margin-top: 0;
    padding-top: 0;
  }
</style>
