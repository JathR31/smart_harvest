# Machine Learning API Setup Guide

## Overview
SmartHarvest uses a separate Python Flask API server for machine learning predictions and forecasting. This guide explains how to set up, run, and test the ML API connection.

## Architecture
```
Laravel Application (Port 8000/80)
         ↓ HTTP Requests
ML API Server (Port 5000)
         ↓ Returns
    Predictions & Forecasts
```

## Prerequisites
- Python 3.8 or higher
- pip (Python package manager)
- Laravel application running

## Installation

### 1. Install Python Dependencies
```bash
# Navigate to the project directory
cd c:\xampp\htdocs\dashboard\SmartHarvest

# Install required packages
pip install -r requirements.txt
```

### 2. Verify Environment Configuration
Check your `.env` file contains:
```env
ML_API_URL=http://127.0.0.1:5000
ML_API_TIMEOUT=30
ML_API_RETRY_TIMES=2
```

## Running the ML API Server

### Start the Server
```bash
# From the project root directory
python ml_api_server.py
```

You should see:
```
Starting SmartHarvest ML API Server...
Server running on http://127.0.0.1:5000
 * Running on http://127.0.0.1:5000
```

### Keep the Server Running
The ML API server needs to stay running while using SmartHarvest. Open a separate terminal/command prompt to keep it running in the background.

## Testing the Connection

### Method 1: Web Interface (Recommended)
1. Start the ML API server (see above)
2. Open your browser
3. Navigate to: `http://localhost/dashboard/SmartHarvest/public/ml-test`
4. Click "Run All Tests" to test all endpoints

### Method 2: API Endpoints
Test individual endpoints using curl or your browser:

**Health Check:**
```bash
curl http://localhost/dashboard/SmartHarvest/public/api/ml/test
```

**Prediction Test:**
```bash
curl http://localhost/dashboard/SmartHarvest/public/api/ml/test-prediction
```

**Direct ML API Health:**
```bash
curl http://127.0.0.1:5000/health
```

### Method 3: Laravel Tinker
```bash
php artisan tinker

# Test the service
$ml = new \App\Services\MLApiService();
$ml->checkHealth();
```

## ML API Endpoints

### 1. Health Check
**Endpoint:** `GET /health`

**Response:**
```json
{
  "status": "healthy",
  "service": "SmartHarvest ML API",
  "version": "1.0.0",
  "timestamp": "2025-11-15T14:30:00",
  "model_loaded": true
}
```

### 2. Crop Yield Prediction
**Endpoint:** `POST /api/predict`

**Request Body:**
```json
{
  "municipality": "La Trinidad",
  "crop_type": "Cabbage",
  "area_planted": 2.5,
  "month": 5,
  "year": 2025
}
```

**Response:**
```json
{
  "status": "success",
  "prediction": {
    "predicted_yield_per_ha": 18.5,
    "total_predicted_yield": 46.25,
    "confidence": 0.92,
    "factors": {
      "seasonal_impact": 1.1,
      "area_planted": 2.5
    }
  }
}
```

### 3. Crop Yield Forecast
**Endpoint:** `POST /api/forecast`

**Request Body:**
```json
{
  "municipality": "La Trinidad",
  "crop_type": "Cabbage",
  "periods": 12
}
```

**Response:**
```json
{
  "status": "success",
  "forecast": {
    "municipality": "La Trinidad",
    "crop_type": "Cabbage",
    "forecast_periods": 12,
    "forecasts": [
      {
        "period": 1,
        "predicted_value": 18.5,
        "lower_bound": 16.5,
        "upper_bound": 20.5
      }
    ]
  }
}
```

### 4. List Available Models
**Endpoint:** `GET /api/models`

**Response:**
```json
{
  "models": [
    {
      "name": "Random Forest Regressor",
      "type": "yield_prediction",
      "status": "active",
      "accuracy": 0.92
    },
    {
      "name": "LSTM Time Series",
      "type": "forecasting",
      "status": "active",
      "accuracy": 0.89
    }
  ],
  "total_models": 2
}
```

## Configuration

### Laravel Configuration
File: `config/ml.php`

```php
return [
    'api_url' => env('ML_API_URL', 'http://127.0.0.1:5000'),
    'endpoints' => [
        'health' => '/health',
        'predict' => '/api/predict',
        'forecast' => '/api/forecast',
    ],
    'timeout' => env('ML_API_TIMEOUT', 30),
    'retry_times' => env('ML_API_RETRY_TIMES', 2),
];
```

### Environment Variables
Add to `.env`:
```env
# Machine Learning API
ML_API_URL=http://127.0.0.1:5000
ML_API_TIMEOUT=30
ML_API_RETRY_TIMES=2
```

## Using the ML Service in Laravel

### Basic Usage
```php
use App\Services\MLApiService;

$mlService = new MLApiService();

// Check health
$health = $mlService->checkHealth();

// Make prediction
$prediction = $mlService->predict([
    'municipality' => 'La Trinidad',
    'crop_type' => 'Cabbage',
    'area_planted' => 2.5,
    'month' => 5,
    'year' => 2025
]);

// Get forecast
$forecast = $mlService->forecast([
    'municipality' => 'La Trinidad',
    'crop_type' => 'Cabbage',
    'periods' => 12
]);
```

### In Routes
```php
Route::get('/predict-yield', function (Request $request) {
    $mlService = new \App\Services\MLApiService();
    
    $result = $mlService->predict([
        'municipality' => $request->municipality,
        'crop_type' => $request->crop_type,
        'area_planted' => $request->area,
        'month' => now()->month,
        'year' => now()->year,
    ]);
    
    return response()->json($result);
});
```

### In Controllers
```php
namespace App\Http\Controllers;

use App\Services\MLApiService;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    protected $mlService;
    
    public function __construct(MLApiService $mlService)
    {
        $this->mlService = $mlService;
    }
    
    public function predict(Request $request)
    {
        $validated = $request->validate([
            'municipality' => 'required|string',
            'crop_type' => 'required|string',
            'area_planted' => 'required|numeric|min:0',
        ]);
        
        $result = $this->mlService->predict($validated);
        
        return response()->json($result);
    }
}
```

## Troubleshooting

### Connection Refused
**Problem:** `Cannot connect to ML API service`

**Solutions:**
1. Make sure the ML API server is running: `python ml_api_server.py`
2. Check if port 5000 is available: `netstat -ano | findstr :5000`
3. Verify ML_API_URL in `.env` is correct
4. Check firewall settings

### 404 Not Found
**Problem:** `HTTP request returned status code 404`

**Solutions:**
1. Verify the ML API server is running
2. Check endpoint paths in `config/ml.php`
3. Test direct access: `curl http://127.0.0.1:5000/health`

### Timeout Errors
**Problem:** Request times out

**Solutions:**
1. Increase timeout in `.env`: `ML_API_TIMEOUT=60`
2. Check if ML API server is overloaded
3. Verify network connectivity

### Import Errors (Python)
**Problem:** `ModuleNotFoundError`

**Solutions:**
```bash
# Reinstall dependencies
pip install -r requirements.txt

# Or install individually
pip install flask flask-cors numpy
```

## Development vs Production

### Development
- Run ML API server manually: `python ml_api_server.py`
- Debug mode enabled
- Runs on localhost

### Production
- Use production WSGI server (e.g., Gunicorn, uWSGI)
- Deploy on separate server/container
- Use process manager (e.g., systemd, supervisor)
- Enable HTTPS
- Set up monitoring and logging

### Production Example (Gunicorn)
```bash
# Install gunicorn
pip install gunicorn

# Run with gunicorn
gunicorn -w 4 -b 127.0.0.1:5000 ml_api_server:app
```

## Testing Checklist

✅ ML API server starts without errors  
✅ Health check returns "healthy" status  
✅ Prediction endpoint returns valid predictions  
✅ Forecast endpoint returns time series data  
✅ Laravel can connect to ML API  
✅ Error handling works (server offline scenario)  
✅ Response times are acceptable  

## Monitoring

### Check ML API Status
```bash
# Health check
curl http://127.0.0.1:5000/health

# List models
curl http://127.0.0.1:5000/api/models
```

### Laravel Logs
Check `storage/logs/laravel.log` for ML API errors:
```bash
tail -f storage/logs/laravel.log | grep "ML API"
```

## Support

For issues or questions:
1. Check this documentation
2. Review Laravel logs: `storage/logs/laravel.log`
3. Review ML API console output
4. Visit the test page: `/ml-test`

## Version History

- **v1.0.0** (November 2025)
  - Initial ML API implementation
  - Health check endpoint
  - Prediction and forecast capabilities
  - Laravel integration service

---

**Last Updated:** November 15, 2025
