<template>
  <div class="box-container is-narrow has-no-footer">
    <div class="box">
      <form :action="url" method="POST" @submit.prevent="submit">
        <thc-input
            v-model="email"
            name="email"
            type="email"
            :isRequired="true"
            label="Email"
            :error="error"
        ></thc-input>
        <p>
          <button type="submit" class="button is-rounded is-primary is-flat is-wide"
                  :class="{'is-loading': loading}" :disabled="!email">
            Next
          </button>
        </p>
      </form>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import {_passwordReset} from '../../api/auth'

  export default {
    name: 'ForgotPassword',
    props: {
      url: {
        required: true,
        type: String
      }
    },
    data() {
      return {
        email: '',
        loading: false,
        error: ''
      }
    },
    methods: {
      async submit() {
        this.loading = true
        this.errorMessage = ''
        try {
          const response = await _passwordReset({
            email: this.email
          })
          location.replace(response.redirect)
        } catch (error) {
          this.error = error.message
          this.loading = false
        }
      }
    }
  }
</script>
