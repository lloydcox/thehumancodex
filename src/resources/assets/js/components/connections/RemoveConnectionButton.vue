<template>
  <div class="button is-small is-danger is-flat is-rounded" @click="action" v-if="!hidden">
    <i class="fa fa-user-minus icon"></i> Remove connection
  </div>
</template>

<script>
  import messages from '../../mixins/messages'
  import {_removeConnection} from '../../api/connections'

  import Confirm from '../dialogs/Confirm'
  import { create } from 'vue-modal-dialogs'

  const confirm = create(Confirm, 'title', 'content')

  export default {
    name: 'RemoveConnectionButton',
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
        if (await confirm('Hey', `Are You sure You want to remove connection with ${this.user.first_name}?`)) {
          try {
            const response = await _removeConnection(this.user.id)
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