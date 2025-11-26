import { defineAsyncComponent } from 'vue'
import { createGmapVuePlugin } from '@gmap-vue/v3'

document.addEventListener('vue:loaded', function (event) {
    const vue = event.detail.vue
    vue.component('gmap', defineAsyncComponent(() => import('./components/Gmap.vue')))

    vue.use(
        createGmapVuePlugin({
            load: {
                key: config.maps.key,
                libraries: config.maps.libraries,
            },
            installComponents: true // these are lazy-loaded by default: https://diegoazh.github.io/gmap-vue/docs/vue-3-version/guide/install-function
        })
    )
})