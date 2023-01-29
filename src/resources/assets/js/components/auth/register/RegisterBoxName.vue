<template>
  <div>
    <p class="is-size-7 has-text-weight-bold has-text-grey-light is-uppercase">
      Hey, I’m...
    </p>
    <thc-input
        v-model="firstName"
        name="firstName"
        :isRequired="true"
        :error="typeof errors.first_name !== 'undefined' ? errors.first_name[0] : ''"
        label="First name"
    ></thc-input>
    <thc-input
        v-model="lastName"
        name="lastName"
        :isRequired="true"
        :error="typeof errors.last_name !== 'undefined' ? errors.last_name[0] : ''"
        label="Last name"
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
                :class="{'is-loading': loading}" :disabled="!firstName || !lastName">
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
    name: 'RegisterBoxName',
    mixins: [registerSteps],
    computed: {
      firstName: {
        get() {
          return this.$store.state.register.data.firstName
        },
        set(value) {
          this.$store.commit('register/setFirstName', value)
        }
      },
      lastName: {
        get() {
          return this.$store.state.register.data.lastName
        },
        set(value) {
          this.$store.commit('register/setLastName', value)
        }
      },
      data() {
        return {
          first_name: this.firstName,
          last_name: this.lastName
        }
      }
    },
    methods: {
      async validatorCall() {
        return await _registerValidator('name', this.data)
      }
    }
  }
</script>
