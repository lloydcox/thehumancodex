<template>
  <div class="thc-dropdown" :id="id">
    <div class="thc-dropdown-trigger has-cursor-pointer" @click="toggle">
      <i class="fas fa-ellipsis-v"></i>
    </div>
    <transition name="drop" mode="out-in">
      <div class="thc-dropdown-content" v-if="isOpen">
        <slot></slot>
      </div>
    </transition>
  </div>
</template>

<script>
  export default {
    name: 'ThcDropdown',
    data() {
      return {
        isOpen: false,
        id: 'dropdown-' + Math.random().toString(36).substring(2)
      }
    },
    beforeDestroy () {
      document.body.removeEventListener('click', this.onBodyClick)
    },
    methods: {
      toggle() {
        this.isOpen = !this.isOpen
        if (this.isOpen) {
          document.body.addEventListener('click', this.onBodyClick)
        } else {
          document.body.removeEventListener('click', this.onBodyClick)
        }
      },
      onBodyClick(event) {
        let t = event.target
        for (let i = 0; i < 5; i++) {
          if (t) {
            if (t.id === this.id) return
            t = t.parentElement
          }
        }
        this.isOpen = false
      }
    }
  }
</script>