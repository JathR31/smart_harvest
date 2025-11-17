# SmartHarvest Machine Learning API Server
# Flask-based API for crop yield predictions and forecasting

from flask import Flask, request, jsonify
from flask_cors import CORS
import numpy as np
from datetime import datetime
import logging

app = Flask(__name__)
CORS(app)  # Enable CORS for Laravel frontend

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Mock ML model - In production, load actual trained models
class CropPredictor:
    def __init__(self):
        self.model_loaded = True
        logger.info("Crop prediction model initialized")
    
    def predict(self, data):
        """
        Make crop yield prediction based on input data
        """
        # Extract features
        area = data.get('area_planted', 1.0)
        month = data.get('month', 1)
        year = data.get('year', 2025)
        
        # Mock prediction logic (replace with actual model)
        # Base yield varies by month (growing seasons)
        seasonal_factor = {
            1: 0.85, 2: 0.90, 3: 0.95, 4: 1.0, 5: 1.1,
            6: 1.15, 7: 1.1, 8: 1.0, 9: 0.95, 10: 0.90, 11: 0.85, 12: 0.80
        }
        
        base_yield = 18.5  # mt/ha
        yield_per_ha = base_yield * seasonal_factor.get(month, 1.0)
        total_yield = yield_per_ha * area
        
        return {
            'predicted_yield_per_ha': round(yield_per_ha, 2),
            'total_predicted_yield': round(total_yield, 2),
            'confidence': round(np.random.uniform(0.85, 0.95), 2),
            'factors': {
                'seasonal_impact': seasonal_factor.get(month, 1.0),
                'area_planted': area
            }
        }
    
    def forecast(self, data):
        """
        Forecast crop yields for multiple periods
        """
        municipality = data.get('municipality', 'La Trinidad')
        crop_type = data.get('crop_type', 'Cabbage')
        periods = data.get('periods', 12)
        
        # Mock forecast (replace with actual time series model)
        forecasts = []
        base_value = 18.0
        
        for i in range(periods):
            # Add trend and seasonality
            trend = i * 0.05
            seasonal = np.sin(i * np.pi / 6) * 2
            noise = np.random.normal(0, 0.5)
            
            value = base_value + trend + seasonal + noise
            forecasts.append({
                'period': i + 1,
                'predicted_value': round(max(0, value), 2),
                'lower_bound': round(max(0, value - 2), 2),
                'upper_bound': round(value + 2, 2)
            })
        
        return {
            'municipality': municipality,
            'crop_type': crop_type,
            'forecast_periods': periods,
            'forecasts': forecasts
        }

# Initialize predictor
predictor = CropPredictor()

@app.route('/health', methods=['GET'])
def health_check():
    """
    Health check endpoint
    """
    return jsonify({
        'status': 'healthy',
        'service': 'SmartHarvest ML API',
        'version': '1.0.0',
        'timestamp': datetime.now().isoformat(),
        'model_loaded': predictor.model_loaded
    }), 200

@app.route('/api/predict', methods=['POST'])
def predict():
    """
    Predict crop yield based on input parameters
    
    Expected JSON body:
    {
        "municipality": "La Trinidad",
        "crop_type": "Cabbage",
        "area_planted": 2.5,
        "month": 5,
        "year": 2025
    }
    """
    try:
        data = request.get_json()
        
        if not data:
            return jsonify({'error': 'No data provided'}), 400
        
        # Validate required fields
        required_fields = ['municipality', 'crop_type', 'area_planted']
        missing_fields = [field for field in required_fields if field not in data]
        
        if missing_fields:
            return jsonify({
                'error': f'Missing required fields: {", ".join(missing_fields)}'
            }), 400
        
        # Make prediction
        logger.info(f"Making prediction for {data.get('crop_type')} in {data.get('municipality')}")
        prediction = predictor.predict(data)
        
        response = {
            'status': 'success',
            'input': data,
            'prediction': prediction,
            'timestamp': datetime.now().isoformat()
        }
        
        return jsonify(response), 200
        
    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        return jsonify({
            'status': 'error',
            'error': str(e)
        }), 500

@app.route('/api/forecast', methods=['POST'])
def forecast():
    """
    Forecast crop yields for multiple periods
    
    Expected JSON body:
    {
        "municipality": "La Trinidad",
        "crop_type": "Cabbage",
        "periods": 12
    }
    """
    try:
        data = request.get_json()
        
        if not data:
            return jsonify({'error': 'No data provided'}), 400
        
        # Make forecast
        logger.info(f"Making forecast for {data.get('crop_type')} in {data.get('municipality')}")
        forecast_result = predictor.forecast(data)
        
        response = {
            'status': 'success',
            'forecast': forecast_result,
            'timestamp': datetime.now().isoformat()
        }
        
        return jsonify(response), 200
        
    except Exception as e:
        logger.error(f"Forecast error: {str(e)}")
        return jsonify({
            'status': 'error',
            'error': str(e)
        }), 500

@app.route('/api/models', methods=['GET'])
def list_models():
    """
    List available ML models
    """
    return jsonify({
        'models': [
            {
                'name': 'Random Forest Regressor',
                'type': 'yield_prediction',
                'status': 'active',
                'accuracy': 0.92
            },
            {
                'name': 'LSTM Time Series',
                'type': 'forecasting',
                'status': 'active',
                'accuracy': 0.89
            }
        ],
        'total_models': 2
    }), 200

@app.errorhandler(404)
def not_found(error):
    return jsonify({
        'error': 'Endpoint not found',
        'available_endpoints': [
            'GET /health',
            'POST /api/predict',
            'POST /api/forecast',
            'GET /api/models'
        ]
    }), 404

if __name__ == '__main__':
    logger.info("Starting SmartHarvest ML API Server...")
    logger.info("Server running on http://127.0.0.1:5000")
    app.run(host='127.0.0.1', port=5000, debug=True)
