export default {
  data() {
    return {
      loading: false,
      errors: {}
    }
  },
  methods: {
    _next() {
      this.$store.commit('register/nextStep')
    },
    next: _.debounce(function () {
      if (this.validatorCall) {
        this.validation()
      } else {
        this._next()
      }
    }, 500, {
      'leading': true,
      'trailing': false
    }),
    prev: _.debounce(function () {
      this.$store.commit('register/prevStep')
    }, 500, {
      'leading': true,
      'trailing': false
    }),
    async validation() {
      try {
        this.loading = true
        await this.validatorCall()
        this._next()
        this.loading = false
      } catch (errors) {
        this.errors = errors
        this.loading = false
      }
    },
    validatorCall() {
      return Promise.resolve()
    }
  }
}