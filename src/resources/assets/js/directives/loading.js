import Vue from 'vue'

let callback = function(event) {
  let el = event.currentTarget
  el.classList.add('is-loading')
}

export default Vue.directive('loading', {
  inserted: function (el) {
    el.addEventListener('click', callback)
  },
  unbind: function (el) {
    el.addEventListener('click', callback)
  }
})