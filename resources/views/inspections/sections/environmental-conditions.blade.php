<!-- Environmental Conditions Section -->
<section class="form-section" id="section-environmental">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-cloud-sun"></i>
            Environmental Conditions
        </h2>
        <p class="section-description">
            Document environmental factors that may affect lifting operations and equipment performance.
        </p>
    </div>

    <div class="section-content">
        <div class="row g-4">
            <!-- Weather Conditions -->
            <div class="col-md-6">
                <label for="weatherConditions" class="form-label">
                    <i class="fas fa-sun me-2"></i>Weather Conditions *
                </label>
                <select class="form-select @error('weather_conditions') is-invalid @enderror" 
                        id="weatherConditions" name="weather_conditions" required>
                    <option value="">Select weather conditions</option>
                    <option value="Clear/Sunny" {{ old('weather_conditions') == 'Clear/Sunny' ? 'selected' : '' }}>Clear/Sunny</option>
                    <option value="Partly Cloudy" {{ old('weather_conditions') == 'Partly Cloudy' ? 'selected' : '' }}>Partly Cloudy</option>
                    <option value="Overcast" {{ old('weather_conditions') == 'Overcast' ? 'selected' : '' }}>Overcast</option>
                    <option value="Light Rain" {{ old('weather_conditions') == 'Light Rain' ? 'selected' : '' }}>Light Rain</option>
                    <option value="Heavy Rain" {{ old('weather_conditions') == 'Heavy Rain' ? 'selected' : '' }}>Heavy Rain</option>
                    <option value="Fog/Mist" {{ old('weather_conditions') == 'Fog/Mist' ? 'selected' : '' }}>Fog/Mist</option>
                    <option value="Snow" {{ old('weather_conditions') == 'Snow' ? 'selected' : '' }}>Snow</option>
                    <option value="Storm" {{ old('weather_conditions') == 'Storm' ? 'selected' : '' }}>Storm</option>
                </select>
                @error('weather_conditions')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Current weather conditions at time of inspection</small>
            </div>

            <!-- Temperature -->
            <div class="col-md-6">
                <label for="temperature" class="form-label">
                    <i class="fas fa-thermometer-half me-2"></i>Temperature (°C)
                </label>
                <input type="number" class="form-control @error('temperature') is-invalid @enderror" 
                       id="temperature" name="temperature" 
                       value="{{ old('temperature') }}" 
                       placeholder="Enter temperature" step="0.1" min="-50" max="60">
                @error('temperature')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Ambient temperature in Celsius</small>
            </div>

            <!-- Wind Speed -->
            <div class="col-md-6">
                <label for="windSpeed" class="form-label">
                    <i class="fas fa-wind me-2"></i>Wind Speed (m/s)
                </label>
                <input type="number" class="form-control @error('wind_speed') is-invalid @enderror" 
                       id="windSpeed" name="wind_speed" 
                       value="{{ old('wind_speed') }}" 
                       placeholder="Enter wind speed" step="0.1" min="0" max="50">
                @error('wind_speed')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Wind speed in meters per second</small>
            </div>

            <!-- Wind Direction -->
            <div class="col-md-6">
                <label for="windDirection" class="form-label">
                    <i class="fas fa-compass me-2"></i>Wind Direction
                </label>
                <select class="form-select" id="windDirection" name="wind_direction">
                    <option value="">Select wind direction</option>
                    <option value="N" {{ old('wind_direction') == 'N' ? 'selected' : '' }}>North (N)</option>
                    <option value="NE" {{ old('wind_direction') == 'NE' ? 'selected' : '' }}>Northeast (NE)</option>
                    <option value="E" {{ old('wind_direction') == 'E' ? 'selected' : '' }}>East (E)</option>
                    <option value="SE" {{ old('wind_direction') == 'SE' ? 'selected' : '' }}>Southeast (SE)</option>
                    <option value="S" {{ old('wind_direction') == 'S' ? 'selected' : '' }}>South (S)</option>
                    <option value="SW" {{ old('wind_direction') == 'SW' ? 'selected' : '' }}>Southwest (SW)</option>
                    <option value="W" {{ old('wind_direction') == 'W' ? 'selected' : '' }}>West (W)</option>
                    <option value="NW" {{ old('wind_direction') == 'NW' ? 'selected' : '' }}>Northwest (NW)</option>
                    <option value="Variable" {{ old('wind_direction') == 'Variable' ? 'selected' : '' }}>Variable</option>
                </select>
                <small class="form-text text-muted">Primary wind direction during inspection</small>
            </div>

            <!-- Visibility -->
            <div class="col-md-6">
                <label for="visibility" class="form-label">
                    <i class="fas fa-eye me-2"></i>Visibility
                </label>
                <select class="form-select" id="visibility" name="visibility">
                    <option value="">Select visibility</option>
                    <option value="Excellent (>10km)" {{ old('visibility') == 'Excellent (>10km)' ? 'selected' : '' }}>Excellent (>10km)</option>
                    <option value="Good (5-10km)" {{ old('visibility') == 'Good (5-10km)' ? 'selected' : '' }}>Good (5-10km)</option>
                    <option value="Moderate (1-5km)" {{ old('visibility') == 'Moderate (1-5km)' ? 'selected' : '' }}>Moderate (1-5km)</option>
                    <option value="Poor (0.5-1km)" {{ old('visibility') == 'Poor (0.5-1km)' ? 'selected' : '' }}>Poor (0.5-1km)</option>
                    <option value="Very Poor (<0.5km)" {{ old('visibility') == 'Very Poor (<0.5km)' ? 'selected' : '' }}>Very Poor (<0.5km)</option>
                </select>
                <small class="form-text text-muted">Visibility conditions affecting operations</small>
            </div>

            <!-- Sea State (for offshore operations) -->
            <div class="col-md-6">
                <label for="seaState" class="form-label">
                    <i class="fas fa-water me-2"></i>Sea State (if applicable)
                </label>
                <select class="form-select @error('sea_state') is-invalid @enderror" 
                        id="seaState" name="sea_state">
                    <option value="">Select sea state (if offshore)</option>
                    <option value="Calm (0-0.1m)" {{ old('sea_state') == 'Calm (0-0.1m)' ? 'selected' : '' }}>Calm (0-0.1m)</option>
                    <option value="Slight (0.1-0.5m)" {{ old('sea_state') == 'Slight (0.1-0.5m)' ? 'selected' : '' }}>Slight (0.1-0.5m)</option>
                    <option value="Moderate (0.5-1.25m)" {{ old('sea_state') == 'Moderate (0.5-1.25m)' ? 'selected' : '' }}>Moderate (0.5-1.25m)</option>
                    <option value="Rough (1.25-2.5m)" {{ old('sea_state') == 'Rough (1.25-2.5m)' ? 'selected' : '' }}>Rough (1.25-2.5m)</option>
                    <option value="Very Rough (2.5-4m)" {{ old('sea_state') == 'Very Rough (2.5-4m)' ? 'selected' : '' }}>Very Rough (2.5-4m)</option>
                    <option value="High (4-6m)" {{ old('sea_state') == 'High (4-6m)' ? 'selected' : '' }}>High (4-6m)</option>
                    <option value="Very High (6-9m)" {{ old('sea_state') == 'Very High (6-9m)' ? 'selected' : '' }}>Very High (6-9m)</option>
                    <option value="N/A - Onshore" {{ old('sea_state') == 'N/A - Onshore' ? 'selected' : '' }}>N/A - Onshore</option>
                </select>
                @error('sea_state')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Sea conditions for offshore operations</small>
            </div>

            <!-- Environmental Notes -->
            <div class="col-12">
                <label for="environmentalNotes" class="form-label">
                    <i class="fas fa-sticky-note me-2"></i>Environmental Notes
                </label>
                <textarea class="form-control" id="environmentalNotes" name="environmental_notes" 
                          rows="3" placeholder="Additional environmental observations or concerns">{{ old('environmental_notes') }}</textarea>
                <small class="form-text text-muted">Any additional environmental factors affecting the operation</small>
            </div>
        </div>
    </div>
</section>
