/* ==========================================================================
    Google Maps handler
    - https://developers.google.com/maps/documentation/javascript/adding-a-google-map
 ========================================================================== */

const MapsHandler = {

    map: '',
    key: 'AIzaSyCVGPUmRmQRxXvzzWu3Xyu77XebQxQ-f4Y',
    location: {lat: 51.261089, lng: 5.598172},
    styling: '',

    init: function () {
        // Get map by id
        MapsHandler.map = document.querySelector('.js-google-map');

        // Check if a map is defined
        if (isset(MapsHandler.map)) {

            if(MapsHandler.map.hasAttribute('data-google-lat')) MapsHandler.location.lat = parseFloat(MapsHandler.map.getAttribute('data-google-lat'));
            if(MapsHandler.map.hasAttribute('data-google-lng')) MapsHandler.location.lng = parseFloat(MapsHandler.map.getAttribute('data-google-lng'));

            MapsHandler.setCustomStyling();

            // See if google variable exists
            if (typeof(google) == 'undefined' || typeof(google.maps) == 'undefined') {
                // Load external script
                getScript('https://maps.googleapis.com/maps/api/js?key=' + MapsHandler.key, MapsHandler.drawMap);
            } else {
                MapsHandler.drawMap()
            }

        }
    },

    drawMap: function () {
        // Create a map
        let map = new google.maps.Map(MapsHandler.map, {
            zoom: 14,
            center: MapsHandler.location,
            disableDefaultUI: true,
            styles: MapsHandler.styling
        });

        var contentString = '<div><p>Plan een route op '+
            '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' + MapsHandler.location.lat + ',' + MapsHandler.location.lng +'" class="link">'+
            'google maps</a> '+
            '</p></div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        // Add a marker
        let marker = new google.maps.Marker({
            position: MapsHandler.location,
            map: map,
            // label: 'A'
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });

    },

    setCustomStyling: function () {

        MapsHandler.styling =
            [
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e9e9e9"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 29
                        },
                        {
                            "weight": 0.2
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 18
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#dedede"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "saturation": 36
                        },
                        {
                            "color": "#333333"
                        },
                        {
                            "lightness": 40
                        }
                    ]
                },
                {
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        },
                        {
                            "lightness": 19
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 17
                        },
                        {
                            "weight": 1.2
                        }
                    ]
                }
            ];

    }
};

MapsHandler.init();