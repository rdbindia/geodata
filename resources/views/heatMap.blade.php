@include('layouts.header')
@section('title', 'Leaflet Heatmap Layer Example')
<body>
<h1>Leaflet Heatmap Layer Example</h1>

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
            async : true,
            dataType: "json",
            beforeSend: function(){
                // Show image container
                $("#loader").show();
            },
            success: function(resultData) {
                var result = {
                    max: 8,
                    data: resultData
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

                var map = new L.Map('map', {
                    center: new L.LatLng(25.6586, -80.3568),
                    zoom: 4,
                    layers: [baseLayer, heatmapLayer]
                });

                heatmapLayer.setData(result);

                layer = heatmapLayer;
            },
            complete:function(resultData){
                $("#loader").hide();
            }
        });




    };
</script>
