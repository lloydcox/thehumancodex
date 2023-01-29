<template>
  <div class="columns is-multiline">
    <div class="column is-8 is-offset-2">
      <div class="media is-white-bis-hover has-cursor-pointer" @click="select('placeOfBirth')">
        <figure class="media-left">
          <span class="icon has-text-primary spacer">
            <i class="fas fa-map-marker-alt"></i>
          </span>
        </figure>
        <div class="media-content">
          <div class="content is-size-6 has-text-black">
            <strong>Place of birth</strong><br>
            <span class="is-size-7 has-text-grey">{{ filters.placeOfBirth ? `${filters.placeOfBirth.city}, ${filters.placeOfBirth.country}` : 'Anywhere' }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="column is-8 is-offset-2">
      <div class="media is-white-bis-hover has-cursor-pointer" @click="select('codexLocation')">
        <figure class="media-left">
          <span class="icon has-text-primary spacer">
            <i class="fas fa-id-badge"></i>
          </span>
        </figure>
        <div class="media-content">
          <div class="content is-size-6 has-text-black">
            <strong>Codex location</strong><br>
            <span class="is-size-7 has-text-grey">{{ filters.codexLocation ? `${filters.codexLocation.city}, ${filters.codexLocation.country}` : 'Anywhere' }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="column is-8 is-offset-2">
      <button type="submit" class="button is-primary is-rounded is-flat is-wide">Show results</button>
      <input type="hidden" name="filters[placeOfBirth]" :value="filters.placeOfBirth ? `${filters.placeOfBirth.city}, ${filters.placeOfBirth.country}` : null">
      <input type="hidden" name="filters[codexLocation]" :value="filters.codexLocation ? `${filters.codexLocation.city}, ${filters.codexLocation.country}` : null">
      <input type="hidden" name="q" :value="query">
    </div>
  </div>
</template>

<script>
  export default {
    name: 'SearchFilters',
    computed: {
      query() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('q');
      }
    },
    data() {
      return {
        filters: {}
      }
    },
    created() {
      let filters = this.$cookies.get('searchFilters')
      if(filters) {
        this.filters = filters
      }
    },
    methods: {
      select(key) {
        this.$emit('select', {
          template: 'SearchLocation',
          key: key
        })
      }
    }
  }
</script>