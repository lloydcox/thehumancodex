<template>
  <div class="button is-small is-transparent is-flat has-text-primary" @click="action" v-if="!hidden">
    <i class="fa fa-user-plus icon"></i> Connect with {{ user.first_name }}
  </div>
</template>

<script>
  import messages from '../../mixins/messages'
  import {_sendInvitation} from '../../api/connections'

  import Confirm from '../dialogs/Confirm'
  import { create } from 'vue-modal-dialogs'

  const confirm = create(Confirm, 'title', 'content')

  export default {
    name: 'AddConnectionButton',
    mixins: [
      messages
    ],
    props: {
      user: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        hidden: false
      }
    },
    methods: {
      async action() {
        if (await confirm('Hey', `Are You sure You want to invite ${this.user.first_name}?`)) {
          try {
            const response = await _sendInvitation([this.user.email])
            this.displaySuccessMessage(response.message || 'Post was removed!')
            this.hidden = true
          } catch (error) {
            this.displayErrorMessage(error.message || 'We can not remove this post now!')
          }
        }
      }
    }
  }
</script>

<style scoped>

</style>