<template>
  <form :action="action">
    <template v-if="template === 'SearchFilters'">
      <search-filters @select="onSelect"></search-filters>
    </template>
    <template v-if="template === 'SearchLocation'">
      <search-location
          :name="key"
          :placeholder="key === 'birth' ? 'Search for a place of birth' : 'Search for a codex location'"
          @submit="template = 'SearchFilters'"></search-location>
    </template>
  </form>
</template>

<script>
  import SearchFilters from './SearchFilters'
  export default {
    name: 'SearchForm',
    components: {SearchFilters},
    props: {
      action: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        template: 'SearchFilters',
        key: '',
      }
    },
    methods: {
      onSelect(data) {
        this.template = data.template
        this.key = data.key || ''
      }
    }
  }
</script>