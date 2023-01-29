<template>
  <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="/">
        thehumancodex
        <sub class="tag is-danger beta-tag">Beta</sub>
      </a>
      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
         :class="{'is-active': mobileMenuOpen}"
         @click="toggleMenu">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>
    <div class="navbar-menu is-hidden-touch">
      <slot name="desktop"></slot>
    </div>
    <div class="navbar-menu is-hidden-desktop" :class="{'is-active': mobileMenuOpen}">
      <slot name="touch"></slot>
    </div>
  </nav>
</template>

<script>
  export default {
    name: 'UserNavbarMenu',
    data() {
      return {
        mobileMenuOpen: false
      }
    },
    methods: {
      toggleMenu() {
        this.mobileMenuOpen = !this.mobileMenuOpen
      }
    }
  }
</script>

<style scoped>
  .beta-tag {
    background-color: #3b0086 !important;
    font-size: 12px;
    position: relative;
    left: 5px;
    padding: 2px 12px;
  }
</style>