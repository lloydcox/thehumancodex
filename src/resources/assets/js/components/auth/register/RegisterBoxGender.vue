<template>
  <div>
    <p class="is-size-7 has-text-weight-bold has-text-grey-light is-uppercase">
      … And I’m ...
    </p>
    <div class="radio-circle">
      <input type="radio" name="gender" id="genderF" value="female" v-model="gender">
      <label for="genderF">Female</label>
    </div>
    <div class="radio-circle">
      <input type="radio" name="gender" id="genderM" value="male" v-model="gender">
      <label for="genderM">Male</label>
    </div>
    <div class="radio-circle">
      <input type="radio" name="gender" id="genderO" value="other" v-model="gender">
      <label for="genderO">Other</label>
    </div>
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
                :class="{'is-loading': loading}">
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  import registerSteps from '../../../mixins/registerSteps'

  export default {
    name: 'RegisterBoxGender',
    mixins: [registerSteps],
    computed: {
      gender: {
        get() {
          return this.$store.state.register.data.gender
        },
        set(value) {
          this.$store.commit('register/setGender', value)
        }
      }
    }
  }
</script>
