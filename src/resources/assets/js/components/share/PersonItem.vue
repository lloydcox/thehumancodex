<template>
  <div class="connection-item has-cursor-pointer">
    <div @click="onPersonClick" class="connection-item-profile">
      <div class="image is-40x40 is-pulled-left">
        <img :src="user.avatar" :alt="user.first_name" class="is-rounded">
      </div>
      {{ name }}
    </div>
    <slot name="actions"/>
  </div>
</template>

<script>
  export default {
    name: 'PersonItem',
    props: {
      user: {
        type: Object,
        required: true
      },
      preventProfileRedirection: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      name () {
        return `${this.user.first_name} ${this.user.last_name}`
      }
    },
    methods: {
      onPersonClick() {
        if (!this.preventProfileRedirection) {
          let getUrl = window.location
          let baseUrl = getUrl.protocol + "//" + getUrl.host
          window.location.replace(baseUrl + '/codex/' + this.user.username)
        }
        this.$emit('clickOnPerson', this.user)
      }
    }
  }
</script>

<style scoped>

</style>