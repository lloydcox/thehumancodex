<template>
  <div class="card is-transparent">
    <div class="card-content">
      <div class="media">
        <div class="media-left">
          <figure class="image is-40x40">
            <img :src="request.user.avatar" alt="Avatar" class="is-rounded">
          </figure>
        </div>
        <div class="media-content">
          <h5 class="title is-size-7 has-font-sans">
            <a :href="request.user.url">{{ request.user.first_name }} {{ request.user.last_name }}</a> requested to connect.
          </h5>
          <p class="subtitle is-size-7 has-text-grey-light">
            {{ moment(request.created_at).format('MMMM DD') }}
          </p>
          <p>
            <button class="button is-small is-primary is-flat is-rounded is-mobile" @click="approve(request)">
              Approve
            </button>
            <button class="button is-small is-transparent is-flat is-rounded has-text-primary is-mobile" @click="decline(request)">
              Decline
            </button>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment'
  import messages from '../../mixins/messages'
  import {mapActions} from 'vuex'

  export default {
    name: 'NewRequests',
    mixins: [
      messages
    ],
    props: {
      request: {
        type: Object,
        required: true
      }
    },
    methods: {
      ...mapActions('requests', [
        'approveRequest',
        'declineRequest'
      ]),
      moment () {
        return moment()
      },
      async approve (request) {
        try {
          const response = await this.approveRequest(request)
          this.displaySuccessMessage(response.message || 'Request was approved!')
        } catch (error) {
          this.displayErrorMessage(error || 'We can not approve this request now')
        }
      },
      async decline (request) {
        try {
          const response = await this.declineRequest(request)
          this.displaySuccessMessage(response.message || 'Request was declined!')
        } catch (error) {
          this.displayErrorMessage(error || 'We can not decline this request now')
        }
      }
    }
  }
</script>

<style scoped>
  .subtitle {
    margin-bottom: 0.5rem;
  }
  p {
    margin-bottom: 0;
  }
</style>