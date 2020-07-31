Nova.booting((Vue, router, store) => {
  Vue.component('index-router-link', require('./components/IndexField'))
  Vue.component('detail-router-link', require('./components/DetailField'))
  Vue.component('form-router-link', require('./components/FormField'))
})
