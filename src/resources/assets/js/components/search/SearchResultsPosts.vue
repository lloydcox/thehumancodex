<template>
  <div>
    <h5 class="is-size-7 has-text-grey-light">Moments</h5>
    <template v-if="results.length">
      <timeline-post :post="post" v-for="post in posts" :key="post.id"></timeline-post>
      <timeline-edit></timeline-edit>
    </template>
    <template v-else>
      <p class="is-size-6 has-text-grey-light">No moments found.</p>
    </template>
  </div>
</template>

<script>
  import TimelinePost from '../timeline/TimelinePost'
  import TimelineEdit from '../timeline/TimelineEdit'
  import { mapState, mapMutations } from 'vuex'
  export default {
    name: 'SearchResultsPosts',
    components: {TimelinePost, TimelineEdit},
    props: {
      results: {
        type: Array,
        required: true
      }
    },
    computed: mapState('timeline', {
      posts: state => state.posts
    }),
    async created () {
      try {
        this.setPosts(this.results)
      } catch (error) {
        console.error(error)
      }
    },
    methods: {
      ...mapMutations('timeline', [
        'setPosts'
      ])
    }
  }
</script>

<style scoped>

</style>