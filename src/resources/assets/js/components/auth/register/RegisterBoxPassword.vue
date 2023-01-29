<template>
  <div>
    <p class="is-size-7 has-text-weight-bold has-text-grey-light is-uppercase">
      ... and I’d like my password to be ...
    </p>
    <thc-input
        v-model="password"
        name="password"
        type="password"
        :isRequired="true"
        :error="typeof errors.password !== 'undefined' ? errors.password[0] : ''"
        label="Password"
    ></thc-input>
    <div class="columns">
      <div class="column is-2">
        <button type="button" class="button is-rounded is-circle"
                @click="prev()">
          <i class="fa fa-arrow-left"></i>
        </button>
      </div>
      <div class="column">
        <button type="button" class="button is-rounded is-primary is-flat is-wide"
                @click="next()"
                :class="{'is-loading': loading}" :disabled="!password">
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  import registerSteps from '../../../mixins/registerSteps'
  import { _registerValidator } from '../../../api/auth'

  export default {
    name: 'RegisterBoxPassword',
    mixins: [registerSteps],
    computed: {
      password: {
        get() {
          return this.$store.state.register.data.password
        },
        set(value) {
          this.$store.commit('register/setPassword', value)
        }
      },
      data() {
        return {
          password: this.password
        }
      }
    },
    methods: {
      async _next() {
        try {
          this.loading = true
          const response = await this.$store.dispatch('register/createAccount')
          location.replace(response.redirect)
        } catch (error) {
          this.errors = error
          this.loading = false
        }
      },
      async validatorCall() {
        return await _registerValidator('password', this.data)
      }
    }
  }
</script>
