<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML API Connection Test - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Machine Learning API Connection Test</h1>
            
            <!-- Configuration Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Configuration</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">ML API URL:</p>
                        <p class="font-mono text-sm bg-gray-100 p-2 rounded">{{ env('ML_API_URL') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Timeout:</p>
                        <p class="font-mono text-sm bg-gray-100 p-2 rounded">{{ config('ml.timeout') }}s</p>
                    </div>
                </div>
            </div>

            <!-- Test Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Connection Tests</h2>
                <div class="space-y-3">
                    <button onclick="testHealth()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded transition">
                        Test Health Check
                    </button>
                    <button onclick="testPrediction()" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded transition">
                        Test Prediction API
                    </button>
                    <button onclick="testForecast()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded transition">
                        Test Forecast API
                    </button>
                    <button onclick="runAllTests()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded transition">
                        Run All Tests
                    </button>
                </div>
            </div>

            <!-- Results Display -->
            <div id="results" class="space-y-4">
                <!-- Results will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        async function testHealth() {
            addResult('Testing health check endpoint...', 'info');
            
            try {
                const response = await fetch('/api/ml/test');
                const data = await response.json();
                
                if (data.health_check.status === 'success') {
                    addResult('✓ Health Check Passed', 'success', data);
                } else {
                    addResult('✗ Health Check Failed', 'error', data);
                }
            } catch (error) {
                addResult('✗ Connection Error: ' + error.message, 'error');
            }
        }

        async function testPrediction() {
            addResult('Testing prediction endpoint...', 'info');
            
            try {
                const response = await fetch('/api/ml/test-prediction');
                const data = await response.json();
                
                if (data.prediction_result.status === 'success') {
                    addResult('✓ Prediction Test Passed', 'success', data);
                } else {
                    addResult('✗ Prediction Test Failed', 'error', data);
                }
            } catch (error) {
                addResult('✗ Prediction Error: ' + error.message, 'error');
            }
        }

        async function testForecast() {
            addResult('Testing forecast endpoint...', 'info');
            
            const testData = {
                municipality: 'La Trinidad',
                crop_type: 'Cabbage',
                periods: 6
            };

            try {
                const mlApiUrl = '{{ env("ML_API_URL") }}';
                const response = await fetch(mlApiUrl + '/api/forecast', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(testData)
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    addResult('✓ Forecast Test Passed', 'success', data);
                } else {
                    addResult('✗ Forecast Test Failed', 'error', data);
                }
            } catch (error) {
                addResult('✗ Forecast Error: ' + error.message, 'error', {
                    error: error.message,
                    note: 'Make sure the ML API server is running on port 5000'
                });
            }
        }

        async function runAllTests() {
            document.getElementById('results').innerHTML = '';
            await testHealth();
            await new Promise(resolve => setTimeout(resolve, 500));
            await testPrediction();
            await new Promise(resolve => setTimeout(resolve, 500));
            await testForecast();
        }

        function addResult(message, type, data = null) {
            const resultsDiv = document.getElementById('results');
            
            const colors = {
                'success': 'bg-green-50 border-green-200 text-green-800',
                'error': 'bg-red-50 border-red-200 text-red-800',
                'info': 'bg-blue-50 border-blue-200 text-blue-800'
            };
            
            const resultDiv = document.createElement('div');
            resultDiv.className = `border-l-4 p-4 rounded ${colors[type]}`;
            
            let html = `<p class="font-semibold">${message}</p>`;
            
            if (data) {
                html += `<details class="mt-2">
                    <summary class="cursor-pointer text-sm">View Details</summary>
                    <pre class="mt-2 text-xs bg-white p-2 rounded overflow-auto max-h-64">${JSON.stringify(data, null, 2)}</pre>
                </details>`;
            }
            
            resultDiv.innerHTML = html;
            resultsDiv.appendChild(resultDiv);
        }

        // Run initial health check on page load
        window.addEventListener('load', () => {
            testHealth();
        });
    </script>
</body>
</html>
