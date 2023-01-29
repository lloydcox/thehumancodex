let toasterOptions = {
  progressBar: true,
  preventDuplicates: true,
  closeButton: true,
  timeOut: 10000,
  extendedTimeOut: 5000
}

export default {
    methods: {
      displayErrorMessage(error) {
        console.error(error)
        this.$toastr.error(error || 'Something went wrong. Try again later', 'Whoops!', toasterOptions);
      },
      displaySuccessMessage(message) {
        this.$toastr.success(message, null, toasterOptions);
      }
    }
}