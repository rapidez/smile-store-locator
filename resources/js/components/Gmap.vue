<script>
import { gmapApi } from 'gmap-vue'

export default {
    render() {
        return this.$scopedSlots.default({
            zoomToPlace: this.zoomToPlace,
            selectLocation: this.selectLocation,
            currentLocation: this.currentLocation,
            selectedLocation: this.selectedLocation,
            visibleLocations: this.visibleLocations,
            retailers: this.retailers,
            getUpcomingOpeningTime: this.getUpcomingOpeningTime
        })
    },

    data: () => ({
        selectedLocation: null,
        visibleLocations: [],
        retailers: config.retailers,
    }),

    async mounted() {
        await this.$gmapApiPromiseLazy()

        if (this.map) {
            this.map.$mapPromise.then((map) => {
                const bounds = new google.maps.LatLngBounds()
                for (let retailer of this.retailers) {
                    bounds.extend({
                        lat: retailer.latitude,
                        lng: retailer.longitude,
                    })
                }
                map.fitBounds(bounds)
            })

            this.map.$on('bounds_changed', window.debounce(this.onBoundsChanged, 50));
            this.map.$on('center_changed', window.debounce(this.onCenterChanged, 50));
        }
    },

    methods: {
        currentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    this.zoomToPlace({
                        geometry: {
                            location: {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            }
                        }
                    })
                });
            } else {
               alert('Your browser does not support geolocation.')
            }
        },

        onBoundsChanged(bounds) {
            this.visibleLocations = []
            this.retailers.forEach(retailer => {
                if (bounds.contains({ lat: retailer.latitude, lng: retailer.longitude })) {
                    this.visibleLocations.push(retailer.address_id)
                }
            })
        },

        onCenterChanged(center) {
            this.sortRetailers(center)
        },

        calculateDistance(lat1, lng1, lat2, lng2) {
            lat1 = this.convertToRadians(lat1);
            lng1 = this.convertToRadians(lng1);
            lat2 = this.convertToRadians(lat2);
            lng2 = this.convertToRadians(lng2);

            let lngDiff = lng2 - lng1;
            let latDiff = lat2 - lat1;

            // Calculate the distance in Radians.
            let distance = 2 * Math.asin(
                Math.sqrt(
                        Math.pow(Math.sin(latDiff / 2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(lngDiff / 2),2)
                    )
                );

            // Radius of earth in kilometers.
            let radius = 6371;

            // calculate the result
            return distance * radius;
        },

        convertToRadians(coord) {
            return coord / (180 / Math.PI);
        },

        sortRetailers: function (center) {
            center = center ?? this.map.$mapObject.center
            let maplat = center.lat();
            let maplng = center.lng();
            if (!maplat || !maplng) return;

            this.retailers = this.retailers.sort((retailer1, retailer2) => this.calculateDistance(maplat, maplng, retailer1.latitude, retailer1.longitude) - this.calculateDistance(maplat, maplng, retailer2.latitude, retailer2.longitude));
        },

        selectLocation(id) {
            this.selectedLocation = this.retailers.find((retailer) => retailer.address_id == id)
            const bounds = new google.maps.LatLngBounds()
            bounds.extend({
                lat: this.selectedLocation.latitude,
                lng: this.selectedLocation.longitude,
            })
            this.map.fitBounds(bounds)
            this.map.$mapObject.setZoom(15)

            this.onBoundsChanged(this.map.$mapObject.getBounds())
        },

        zoomToPlace(place) {
            this.map.$mapObject.setZoom(11)
            this.map.$mapObject.setCenter(place.geometry.location)

            this.selectedLocation = null

            this.onBoundsChanged(this.map.$mapObject.getBounds())
        },
        getUpcomingOpeningTime(retailer) {
            if (retailer.opening_time) {
                // When the retailer has an opening_time, then it will be opened later this day
                let openingTime = Date.parse(retailer.opening_time)

                return !isNaN(openingTime) ? new Date(openingTime).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : retailer.opening_time
            }

            let date = new Date(),
                today = date.getDay(),
                dayNumber = today !== 7 ? today : 0,
                dayIterator = 1,
                upcomingDay = false

            while (!upcomingDay || upcomingDay === 'undefined') {
                // Get upcomming day if it has special opening hours
                upcomingDay = retailer.times.find((time) => time.attribute_code == 'special_opening_hours' && time.date == (new Date(date.setDate(date.getDate() + dayIterator))).toISOString().split('T')[0])
                dayIterator += 1

                // When the upcomming day's start- and end_time are the same, then the retailer is closed the upcoming day, so set the upcomingDay undefined
                upcomingDay = upcomingDay && upcomingDay !== 'undefined' && upcomingDay.start_time == upcomingDay.end_time ? 'undefined' : upcomingDay

                // If there aren't special opening hours, get the default opening hours
                if (!upcomingDay || upcomingDay === 'undefined') {
                    dayNumber = dayNumber + 1 !== 7 ? dayNumber + 1 : 0
                    upcomingDay = retailer.times.find((time) => time.attribute_code == 'opening_hours' && parseInt(time.day_of_week) == dayNumber)
                }
            }

            // If the retailer no longer opens today, show what day it will open again. Otherwise, show the time the store opens today
            return (dayNumber !== (today !== 7 ? today : 0)) ? config.day_names[dayNumber].toLowerCase() : upcomingDay.start_time
        }
    },

    computed: {
        map: function () {
            return this.$scopedSlots.default()[0].context.$refs.map
        }
    }
}
</script>
