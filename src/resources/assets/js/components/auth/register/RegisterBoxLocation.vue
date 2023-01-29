<template>
  <div>
    <p class="is-size-7 has-text-weight-bold has-text-grey-light is-uppercase">
      ... living in ...
    </p>
    <div class="field">
      <div class="control is-expanded">
        <div class="select is-fullwidth">
          <select title="location"
              v-model="location"
              name="location"
              required>
            <option :value="country.name" v-for="country in countries">{{ country.name }}</option>
          </select>
        </div>
      </div>
    </div>
    <p class="is-size-7 has-text-weight-bold has-text-grey-light is-uppercase">
      Suggestions
    </p>
    <section class="has-margin-bottom">
      <button type="button" class="button is-flat is-wide has-align-left" v-for="suggestion in suggestions"
              @click="location = suggestion.country">
        <span class="circle">{{ suggestion.countryCode }}</span>
        {{ suggestion.country }}
      </button>
    </section>
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
                :class="{'is-loading': loading}" :disabled="!location">
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import registerSteps from '../../../mixins/registerSteps'
  import countries from '../../../resources/countries.json'

  export default {
    name: 'RegisterBoxLocation',
    mixins: [registerSteps],
    computed: {
      location: {
        get() {
          return this.$store.state.register.data.location
        },
        set(value) {
          this.$store.commit('register/setLocation', value)
        }
      },
      countries() {
        return countries
      }
    },
    data() {
      return {
        suggestions: []
      }
    },
    created() {
      let instance = axios.create()
      delete instance.defaults.headers.common['X-CSRF-TOKEN']

      instance.get('http://ip-api.com/json')
        .then((request) => {
          this.suggestions.push({
            countryCode: request.data.countryCode || '',
            country: request.data.country || ''
          })
        }).catch((error) => {
        console.log(error)
      })
    }
  }
</script>

<style scoped>
  button:hover .circle {
    color: #000;
    border: 1px solid #000;
  }

  .circle {
    display: block;
    color: rgba(0, 0, 0, 0.54);
    border: 1px solid rgba(0, 0, 0, 0.54);
    width: 24px;
    height: 24px;
    border-radius: 24px;
    font-size: 12px;
    line-height: 24px;
    margin-right: 32px;
  }

  .has-margin-bottom {
    margin-bottom: 15px;
  }

  .has-align-left {
    justify-content: flex-start
  }
</style>