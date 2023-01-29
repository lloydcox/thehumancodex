<template>
  <div class="new-requests spacer is-large has-text-left" v-if="requests.length">
    <h5 class="is-size-7 has-text-grey-light">New requests: {{ requests.length }}</h5>
    <transition-group name="fade" mode="out-in">
      <new-requests-item :request="request" v-for="(request, key) in requests" :key="requests.key"></new-requests-item>
    </transition-group>
  </div>
</template>

<script>
  import NewRequestsItem from './NewRequestsItem'
  import {mapState, mapActions} from 'vuex'

  export default {
    name: 'NewRequests',
    components: {
      'new-requests-item': NewRequestsItem
    },
    props: {
      userId: {
        type: String,
        required: true
      }
    },
    computed: mapState('requests', {
      requests: state => state.requests
    }),
    created () {
      this.getRequests(this.userId)
    },
    methods: {
      ...mapActions('requests', [
        'getRequests',
        'approveRequest',
        'declineRequest'
      ])
    }
  }
</script>

<style scoped>
  p {
    margin-bottom: 0;
  }
</style>