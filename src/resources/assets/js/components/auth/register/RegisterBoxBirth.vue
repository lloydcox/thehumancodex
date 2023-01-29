<template>
  <div>
    <p class="is-size-7 has-text-weight-bold has-text-grey-light is-uppercase">
      ... born on ...
    </p>
    <thc-input
        v-model="birthdate"
        name="birthdate"
        type="date"
        :isRequired="true"
        label="Date of birth"
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
                :class="{'is-loading': loading}" :disabled="!birthdate">
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
    name: 'RegisterBoxBirth',
    mixins: [registerSteps],
    computed: {
      birthdate: {
        get() {
          return this.$store.state.register.data.birthdate
        },
        set(value) {
          this.$store.commit('register/setBirthdate', value)
        }
      }
    },
    methods: {
      async validatorCall() {
        return await _registerValidator('birthdate', this.data)
      }
    }
  }
</script>
