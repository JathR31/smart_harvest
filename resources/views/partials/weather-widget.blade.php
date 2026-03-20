<!-- PAGASA Weather Widget -->
<div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white" x-data="weatherWidget()">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
            </svg>
            Weather Forecast
        </h3>
        <a href="{{ route('pagasa.dashboard') }}" class="text-white hover:text-blue-100 text-sm underline">View Full Forecast</a>
    </div>

    <div x-show="loading" class="text-center py-4">
        <svg class="animate-spin h-8 w-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <div x-show="!loading">
        <!-- Soil Moisture for User's Municipality -->
        <div x-show="soilMoisture" class="mb-4 bg-white bg-opacity-20 rounded-lg p-4">
            <p class="text-sm opacity-90 mb-1">Soil Moisture - <span x-text="soilMoisture?.municipality"></span></p>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-sm font-semibold" 
                      :class="{
                          'bg-blue-200 text-blue-900': soilMoisture?.condition === 'wet',
                          'bg-green-200 text-green-900': soilMoisture?.condition === 'moist',
                          'bg-orange-200 text-orange-900': soilMoisture?.condition === 'dry'
                      }"
                      x-text="soilMoisture?.condition?.toUpperCase()">
                </span>
                <svg x-show="soilMoisture?.condition === 'wet'" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                </svg>
            </div>
        </div>

        <!-- Gale Warning Alert -->
        <div x-show="galeWarning" class="mb-4 bg-red-500 bg-opacity-90 rounded-lg p-4 border-2 border-red-300">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                </svg>
                <p class="font-semibold">Gale Warning Active in Your Area!</p>
            </div>
            <p class="text-sm mt-2 opacity-90">Please avoid fishing and secure your crops.</p>
        </div>

        <!-- ENSO Status -->
        <div x-show="enso" class="mb-4">
            <p class="text-sm opacity-90 mb-1">ENSO Status</p>
            <p class="font-bold text-lg" x-text="enso?.status?.replace('_', ' ')?.toUpperCase()"></p>
        </div>

        <!-- Latest Advisory -->
        <div x-show="advisories && advisories.length > 0" class="bg-white bg-opacity-20 rounded-lg p-4">
            <p class="text-sm font-semibold mb-2">Latest Advisory</p>
            <template x-for="advisory in advisories.slice(0, 1)" :key="advisory.id">
                <div>
                    <p class="text-sm font-medium" x-text="advisory.title"></p>
                    <p class="text-xs opacity-90 mt-1" x-text="advisory.description?.substring(0, 100) + '...'"></p>
                </div>
            </template>
        </div>

        <!-- View Full Forecast Link -->
        <div class="mt-4 text-center">
            <a href="{{ route('pagasa.dashboard') }}" class="inline-block px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-sm font-semibold transition-colors">
                View Detailed Forecast →
            </a>
        </div>
    </div>
</div>

<script>
function weatherWidget() {
    return {
        loading: true,
        soilMoisture: null,
        advisories: [],
        enso: null,
        galeWarning: false,
        
        init() {
            this.fetchWeatherData();
            // Refresh every 30 minutes
            setInterval(() => this.fetchWeatherData(), 30 * 60 * 1000);
        },
        
        async fetchWeatherData() {
            try {
                const response = await fetch('{{ url('/api/pagasa/widget') }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        this.soilMoisture = result.data.soil_moisture;
                        this.advisories = result.data.advisories || [];
                        this.enso = result.data.enso;
                        this.galeWarning = result.data.gale_warning;
                    }
                }
            } catch (error) {
                console.error('Error fetching weather data:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
