# Manual Tests Directory

This folder contains manual test files, test scripts, and test data for the SmartHarvest application.

## Contents

### Test Scripts (test_*.php)
These are manual test files used to verify functionality during development:
- Test API endpoints
- Test authentication and translations
- Test SMS, email, and announcements
- Test data processing and ML integration
- Test dashboard and dashboard data

### Setup & Verification Scripts
- **setup_superadmin.php** - Setup superadmin user
- **check_dataset.php** - Verify dataset integrity
- **check_municipalities.php** - Check municipality data
- **check_superadmin.php** - Verify superadmin setup
- **verify_user.php** - Verify user creation
- **verify_announcement_fix.php** - Verify announcement fixes

### Test Data Files
- **sample_dataset.csv** - Sample dataset for testing
- **fulldataset.csv** - Full dataset for testing
- **backup_*.sql** - Database backups for restoration
- **pagasa_sample.html** - Sample PAGASA weather data
- **test_translation_homepage.html** - Translation test HTML

### Information Files
- **SmartHarvest.ipynb** - Jupyter notebook with data analysis

## Usage

To run a test manually:
```bash
php tests/manual/test_name.php
```

**Note:** These are development test files and should only be run in development/testing environments, not in production.

## Important
- Test files contain debugging and verification logic
- Do not include these files in production deployments
- Use Laravel's built-in test suite (tests/ folder) for automated testing
