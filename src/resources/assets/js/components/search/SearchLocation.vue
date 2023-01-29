<template>
  <div class="columns is-multiline">
    <div class="column is-8 is-offset-2">
      <div class="field search-input-field">
        <div class="control has-icons-right" :class="{'is-loading': loading}">
          <input type="text" 
                 placeholder="Search for a place of birth" 
                 class="input is-rounded"
                 v-model="query"
                 @input="onInput">
          <span class="icon is-right" v-show="!loading">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
    </div>
    <div class="column is-8 is-offset-2">
      <div class="has-margin-1">
        <div class="thc-radio">
          <input id="default" class="is-pulled-right" type="radio" v-model="value" :value="null">
          <label for="default">
            <strong>Anywhere</strong><br/>
            <span class="is-size-7 has-text-color-grey">World</span>
          </label>
        </div>
      </div>
      <div class="has-margin-1" v-for="(result, key) in results" :key="key">
        <div class="thc-radio">
          <input :id="key" class="is-pulled-right" type="radio" v-model="value" :value="result">
          <label :for="key">
            <strong>{{ result.country ? result.city : result.country }}</strong><br/>
            <span class="is-size-7 has-text-color-grey">{{ result.country }}</span>
          </label>
        </div>
      </div>
    </div>
    <div class="column is-8 is-offset-2">
      <button type="button" class="button is-primary is-rounded is-flat is-wide" @click="submit">Show results</button>
    </div>
  </div>
</template>

<script>
  import {_searchForPlaces} from '../../api/search'

  export default {
    name: 'SearchLocation',
    props: {
      name: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        query: '',
        results: [],
        value: null,
        loading: false
      }
    },
    created() {
      this.$cookies.config('1d')

      let filters = this.$cookies.get('searchFilters')
      if(filters) {
        if (filters[this.name]) {
          this.value = filters[this.name]
        }
      }

      this.results = this.restoreLastResults()
    },
    watch: {
      value(newValue) {
        let filters = this.$cookies.get('searchFilters')
        if(!filters) filters = {}
        filters[this.name] = newValue
        this.$cookies.set('searchFilters', filters);
      }
    },
    methods: {
      onInput: _.debounce(async function () {
        this.loading = true
        const response = await _searchForPlaces(this.query)
        let results = this.parseResults(response)
        this.results = results
        this.storeLastResults(results)
        this.loading = false
      }, 1000, {
        'leading': false,
        'trailing': true
      }),
      parseResults(raw) {
        return raw.data
      },
      storeLastResults(data) {
        let filtersResults = this.restoreLastResults()
        if(!filtersResults.length) filtersResults = {}
        filtersResults[this.name] = data
        console.log(filtersResults)
        this.$cookies.set('lastFilterResults', filtersResults);
      },
      restoreLastResults() {
        let lastResults = this.$cookies.get('lastFilterResults')
        if(lastResults) {
          if (lastResults[this.name]) {
            return lastResults[this.name]
          }
        }
        return []
      },
      submit() {
        this.$emit('submit')
      }
    }
  }
</script>