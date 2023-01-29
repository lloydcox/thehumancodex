<template>
  <button @click="copy">
    <slot></slot>
  </button>
</template>

<script>
  import messages from '../mixins/messages'

  export default {
    name: 'ThcCopyButton',
    mixins: [
      messages
    ],
    props: {
      text: {
        type: String,
        required: true
      },
      messageOnCopy: {
        type: String,
        required: false
      }
    },
    methods: {
      copy() {
        this.$copyText(this.text).then(() => {
          this.displaySuccessMessage(this.messageOnCopy)
        })
      }
    }
  }
</script>

<style scoped>

</style>