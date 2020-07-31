Nova.booting((Vue, router, store) => {
  Vue.component('index-permission-checkbox', require('./components/IndexField'))
  Vue.component('detail-permission-checkbox', require('./components/DetailField'))
  Vue.component('form-permission-checkbox', require('./components/FormField'))
})
