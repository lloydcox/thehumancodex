<template>
  <div class="columns is-marginless landing-page">

    <landing-page-column :ball-position="ballPosition(true)" :class="colorClass" :number-of-slides="numberOfSlides">
      <section :slot="'slide'+i" v-for="i in numberOfSlides" class="slide" v-show="slide + 1 === i" :key="i">
        <slot :name="'slide'+i"></slot>
      </section>
      <template slot="dots">
        <div class="dots">
          <div class="dot-container" @click="slide = 0">
            <div class="dot" :class="{'is-active': slide === 0 }"></div>
          </div>
          <div class="dot-container" @click="slide = 1">
            <div class="dot" :class="{'is-active': slide === 1 }"></div>
          </div>
          <div class="dot-container" @click="slide = 2">
            <div class="dot" :class="{'is-active': slide === 2 }"></div>
          </div>
        </div>
      </template>
    </landing-page-column>

    <landing-page-column :ball-position="ballPosition(false)">
      <template slot="content">
        <slot name="login"></slot>
      </template>
      <template slot="footer">
        <slot name="footer"></slot>
      </template>
    </landing-page-column>
  </div>
</template>

<script>
import LandingPageColumn from './LandingPageColumn.vue'

export default {
  name: 'landing-page',
  components: {
    LandingPageColumn
  },
  computed: {
    colorClass () {
      return this.colorClasses[this.slide]
    }
  },
  data () {
    return {
      colorClasses: ['is-purple', 'is-orange', 'is-blue'],
      ballPositions: ['left', 'center', 'right'],
      numberOfSlides: 3,
      slide: 0
    }
  },
  methods: {
    ballPosition (reversed) {
      let collection = this.ballPositions
      if (reversed) {
        return collection[2-this.slide]
      }
      return collection[this.slide]
    }
  }
}
</script>
