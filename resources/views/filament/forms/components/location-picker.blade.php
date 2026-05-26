@php
    $latStatePath = $getLatStatePath();
    $lngStatePath = $getLngStatePath();
    $initialLat = (float) ($getLat() ?? 33.3152);
    $initialLng = (float) ($getLng() ?? 44.3661);
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        wire:ignore
        x-data="liqahiLocationPicker({
            initialLat: {{ $initialLat }},
            initialLng: {{ $initialLng }},
            latPath: @js($latStatePath),
            lngPath: @js($lngStatePath),
        })"
        x-init="init()"
        class="overflow-hidden rounded-lg border border-gray-200 dark:border-white/10"
        style="height: 360px"
    ></div>
</x-dynamic-component>

@assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
@endassets

@script
<script>
    Alpine.data('liqahiLocationPicker', ({ initialLat, initialLng, latPath, lngPath }) => ({
        map: null,
        marker: null,
        init() {
            const start = () => {
                if (typeof L === 'undefined') {
                    setTimeout(start, 200);
                    return;
                }
                this.map = L.map(this.$el).setView([initialLat, initialLng], 12);
                const dark = document.documentElement.classList.contains('dark');
                const tiles = dark
                    ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                    : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                L.tileLayer(tiles, { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(this.map);
                this.marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(this.map);
                this.marker.on('dragend', () => {
                    const { lat, lng } = this.marker.getLatLng();
                    this.write(lat, lng);
                });
                this.map.on('click', (e) => {
                    this.marker.setLatLng(e.latlng);
                    this.write(e.latlng.lat, e.latlng.lng);
                });
                setTimeout(() => this.map.invalidateSize(), 100);
            };
            start();
        },
        write(lat, lng) {
            this.$wire.set(latPath, Number(lat.toFixed(7)));
            this.$wire.set(lngPath, Number(lng.toFixed(7)));
        },
    }));
</script>
@endscript
