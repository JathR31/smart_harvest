# SmartHarvest API Documentation

## Base URL
`http://127.0.0.1:8000`

---

## Authentication
All endpoints require authentication. Users must be logged in to access these APIs.

---

## Endpoints

### 1. Dashboard Statistics
**Endpoint:** `GET /api/dashboard/stats`

**Description:** Get user's crop statistics and harvest projections

**Response:**
```json
{
  "stats": {
    "expected_harvest": "125.5",
    "percentage_change": 12.5
  },
  "recent_harvests": [
    {
      "id": 1,
      "crop_type": "Cabbage",
      "variety": "Scorpio",
      "municipality": "La Trinidad",
      "year": 2025,
      "area_planted": 2.5,
      "yield_amount": 45.5
    }
  ]
}
```

---

### 2. Yield Statistics
**Endpoint:** `GET /api/yield/stats`

**Query Parameters:**
- `municipality` (required): Municipality name (e.g., "La Trinidad")
- `year` (required): Year (e.g., 2025)

**Example:** `/api/yield/stats?municipality=La Trinidad&year=2025`

**Response:**
```json
{
  "avg_yield": "18.2",
  "best_crop": {
    "crop_type": "Cabbage",
    "avg_yield": 18.5
  },
  "total_production": "29875",
  "total_area": "1620"
}
```

---

### 3. Yield Comparison (Multi-Year)
**Endpoint:** `GET /api/yield/comparison`

**Query Parameters:**
- `municipality` (required): Municipality name

**Example:** `/api/yield/comparison?municipality=La Trinidad`

**Response:**
```json
[
  {
    "year": 2020,
    "avg_yield": "16.5"
  },
  {
    "year": 2021,
    "avg_yield": "17.2"
  },
  {
    "year": 2022,
    "avg_yield": "17.8"
  }
]
```

---

### 4. Crop Performance Rankings
**Endpoint:** `GET /api/yield/crops`

**Query Parameters:**
- `municipality` (required): Municipality name
- `year` (required): Year

**Example:** `/api/yield/crops?municipality=La Trinidad&year=2025`

**Response:**
```json
[
  {
    "crop_type": "Cabbage",
    "avg_yield": "18.5"
  },
  {
    "crop_type": "Carrot",
    "avg_yield": "16.2"
  },
  {
    "crop_type": "Potato",
    "avg_yield": "15.8"
  }
]
```

---

### 5. Monthly Yield Patterns
**Endpoint:** `GET /api/yield/monthly`

**Query Parameters:**
- `municipality` (required): Municipality name
- `year` (required): Year

**Example:** `/api/yield/monthly?municipality=La Trinidad&year=2025`

**Response:**
```json
[
  {
    "month": 1,
    "month_name": "January",
    "avg_yield": "17.2"
  },
  {
    "month": 2,
    "month_name": "February",
    "avg_yield": "18.1"
  }
]
```

---

### 6. Planting Schedule Recommendations
**Endpoint:** `GET /api/planting/schedule`

**Query Parameters:**
- `municipality` (optional): Municipality name (defaults to user's records)

**Example:** `/api/planting/schedule?municipality=La Trinidad`

**Response:**
```json
[
  {
    "crop_type": "Cabbage",
    "variety": "Scorpio",
    "planting_month_start": 5,
    "planting_month_end": 6,
    "harvest_month_start": 8,
    "harvest_month_end": 9,
    "duration": 90,
    "avg_temp": "17.5",
    "avg_rainfall": "250.5",
    "avg_yield": "18.5"
  }
]
```

**Notes:**
- Limited to top 10 crops by average yield
- Duration calculated from average planting to harvest

---

### 7. Optimal Planting Recommendation
**Endpoint:** `GET /api/planting/optimal`

**Query Parameters:**
- `municipality` (optional): Municipality name

**Example:** `/api/planting/optimal?municipality=La Trinidad`

**Response:**
```json
{
  "optimal_crop": {
    "crop_type": "Cabbage",
    "variety": "Scorpio",
    "avg_yield": 18.5,
    "latest_harvest": "2024-09-15"
  },
  "next_planting_date": "2025-05-15",
  "confidence": "High"
}
```

**Business Logic:**
- Selects crop with highest average yield
- Next planting date = 90 days after latest harvest
- Confidence based on data availability

---

### 8. Current Climate Data
**Endpoint:** `GET /api/climate/current`

**Query Parameters:**
- `municipality` (required): Municipality name

**Example:** `/api/climate/current?municipality=La Trinidad`

**Response:**
```json
{
  "current": {
    "municipality": "La Trinidad",
    "year": 2025,
    "month": 11,
    "avg_temperature": "17.2",
    "min_temperature": "15.5",
    "max_temperature": "19.0",
    "rainfall": "125.5",
    "humidity": "78.5",
    "wind_speed": "8.2",
    "weather_condition": "Rainy"
  },
  "historical_avg": {
    "avg_temperature": "17.5",
    "rainfall": "135.2",
    "humidity": "79.0"
  }
}
```

**Notes:**
- Current = latest month/year in database
- Historical average = 6-year average for current month

---

### 9. Municipalities List
**Endpoint:** `GET /api/municipalities`

**Description:** Get list of all 14 Benguet municipalities

**Response:**
```json
[
  "Atok",
  "Baguio City",
  "Bakun",
  "Bokod",
  "Buguias",
  "Itogon",
  "Kabayan",
  "Kapangan",
  "Kibungan",
  "La Trinidad",
  "Mankayan",
  "Sablan",
  "Tuba",
  "Tublay"
]
```

---

## Common Response Codes

- **200 OK**: Request successful
- **401 Unauthorized**: User not authenticated
- **404 Not Found**: No data found for query parameters
- **500 Internal Server Error**: Server error

---

## Data Types

### Crop Status
- `planting` - Crop being planted
- `growing` - Crop in growth phase
- `harvested` - Crop harvested
- `failed` - Crop failed

### Validation Status
- `pending` - Awaiting validation
- `approved` - Validated and approved
- `flagged` - Flagged for review

### Weather Conditions
- `Sunny` - Clear sunny weather
- `Rainy` - Rainy conditions
- `Cloudy` - Overcast/cloudy
- `Partly Cloudy` - Mixed conditions

---

## Sample Usage (JavaScript)

### Fetch Dashboard Stats
```javascript
async function loadDashboard() {
  const response = await fetch('/api/dashboard/stats');
  const data = await response.json();
  console.log('Expected Harvest:', data.stats.expected_harvest);
  console.log('Recent Harvests:', data.recent_harvests);
}
```

### Fetch Yield Analysis
```javascript
async function loadYieldAnalysis(municipality, year) {
  const url = `/api/yield/stats?municipality=${encodeURIComponent(municipality)}&year=${year}`;
  const response = await fetch(url);
  const data = await response.json();
  console.log('Average Yield:', data.avg_yield);
  console.log('Best Crop:', data.best_crop.crop_type);
}
```

### Fetch Climate Data
```javascript
async function loadClimate(municipality) {
  const url = `/api/climate/current?municipality=${encodeURIComponent(municipality)}`;
  const response = await fetch(url);
  const data = await response.json();
  console.log('Current Weather:', data.current.weather_condition);
  console.log('Temperature:', data.current.avg_temperature + '°C');
  console.log('Rainfall:', data.current.rainfall + 'mm');
}
```

---

## Testing with cURL

### Dashboard Stats
```bash
curl -X GET "http://127.0.0.1:8000/api/dashboard/stats" \
  -H "Accept: application/json" \
  --cookie "laravel_session=YOUR_SESSION_COOKIE"
```

### Yield Statistics
```bash
curl -X GET "http://127.0.0.1:8000/api/yield/stats?municipality=La%20Trinidad&year=2025" \
  -H "Accept: application/json" \
  --cookie "laravel_session=YOUR_SESSION_COOKIE"
```

### Planting Schedule
```bash
curl -X GET "http://127.0.0.1:8000/api/planting/schedule?municipality=La%20Trinidad" \
  -H "Accept: application/json" \
  --cookie "laravel_session=YOUR_SESSION_COOKIE"
```

---

## Error Handling

### No Data Found
```json
{
  "stats": {
    "avg_yield": "0.0",
    "best_crop": null,
    "total_production": "0",
    "total_area": "0"
  }
}
```

### Invalid Municipality
Returns empty array `[]` or default values

---

## Database Schema Reference

### crop_data Table
- `id`: Primary key
- `user_id`: Foreign key to users
- `crop_type`: String (Cabbage, Carrot, Potato, Lettuce, Tomato)
- `variety`: String (specific variety name)
- `municipality`: String (14 Benguet municipalities)
- `area_planted`: Decimal (hectares)
- `yield_amount`: Decimal (metric tons)
- `planting_date`: Date
- `harvest_date`: Date (nullable)
- `status`: Enum (planting, growing, harvested, failed)
- `temperature`: Decimal (°C)
- `rainfall`: Decimal (mm)
- `humidity`: Decimal (%)
- `validation_status`: Enum (pending, approved, flagged)

### climate_patterns Table
- `id`: Primary key
- `municipality`: String
- `year`: Integer (2020-2025)
- `month`: Integer (1-12)
- `avg_temperature`: Decimal (°C)
- `min_temperature`: Decimal (°C)
- `max_temperature`: Decimal (°C)
- `rainfall`: Decimal (mm)
- `humidity`: Decimal (%)
- `wind_speed`: Decimal (km/h)
- `weather_condition`: String
- Unique constraint: (municipality, year, month)

---

## Performance Optimization

### Caching Recommendations
- Cache municipality lists (rarely changes)
- Cache historical averages (updated monthly)
- Use query result caching for expensive aggregations

### Database Indexing
Indexes already exist on:
- `crop_data.municipality`
- `crop_data.harvest_date`
- `climate_patterns(municipality, year, month)`

---

## Rate Limiting
Currently no rate limiting implemented. Recommended for production:
- 60 requests per minute per user
- 1000 requests per day per user

---

**Last Updated:** November 14, 2025
**Version:** 1.0
