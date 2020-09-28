Nova.booting((Vue, router, store) => {
  Vue.component('index-text-uppercase', require('./components/IndexField'))
  Vue.component('detail-text-uppercase', require('./components/DetailField'))
  Vue.component('form-text-uppercase', require('./components/FormField'))
})
