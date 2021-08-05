<script>
import { gmapApi } from 'gmap-vue'

export default {
    render() {
        return this.$scopedSlots.default({
            zoomToPlace: this.zoomToPlace,
            selectLocation: this.selectLocation,
            selectedLocation: this.selectedLocation,
            visibleLocations: this.visibleLocations,
        })
    },

    data: () => ({
        selectedLocation: null,
        visibleLocations: [],
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
            this.visibleLocations = []
        },

        zoomToPlace(place) {
            this.map.$mapObject.setZoom(11)
            this.map.$mapObject.setCenter(place.geometry.location)

            this.selectedLocation = null
            this.visibleLocations = []
            let bounds = this.map.$mapObject.getBounds()
            config.retailers.forEach(retailer => {
                if (bounds.contains({ lat: retailer.latitude, lng: retailer.longitude })) {
                    this.visibleLocations.push(retailer.address_id)
                }
            })
        }
    },

    computed: {
        map: function () {
            return this.$scopedSlots.default()[0].context.$refs.map
        }
    }
}
</script>
