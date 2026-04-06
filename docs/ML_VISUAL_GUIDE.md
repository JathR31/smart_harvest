# Visual Guide: ML Integration in SmartHarvest

## Where to See ML Predictions in Action

### 1. Yield Analysis Page
**URL:** `http://localhost/dashboard/SmartHarvest/public/yield-analysis`

#### Chart 1: Yield Comparison (2020-2025)
```
📊 Multi-Year Trend Chart

Legend:
━━━ Green Solid Line: Actual Historical Yields
- - - Blue Dashed Line: ML Predicted Yields

Hover over any point to see:
• Year: 2025
• Actual: 18.50 mt/ha
• Predicted: 19.20 mt/ha
• Confidence: 92%
```

#### Chart 2: Crop Performance by Variety
```
📊 Horizontal Bar Chart

For each crop:
■ Green Bar: Actual Yield
■ Blue Bar: ML Predicted Yield

Example:
Cabbage       ████████████ 22.13 mt/ha (Actual)
              ████████████▓ 23.50 mt/ha (Predicted)
              Confidence: 89%
```

---

### 2. Planting Schedule Page
**URL:** `http://localhost/dashboard/SmartHarvest/public/planting-schedule`

#### Top Cards

**Card 1: Next Optimal Date**
```
┌─────────────────────────┐
│ 📅 Next Optimal Date    │
│                         │
│    Nov 30               │
│    Cabbage (Scorpio)    │
│                         │
└─────────────────────────┘
```

**Card 2: Expected Yield (ML)**
```
┌─────────────────────────┐
│ 📈 Expected Yield       │
│                         │
│    19.2 mt/ha           │
│    High confidence      │
│                         │
└─────────────────────────┘
```

#### Planting Schedule Table

```
┌─────────────┬─────────────┬──────────────┬──────────────┬─────────────┐
│ Crop        │ Planting    │ Harvest      │ ML           │ Confidence  │
│ Variety     │ Window      │ Window       │ Prediction   │             │
├─────────────┼─────────────┼──────────────┼──────────────┼─────────────┤
│ Cabbage     │ Nov 15 -    │ Feb 13 -     │ 18.50 mt/ha  │ [High 92%]  │
│ Scorpio     │ Dec 15      │ Mar 15       │ Hist: 17.20  │ 🟢          │
├─────────────┼─────────────┼──────────────┼──────────────┼─────────────┤
│ Carrot      │ Nov 20 -    │ Feb 18 -     │ 16.30 mt/ha  │ [High 88%]  │
│ Highland    │ Dec 20      │ Mar 20       │ Hist: 15.80  │ 🟢          │
├─────────────┼─────────────┼──────────────┼──────────────┼─────────────┤
│ Potato      │ Nov 25 -    │ Feb 23 -     │ 19.80 mt/ha  │ [Med 76%]   │
│ Granola     │ Dec 25      │ Mar 25       │ Hist: 19.60  │ 🟡          │
└─────────────┴─────────────┴──────────────┴──────────────┴─────────────┘

Legend:
🟢 High Confidence (≥85%)  - Strong ML prediction
🟡 Medium Confidence (70-84%) - Moderate ML prediction
🔴 Low Confidence (<70%)   - Weak ML prediction
```

---

### 3. Dashboard Page
**URL:** `http://localhost/dashboard/SmartHarvest/public/dashboard`

#### Expected Harvest Card
```
┌──────────────────────────────────┐
│ Year Expected Harvest            │
│                                  │
│ 5.2 metric tons                  │
│ ↑ 12% better than last year      │
│                                  │
│ (Uses aggregated ML predictions) │
└──────────────────────────────────┘
```

---

## Color Guide

### Data Visualization Colors

**Actual/Historical Data:**
- Color: Green (#10b981)
- Usage: Actual yields, historical averages
- Chart Type: Solid lines, solid bars

**ML Predictions:**
- Color: Blue (#3b82f6)
- Usage: Predicted yields, forecasts
- Chart Type: Dashed lines, transparent bars

**Confidence Indicators:**
- 🟢 High: Green badge
- 🟡 Medium: Yellow badge
- 🔴 Low: Red badge

---

## Data Flow Visualization

```
┌─────────────────┐
│  User Opens     │
│  Page           │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Frontend       │
│  (AlpineJS)     │
│  Requests Data  │
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│  Laravel API Endpoint   │
│  /api/yield/comparison  │
│  /api/yield/crops       │
│  /api/planting/schedule │
└────────┬────────────────┘
         │
         ├──► Fetch Historical Data (MySQL)
         │
         ▼
┌─────────────────┐
│  MLApiService   │
│  predict()      │
└────────┬────────┘
         │
         ▼
┌──────────────────────┐
│  Python Flask API    │
│  http://127.0.0.1:5000│
│  /api/predict        │
└────────┬─────────────┘
         │
         ▼
┌─────────────────┐
│  ML Model       │
│  Random Forest  │
│  Calculation    │
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│  JSON Response          │
│  {                      │
│    predicted: 18.5,     │
│    confidence: 0.92     │
│  }                      │
└────────┬────────────────┘
         │
         ▼
┌─────────────────┐
│  Chart.js       │
│  Renders Graph  │
└─────────────────┘
```

---

## Tooltip Examples

### Yield Comparison Chart Tooltip
```
┌──────────────────────┐
│ 2025                 │
│ Actual: 18.50 mt/ha  │
│ Predicted: 19.20     │
│ Confidence: 92%      │
└──────────────────────┘
```

### Crop Performance Chart Tooltip
```
┌──────────────────────┐
│ Cabbage              │
│ Actual: 22.13 mt/ha  │
│ ML Pred: 23.50       │
│ Confidence: 89%      │
└──────────────────────┘
```

---

## API Response Examples

### Yield Comparison API
**Endpoint:** `GET /api/yield/comparison?municipality=La Trinidad`

```json
[
  {
    "year": 2025,
    "actual": 18.50,
    "predicted": 19.20,
    "confidence": 92.5
  },
  {
    "year": 2024,
    "actual": 17.80,
    "predicted": 18.10,
    "confidence": 90.2
  }
]
```

### Planting Schedule API
**Endpoint:** `GET /api/planting/schedule?municipality=La Trinidad`

```json
[
  {
    "crop": "Cabbage",
    "variety": "Scorpio",
    "optimal_planting": "Nov 15 - Dec 15",
    "expected_harvest": "Feb 13 - Mar 15",
    "duration": "90 days",
    "yield_prediction": "18.50 mt/ha",
    "historical_yield": "17.20 mt/ha",
    "confidence": "High",
    "confidence_score": 92.5,
    "status": "Recommended"
  }
]
```

---

## Quick Test Checklist

### ✅ Visual Tests

1. **Open Yield Analysis Page**
   - [ ] Two lines visible on Yield Comparison chart (green solid, blue dashed)
   - [ ] Hover shows confidence percentages
   - [ ] Crop Performance shows two bars per crop

2. **Open Planting Schedule Page**
   - [ ] Expected Yield card shows ML prediction
   - [ ] Table shows ML Prediction column in green
   - [ ] Confidence badges display (High/Medium/Low)
   - [ ] Confidence percentages visible

3. **Check Data Alignment**
   - [ ] ML predictions differ from actual (not identical)
   - [ ] Confidence scores make sense (85-95% typical)
   - [ ] Historical yields shown for comparison

### ✅ API Tests

```bash
# Test ML Health
curl http://localhost/dashboard/SmartHarvest/public/api/ml/test

# Test Prediction
curl http://localhost/dashboard/SmartHarvest/public/api/ml/test-prediction
```

Expected: Both return `"status": "success"`

---

## Common Visual Indicators

### When ML is Working:
- ✅ Two datasets in charts (actual vs predicted)
- ✅ Confidence scores displayed
- ✅ Green numbers for ML predictions
- ✅ Blue color for predicted data
- ✅ Badges showing High/Medium/Low

### When ML Fallback Occurs:
- ⚠️ Single dataset only (historical)
- ⚠️ No confidence scores
- ⚠️ Warning message in console
- ⚠️ Uses last known values

---

## User Experience

### What Farmers See:

**Before ML Integration:**
"Average yield: 18.5 mt/ha"

**After ML Integration:**
"Predicted yield: 19.2 mt/ha (92% confidence)
Historical average: 18.5 mt/ha
Expected improvement: +3.8%"

### Benefits:
1. **Confidence in decisions** - Know prediction reliability
2. **Comparison view** - See predicted vs actual
3. **Transparency** - Understand seasonal factors
4. **Risk assessment** - Evaluate based on confidence

---

## Maintenance Quick Reference

### Daily Checks
```bash
# Check ML API Status
curl http://127.0.0.1:5000/health

# Should return:
{
  "status": "healthy",
  "model_loaded": true
}
```

### If Charts Not Updating
1. Check browser console for errors
2. Verify ML API server is running
3. Test ML endpoints manually
4. Clear browser cache

### Performance Monitoring
- Response time should be <50ms
- Confidence scores should be 85-95%
- No errors in Laravel logs
- ML API uptime 99%+

---

**For detailed technical documentation, see `ML_INTEGRATION_SUMMARY.md`**
