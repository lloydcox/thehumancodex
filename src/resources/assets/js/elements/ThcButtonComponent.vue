<template>
  <a class="button-component"
     :href="!disabled ? url : '#!'"
     :title="disabled ? disabledText : ''"
     :class="{
       'button-component--has-image': hasImageSlot,
       'button-component--has-icon': hasIconSlot,
       'button-component--is-disabled': isDisabled,
     }">
    <figure class="button-component__icon icon is-40x40" v-if="hasIconSlot">
      <slot name="icon"></slot>
    </figure>
    <figure class="button-component__image image is-40x40" v-if="hasImageSlot">
      <slot name="image"></slot>
    </figure>
    <div class="button-component__name" :class="{'button-component__name--tall': !hasDescription}">
      <slot></slot>
      <slot name="name"></slot>
    </div>
    <div class="button-component__description">
      <slot name="description"></slot>
    </div>
  </a>
</template>

<script>
  export default {
    name: 'ThcButtonComponent',
    props: {
      url: {
        type: String,
        require: true
      },
      disabledText: {
        type: String,
        default: 'Coming soon!'
      },
      disabled: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      hasImageSlot () {
        return !!this.$slots['image']
      },
      hasIconSlot () {
        return !!this.$slots['icon'] && !this.hasImageSlot
      },
      isDisabled () {
        return this.disabled
      },
      hasDescription () {
        return !!this.$slots['description']
      }
    }
  }
</script>

<style scoped>

</style>