<template>
  <div class="message-box dialog-mask" @click="$close(false)">
    <div class="dialog-content">
      <header>{{ title }}</header>
      <div class="dialog-body">
        <p>{{ content }}</p>
      </div>
      <footer>
        <button type="button" class="button is-rounded is-flat" @click="$close(true)">Yes</button>
        <button type="button" class="button is-rounded is-flat" @click="$close(false)">No</button>
      </footer>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Confirm'
  }
</script>