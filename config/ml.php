<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Machine Learning API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the external Machine Learning API service
    | that provides crop prediction and forecasting capabilities.
    |
    */

    'api_url' => env('ML_API_URL', 'http://127.0.0.1:5000'),

    'endpoints' => [
        'health' => '/api/health',
        'model_info' => '/api/model-info',
        'predict' => '/api/predict',
        'batch_predict' => '/api/batch-predict',
        'forecast' => '/api/forecast',
        'top_crops' => '/api/top-crops',
        'dataset' => '/api/dataset',
        'dataset_stats' => '/api/dataset/stats',
        'available_options' => '/api/available-options',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Request Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time (in seconds) to wait for a response from the ML API
    |
    */

    'timeout' => env('ML_API_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Number of retry attempts if the API request fails
    |
    */

    'retry_times' => env('ML_API_RETRY_TIMES', 2),

];
