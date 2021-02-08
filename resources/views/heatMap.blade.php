@include('layouts.header')
@section('title', 'Leaflet Heatmap Layer Example')
<body>
{{--<h1>Leaflet Heatmap Layer Example</h1>--}}

<!-- Image loader -->
<div id='loader' style='display: none;'>
    <img src='{{ url('assets/images/reload.gif') }}' width='100%' height='100%'>
</div>
<!-- Image loader -->
<div id="map"></div>


</body>
@include('layouts.footer')

<script>
    window.onload = function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let url = 'GeoDataApiController';
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                _token: '{{ csrf_token() }}'
            },
            async: true,
            dataType: "json",
            beforeSend: function () {
                $("#loader").show();
            },
            success: function (resultData) {
                loadMapData(resultData);
            },
            complete: function (resultData) {
                $("#loader").hide();
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });

        function loadMapData(resultData) {
            var result = {
                max: 8,
                data: resultData.data
            };

            var baseLayer = L.tileLayer(
                'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '',
                    maxZoom: 15
                }
            );

            var cfg = {
                "radius": 2,
                "maxOpacity": .8,
                "scaleRadius": true,
                "useLocalExtrema": true,
                // which field name in your data represents the latitude - default "lat"
                latField: 'latitude',
                // which field name in your data represents the longitude - default "lng"
                lngField: 'longitude',
                // which field name in your data represents the data value - default "value"
                valueField: 'count'
            };


            var heatmapLayer = new HeatmapOverlay(cfg);
            var editableLayers = new L.FeatureGroup();

            var map = new L.Map('map', {
                center: new L.LatLng(25.6586, -80.3568),
                zoom: 4,
                layers: [baseLayer, heatmapLayer, editableLayers]
            });

            heatmapLayer.setData(result);

            layer = heatmapLayer;

            var drawPluginOptions = {
                position: 'topright',
                draw: {
                    polygon: {
                        allowIntersection: false, // Restricts shapes to simple polygons
                        drawError: {
                            color: '#e1e100', // Color the shape will turn when intersects
                            message: '<strong>Oh snap!<strong> you can\'t draw that!' // Message that will show when intersect
                        },
                        shapeOptions: {
                            color: '#97009c'
                        }
                    },
                    // disable toolbar item by setting it to false
                    polyline: false,
                    circle: false, // Turns off this drawing tool
                    rectangle: false,
                    marker: false,
                    remove: true
                },
                edit: {
                    featureGroup: editableLayers, //REQUIRED!!
                    remove: true
                }
            };

            // Initialise the draw control and pass it the FeatureGroup of editable layers
            var drawControl = new L.Control.Draw(drawPluginOptions);
            map.addControl(drawControl);

            L.EditToolbar.Delete.include({
                removeAllLayers: true
            });

            var editableLayers = new L.FeatureGroup();
            map.addLayer(editableLayers);

            map.on('draw:created', function (e) {
                var type = e.layerType,
                    layer = e.layer;

                if (type === 'marker') {
                    layer.bindPopup('A popup!');
                }

                editableLayers.addLayer(layer);
            });
            map.on('draw:created', function (e) {
                alert("here");
                var layer = e.layer;

                var shape = layer.toGeoJSON()
                console.log(shape['geometry']['coordinates']);
                var shape_for_db = JSON.stringify(shape);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let url = 'polygon';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}',
                        coordinates: shape['geometry']['coordinates']
                    },
                    async: true,
                    dataType: "json",
                    beforeSend: function () {
                        $("#loader").show();
                    },
                    success: function (resultData) {
                        heatmapLayer.setData(resultData);
                        heatmapLayer._reset();
                        heatmapLayer._update();
                    },
                    complete: function (resultData) {
                        $("#loader").hide();
                    }
                });
            });
        }

    };
</script>
