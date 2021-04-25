@extends('layouts.app_master')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            Maps Cabang
        </div>
        <div class="card-body">
            @if(Auth::user()->level_id == 1)
                <div class="alert alert-info" role="alert">
                    Tambahkan pin maps pada master cabang dengan cara memberikan koordinat Latitude dan Longitude.
                </div>
            @endif

            <!-- Start - Maps -->
            <div id="mapid" style="height:500px"></div>
            <!-- End - Maps -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

<script>
    var mymap = L.map('mapid').setView([-1.265386, 116.831200], 5);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoibmF1ZmFscml2YWxkaSIsImEiOiJja25ybHFvOGUwYTU1Mm9vcm41ZzZmdGxjIn0.r1TeCxOMGcGYWQW13SAxtQ'
    }).addTo(mymap);

    @foreach($cabangs as $cabang)
        L.marker(["{{ $cabang->latitude }}", "{{ $cabang->longitude }}"]).addTo(mymap).bindPopup("{{ $cabang->nama }}");
    @endforeach
</script>
@endpush