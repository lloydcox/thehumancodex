<template>
  <div>
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
      Or register by using your email
    </p>
    <thc-input
        v-model="email"
        name="email"
        type="email"
        :isRequired="true"
        :error="typeof errors.email !== 'undefined' ? errors.email[0] : ''"
        label="Email"
    ></thc-input>
    <p>
      <button type="button" class="button is-rounded is-primary is-flat is-wide"
              @click="next()"
              :class="{'is-loading': loading}" :disabled="!email">
        Next
      </button>
    </p>
    <div class="box-footer">
      <a href="login" class="has-text-weight-bold is-size-7">Already a member? Log in!</a>
    </div>
  </div>
</template>

<script>
  import registerSteps from '../../../mixins/registerSteps'
  import { _registerValidator } from '../../../api/auth'

  export default {
    name: 'RegisterBoxEmail',
    mixins: [registerSteps],
    computed: {
      email: {
        get() {
          return this.$store.state.register.data.email
        },
        set(value) {
          this.$store.commit('register/setEmail', value)
        }
      },
      data() {
        return {
          email: this.email,
        }
      }
    },
    methods: {
      async validatorCall() {
        return await _registerValidator('email', this.data)
      }
    }
  }
</script>
