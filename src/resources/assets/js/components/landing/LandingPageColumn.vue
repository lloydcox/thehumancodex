<template>
  <div class="column landing-column">
    <div class="ball is-small is-static"></div>
    <div class="ball" :class="ballPosition"></div>
    <div class="content">
      <slot name="content"></slot>
      <div class="slides">
        <slot :name="'slide'+i" v-for="i in numberOfSlides"></slot>
        <slot name="dots"></slot>
      </div>
    </div>
    <footer class="landing-column-footer">
      <slot name="footer"></slot>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'landing-page-column',
  props: {
    ballPosition: {
      required: true,
      type: String
    },
    numberOfSlides: {
      required: false,
      type: Number,
      default: 0
    }
  }
}
</script>
