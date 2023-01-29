<template>
  <div class="card">
    <div class="card-content">
      <form @submit.prevent="onSubmit">
        <thc-input label="Email" name="email" v-model="email" :error="errors.email"/>
        <button type="submit" class="button is-primary is-rounded is-flat is-wide"
                :class="{'is-loading' : loading}"
                :disabled="loading">
          Save
        </button>
      </form>
    </div>
  </div>
</template>

<script>
  import { _getProfile, _saveEmail } from '../../api/settings'
  import messages from '../../mixins/messages'

  export default {
    name: 'SettingsEmailForm',
    mixins: [
      messages
    ],
    data() {
      return {
        email: '',
        errors: {},
        loading: false
      }
    },
    async created() {
      let response = await _getProfile();
      this.email = response.data.email || ''
    },
    methods: {
      async onSubmit() {
        this.loading = true
        try {
          this.errors = {}
          let response = await _saveEmail(this.email)
          this.displaySuccessMessage(response.message)
          if (response.redirect) {
            window.location.replace(response.redirect)
          }
          this.loading = false
        } catch (error) {
          this.errors = error.data
          this.displayErrorMessage(error.message)
          this.loading = false
        }
      }
    }
  }
</script>