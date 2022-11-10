import * as GmapVue from 'gmap-vue'

Vue.use(GmapVue, {
    load: {
        key: config.maps.key,
        libraries: config.maps.libraries,
    },
    installComponents: true
})

Vue.component('gmap', () => import('./components/Gmap.vue'))
