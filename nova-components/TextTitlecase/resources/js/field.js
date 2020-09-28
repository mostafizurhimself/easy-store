Nova.booting((Vue, router, store) => {
  Vue.component('index-text-titlecase', require('./components/IndexField'))
  Vue.component('detail-text-titlecase', require('./components/DetailField'))
  Vue.component('form-text-titlecase', require('./components/FormField'))
})
