<template>
  <div class="box-container is-narrow">
    <div class="box">
      <form @submit.prevent="submit">
        <p>
          <button type="button" class="button is-rounded is-wide" disabled>
            <img src="/images/google-logo.svg" alt="g" class="icon">
            Continue with Google
          </button>
        </p>
        <p>
          <button type="button" class="button is-rounded is-wide" disabled>
            <img src="/images/facebook-logo.svg" alt="fb" class="icon">
            Continue with Facebook
          </button>
        </p>
        <p class="is-size-7 has-text-weight-bold has-text-grey-light is-uppercase">
          OR LOG IN USING YOUR EMAIL
        </p>
        <thc-input
            v-model="email"
            name="email"
            type="email"
            :isRequired="true"
            label="Email"
        ></thc-input>
        <thc-input
            v-model="password"
            name="password"
            type="password"
            :isRequired="true"
            label="Password"
            :error="typeof errors.email !== 'undefined' ? errors.email : ''"
        ></thc-input>
        <p>
          <button type="submit" class="button is-rounded is-primary is-flat is-wide"
                  :class="{'is-loading': loading}" :disabled="!email || !password">
            Log in
          </button>
        </p>
        <p>
          <a href="passwords/email" class="has-text-weight-bold is-size-7">Forgot password?</a>
        </p>
        <div class="box-footer">
          <a href="register" class="has-text-weight-bold is-size-7">Not a member? Sign up!</a>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
  import { _login } from '../../api/auth'

  export default {
    name: 'LoginBox',
    data() {
      return {
        email: '',
        password: '',
        loading: false,
        errors: {}
      }
    },
    methods: {
      async submit() {
        this.loading = true
        this.errorMessage = ''
        try {
          const response = await _login({
            email: this.email,
            password: this.password
          })
          location.replace(response.redirect)
          this.loading = false
        } catch (error) {
          this.errors = error
          this.loading = false
          this.errorMessage = error
        }
      }
    }
  }
</script>
