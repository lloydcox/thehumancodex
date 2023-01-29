<template>
  <div class="card">
    <div class="card-content">
      <form @submit.prevent="onSubmit">
        <thc-input label="Current password"
                   name="current_password"
                   v-model="data.current_password"
                   type="password"
                   :error="errors.current_password"/>
        <thc-input label="New password"
                   name="current_password"
                   v-model="data.new_password"
                   type="password"
                   :error="errors.new_password"/>
        <thc-input label="Repeat new password"
                   name="new_password"
                   v-model="data.new_password_confirmation"
                   type="password"
                   :error="errors.new_password_confirmation"/>
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
  import { _savePassword } from '../../api/settings'
  import messages from '../../mixins/messages'

  export default {
    name: 'SettingsPasswordForm',
    mixins: [
      messages
    ],
    data() {
      return {
        data: {},
        errors: {},
        loading: false
      }
    },
    methods: {
      async onSubmit() {
        this.loading = true
        try {
          this.errors = {}
          let response = await _savePassword(this.data)
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