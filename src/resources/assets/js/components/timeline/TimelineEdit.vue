<template>
  <div class="message-box dialog-mask" @click.self="closeEditBox" v-if="editedPost.id">
    <div class="main-container" style="margin-top: 95px; max-height: 550px; overflow-y: scroll;">
      <timeline-form :user="editedPost.user" :edited-post="editedPost" :post-categories="postCategories" @success="closeEditBox"></timeline-form>
    </div>
  </div>
</template>

<script>
  import { mapState, mapMutations } from 'vuex'

  export default {
    name: 'TimelineEdit',
    props: {
      postCategories: {
        type: Array,
        default: () => [],
      }
    },
    computed: mapState('timeline', {
      editedPost: state => state.editedPost
    }),
    methods: {
      ...mapMutations('timeline', [
        'closeEditBox'
      ])
    }
  }
</script>
