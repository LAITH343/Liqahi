<div
    x-data="liqahiSearch({
        lat: @entangle('latitude').live,
        lng: @entangle('longitude').live,
    })"
    x-init="init()"
    class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8"
>
    <header class="flex items-center justify-between gap-4 pb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-semibold tracking-tight">{{ __('messages.app_name') }}</h1>
            <span class="hidden text-sm text-zinc-500 sm:inline">— {{ __('messages.tagline') }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="https://github.com/LAITH343/Liqahi" target="_blank" rel="noopener"
                title="source code"
                class="inline-flex items-center gap-1.5 rounded-md border border-zinc-200 px-3 py-1.5 text-sm hover:bg-zinc-100 dark:border-white/10 dark:hover:bg-white/5">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.262.82-.582 0-.288-.012-1.243-.018-2.255-3.338.725-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.082-.73.082-.73 1.205.085 1.84 1.237 1.84 1.237 1.07 1.834 2.807 1.304 3.492.997.108-.775.42-1.305.763-1.605-2.665-.305-5.466-1.332-5.466-5.93 0-1.31.468-2.382 1.236-3.222-.124-.303-.536-1.524.117-3.176 0 0 1.008-.322 3.3 1.23a11.5 11.5 0 0 1 3.003-.404c1.02.005 2.047.138 3.003.404 2.29-1.552 3.296-1.23 3.296-1.23.655 1.652.243 2.873.12 3.176.77.84 1.235 1.911 1.235 3.222 0 4.61-2.807 5.62-5.48 5.92.43.372.814 1.104.814 2.226 0 1.606-.015 2.9-.015 3.293 0 .322.218.7.825.58C20.565 21.795 24 17.296 24 12c0-6.63-5.373-12-12-12Z"/>
                </svg>
            </a>
            <form method="POST" action="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
                @csrf
                <button type="submit"
                    class="rounded-md border border-zinc-200 px-3 py-1.5 text-sm hover:bg-zinc-100 dark:border-white/10 dark:hover:bg-white/5"
                    title="{{ __('messages.language') }}">
                    {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                </button>
            </form>
            <button type="button"
                x-on:click="toggleTheme()"
                class="rounded-md border border-zinc-200 px-3 py-1.5 text-sm hover:bg-zinc-100 dark:border-white/10 dark:hover:bg-white/5"
                :title="$root.dataset.theme === 'dark' ? '{{ __('messages.theme_toggle') }}' : '{{ __('messages.theme_toggle') }}'">
                <span x-show="!isDark()">🌙</span>
                <span x-show="isDark()">☀</span>
            </button>
        </div>
    </header>

    <section class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-zinc-900">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">{{ __('messages.find_vaccine') }}</label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.250ms="itemSearch"
                        class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-zinc-950"
                        placeholder="{{ __('messages.find_vaccine') }}"
                        autocomplete="off"
                    >
                    @if($this->itemSuggestions->isNotEmpty())
                        <ul class="absolute z-20 mt-1 max-h-64 w-full overflow-auto rounded-md border border-zinc-200 bg-white shadow-lg dark:border-white/10 dark:bg-zinc-900">
                            @foreach($this->itemSuggestions as $item)
                                <li>
                                    <button type="button"
                                        wire:click="selectItem({{ $item->id }}, @js($item->localizedName()))"
                                        class="block w-full px-3 py-2 text-start text-sm hover:bg-zinc-100 dark:hover:bg-white/5">
                                        {{ $item->localizedName() }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if($itemId)
                        <button type="button" wire:click="clearItem"
                            class="absolute end-2 top-1/2 -translate-y-1/2 text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                            ✕
                        </button>
                    @endif
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">{{ __('messages.address_placeholder') }}</label>
                <div class="flex gap-2">
                    <input
                        type="text"
                        wire:model="address"
                        wire:keydown.enter="geocode"
                        class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-zinc-950"
                        placeholder="{{ __('messages.address_placeholder') }}"
                    >
                    <button type="button" wire:click="geocode"
                        class="rounded-md bg-amber-500 px-3 py-2 text-sm font-medium text-white hover:bg-amber-600">
                        {{ __('messages.search') }}
                    </button>
                </div>
            </div>
        </div>

        @if($error)
            <p class="mt-3 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
        @endif
    </section>

    @php($needsLocation = $itemId && (! $latitude || ! $longitude))
    @php($hideMobilePanel = ! $this->selectedCenter && ! ($itemId && $latitude && $longitude) && ! $needsLocation)

    <section class="mt-6 grid gap-4 lg:grid-cols-5">
        <div class="order-2 lg:order-1 lg:col-span-2 {{ $hideMobilePanel ? 'hidden lg:block' : '' }}">
            @if($this->selectedCenter)
                @php($center = $this->selectedCenter)
                <div class="flex h-[420px] flex-col overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm lg:h-[480px] dark:border-white/10 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-2 border-b border-zinc-200 p-4 dark:border-white/10">
                        <div>
                            <h3 class="font-semibold">{{ $center->localizedName() }}</h3>
                            <p class="mt-0.5 text-sm text-zinc-500">{{ app()->getLocale() === 'ar' ? $center->address_ar : $center->address_en }}</p>
                            <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                @if($center->phone)
                                    <a href="tel:{{ $center->phone }}"
                                        class="rounded-md border border-zinc-200 px-2 py-1 hover:bg-zinc-100 dark:border-white/10 dark:hover:bg-white/5">
                                        {{ __('messages.call') }}
                                    </a>
                                @endif
                                <a target="_blank" rel="noopener"
                                    href="https://www.google.com/maps/dir/?api=1&destination={{ $center->latitude }},{{ $center->longitude }}"
                                    class="rounded-md border border-zinc-200 px-2 py-1 hover:bg-zinc-100 dark:border-white/10 dark:hover:bg-white/5">
                                    {{ __('messages.open_google_maps') }}
                                </a>
                                <a target="_blank" rel="noopener"
                                    href="https://waze.com/ul?ll={{ $center->latitude }},{{ $center->longitude }}&navigate=yes"
                                    class="rounded-md border border-zinc-200 px-2 py-1 hover:bg-zinc-100 dark:border-white/10 dark:hover:bg-white/5">
                                    {{ __('messages.open_waze') }}
                                </a>
                            </div>
                        </div>
                        <button type="button" wire:click="clearCenter"
                            class="shrink-0 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">✕</button>
                    </div>
                    <ul class="liqahi-scroll flex-1 divide-y divide-zinc-100 overflow-y-auto text-sm dark:divide-white/5">
                        @foreach($center->items as $item)
                            <li class="flex items-center justify-between gap-2 px-4 py-2">
                                <span class="truncate">{{ $item->localizedName() }}</span>
                                @if($item->pivot->is_available)
                                    <span class="shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-medium text-green-800 dark:bg-green-500/10 dark:text-green-300">
                                        {{ __('messages.available') }}
                                    </span>
                                @else
                                    <span class="shrink-0 rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-medium text-zinc-600 dark:bg-white/5 dark:text-zinc-400">
                                        {{ __('messages.unavailable') }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @elseif($needsLocation)
                <div class="flex flex-col items-center justify-center gap-3 rounded-md border border-dashed border-amber-300 bg-amber-50 p-6 text-center text-sm text-amber-800 lg:h-[480px] dark:border-amber-500/30 dark:bg-amber-500/5 dark:text-amber-300">
                    <p>{{ __('messages.need_location') }}</p>
                    <button type="button" x-on:click="useMyLocation()"
                        class="rounded-md bg-amber-500 px-3 py-2 text-xs font-medium text-white hover:bg-amber-600">
                        📍 {{ __('messages.use_my_location') }}
                    </button>
                </div>
            @elseif($itemId && $latitude && $longitude)
                @if($this->results->isEmpty())
                    <div class="flex items-center justify-center rounded-md border border-dashed border-zinc-300 p-6 text-center text-sm text-zinc-500 lg:h-[480px] dark:border-white/10">
                        {{ __('messages.no_results') }}
                    </div>
                @else
                    <ul class="liqahi-scroll max-h-[420px] space-y-3 overflow-y-auto pe-1 lg:h-[480px] lg:max-h-[480px]">
                        @foreach($this->results as $center)
                            <li
                                wire:key="center-{{ $center->id }}"
                                x-on:click="focusMarker({{ $center->id }})"
                                class="cursor-pointer rounded-lg border border-zinc-200 bg-white p-4 shadow-sm hover:border-amber-400 dark:border-white/10 dark:bg-zinc-900"
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="font-semibold">{{ $center->localizedName() }}</h3>
                                        <p class="text-sm text-zinc-500">{{ app()->getLocale() === 'ar' ? $center->address_ar : $center->address_en }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                                        {{ number_format($center->distance_km, 1) }} {{ __('messages.distance_km') }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @else
                <div class="hidden items-center justify-center rounded-md border border-dashed border-zinc-300 p-6 text-center text-sm text-zinc-500 lg:flex lg:h-[480px] dark:border-white/10">
                    {{ __('messages.tagline') }}
                </div>
            @endif
        </div>

        <div class="order-1 lg:order-2 lg:col-span-3">
            <div
                wire:ignore
                x-ref="map"
                id="liqahi-map"
                class="h-[360px] w-full rounded-lg border border-zinc-200 lg:h-[480px] dark:border-white/10"
                data-centers="{{ json_encode($this->allCenters->map(fn ($c) => [
                    'id' => $c->id,
                    'name' => $c->localizedName(),
                    'lat' => (float) $c->latitude,
                    'lng' => (float) $c->longitude,
                ])->values()) }}"
                data-matching="{{ json_encode($this->results->pluck('id')->values()) }}"
            ></div>
        </div>
    </section>

    @script
    <script>
        Alpine.data('liqahiSearch', ({ lat, lng }) => ({
            map: null,
            userMarker: null,
            markers: {},
            lastDataKey: '',
            lastUserKey: '',
            isDark: () => document.documentElement.classList.contains('dark'),
            toggleTheme() {
                const dark = !this.isDark();
                document.documentElement.classList.toggle('dark', dark);
                localStorage.setItem('theme', dark ? 'dark' : 'light');
                this.applyTileLayer();
            },
            init() {
                this.$nextTick(() => this.buildMap());
                Livewire.hook('morph.updated', () => this.refreshMarkers());
            },
            useMyLocation() {
                if (!navigator.geolocation) {
                    alert('{{ __('messages.geolocation_denied') }}');
                    return;
                }
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.$wire.setCoords(pos.coords.latitude, pos.coords.longitude);
                    },
                    () => alert('{{ __('messages.geolocation_denied') }}'),
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            },
            buildMap() {
                const el = this.$refs.map;
                if (!el || typeof L === 'undefined') {
                    setTimeout(() => this.buildMap(), 200);
                    return;
                }
                const startLat = this.$wire.latitude ?? 33.3152;
                const startLng = this.$wire.longitude ?? 44.3661;
                this.map = L.map(el).setView([startLat, startLng], 11);
                this.map.on('click', () => this.$wire.clearCenter());
                this.applyTileLayer();
                this.addLocateControl();
                this.refreshMarkers();
            },
            addLocateControl() {
                const self = this;
                const Locate = L.Control.extend({
                    options: { position: 'bottomright' },
                    onAdd: function () {
                        const btn = L.DomUtil.create('button', 'liqahi-locate-btn');
                        btn.type = 'button';
                        btn.title = '{{ __('messages.use_my_location') }}';
                        btn.setAttribute('aria-label', '{{ __('messages.use_my_location') }}');
                        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px"><circle cx="12" cy="12" r="3"/><path d="M12 2v3"/><path d="M12 19v3"/><path d="M2 12h3"/><path d="M19 12h3"/></svg>`;
                        L.DomEvent.disableClickPropagation(btn);
                        L.DomEvent.on(btn, 'click', (e) => {
                            L.DomEvent.stop(e);
                            self.useMyLocation();
                        });
                        return btn;
                    },
                });
                new Locate().addTo(this.map);
            },
            applyTileLayer() {
                if (!this.map) return;
                this.map.eachLayer((layer) => { if (layer instanceof L.TileLayer) this.map.removeLayer(layer); });
                const url = this.isDark()
                    ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                    : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                L.tileLayer(url, { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(this.map);
            },
            buildingIcon(color) {
                const svg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="white" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>`;
                return L.divIcon({
                    className: 'liqahi-marker',
                    html: `<div style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:9999px;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,.4);background:${color}">${svg}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                });
            },
            userIcon() {
                return L.divIcon({
                    className: 'liqahi-user-marker',
                    html: `<div style="box-sizing:border-box;width:18px;height:18px;border-radius:9999px;border:3px solid white;box-shadow:0 1px 4px rgba(0,0,0,.5);background:#3b82f6"></div>`,
                    iconSize: [18, 18],
                    iconAnchor: [9, 9],
                });
            },
            refreshMarkers() {
                if (!this.map) return;

                const el = this.$refs.map;
                const centersRaw = el.dataset.centers || '[]';
                const matchingRaw = el.dataset.matching || '[]';
                const dataKey = centersRaw + '|' + matchingRaw;
                const lat = this.$wire.latitude;
                const lng = this.$wire.longitude;
                const userKey = (lat ?? '') + ',' + (lng ?? '');

                const userChanged = userKey !== this.lastUserKey;
                const dataChanged = dataKey !== this.lastDataKey;

                if (userChanged) {
                    if (this.userMarker) this.map.removeLayer(this.userMarker);
                    this.userMarker = null;
                    if (lat && lng) {
                        this.userMarker = L.marker([lat, lng], { icon: this.userIcon(), interactive: false }).addTo(this.map);
                    }
                    this.lastUserKey = userKey;
                }

                if (!dataChanged) return;

                Object.values(this.markers).forEach((m) => this.map.removeLayer(m));
                this.markers = {};

                const centers = JSON.parse(centersRaw);
                const matching = new Set(JSON.parse(matchingRaw));
                const hasSearch = matching.size > 0;
                const bounds = [];

                if (lat && lng) bounds.push([lat, lng]);

                centers.forEach((c) => {
                    const isMatch = matching.has(c.id);
                    const color = isMatch ? '#10b981' : (hasSearch ? '#71717a' : '#f59e0b');
                    const m = L.marker([c.lat, c.lng], { icon: this.buildingIcon(color) })
                        .addTo(this.map);
                    m.on('click', (e) => {
                        L.DomEvent.stopPropagation(e);
                        this.$wire.selectCenter(c.id);
                    });
                    this.markers[c.id] = m;
                    if (!hasSearch || isMatch) bounds.push([c.lat, c.lng]);
                });

                if (bounds.length > 0) {
                    this.map.flyToBounds(bounds, { padding: [40, 40], maxZoom: 13, duration: 0.8 });
                }

                this.lastDataKey = dataKey;
            },
            focusMarker(id) {
                const m = this.markers[id];
                if (!m) return;
                this.map.flyTo(m.getLatLng(), 15, { animate: true, duration: 1.0, easeLinearity: 0.25 });
            },
        }));
    </script>
    @endscript
</div>
