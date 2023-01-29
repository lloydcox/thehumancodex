<template>
  <div>
    <invitations :users="results" :show-groups="false" v-if="results.length">
      <template slot="top-section">
        <h5 class="is-size-7 has-text-grey-light">People</h5>
      </template>
    </invitations>
    <template v-else>
      <h5 class="is-size-7 has-text-grey-light">People</h5>
      <p class="is-size-6 has-text-grey-light">No users found.</p>
    </template>
  </div>
</template>

<script>
  export default {
    name: 'SearchResultsUsers',
    props: {
      results: {
        type: Array,
        required: true
      }
    }
  }
</script>

<style scoped>

</style>