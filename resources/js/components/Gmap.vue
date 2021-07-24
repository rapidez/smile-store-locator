<script>
import { gmapApi } from 'gmap-vue'

export default {
    render() {
        return this.$scopedSlots.default({
            selectLocation: this.selectLocation,
            selectedLocation: this.selectedLocation,
        })
    },

    data: () => ({
        selectedLocation: null
    }),

    mounted() {
        this.map.$mapPromise.then((map) => {
            const bounds = new google.maps.LatLngBounds()
            for (let retailer of config.retailers) {
                bounds.extend({
                    lat: retailer.latitude,
                    lng: retailer.longitude,
                })
            }
            map.fitBounds(bounds)
        })
    },

    methods: {
        selectLocation(id) {
            this.selectedLocation = config.retailers.find((retailer) => retailer.address_id == id)
            const bounds = new google.maps.LatLngBounds()
            bounds.extend({
                lat: this.selectedLocation.latitude,
                lng: this.selectedLocation.longitude,
            })
            this.map.fitBounds(bounds)
            this.map.$mapObject.setZoom(15)
        }
    },

    computed: {
        map: function () {
            return this.$scopedSlots.default()[0].context.$refs.map
        }
    }
}
</script>
