<template>
  <section class="media kudo">
    <div class="media-left">
      <figure class="image is-40x40">
        <img :src="kudo.user.avatar" alt="Avatar" class="is-rounded">
      </figure>
    </div>
    <div class="media-content">
      <a :href="`codex/${kudo.user.username}`" class="title is-size-7 has-font-sans has-text-primary">{{ name }}</a>
    </div>
  </section>
</template>

<script>
  export default {
    name: 'TimelinePostKudo',
    props: {
      kudo: {
        required: true,
        type: Object
      }
    },
    computed: {
      name() {
        return `${this.kudo.user.first_name} ${this.kudo.user.last_name}`
      }
    }
  }
</script>

<style scoped>
  .kudo.media {
    border: 0;
    margin-bottom: 0;
    margin-top: 0;
  }
  .media-content {
    padding: 0.5rem 0;
  }
</style>