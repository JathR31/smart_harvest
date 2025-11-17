"""
ML Dataset Loader
-----------------
This module loads datasets uploaded through the admin panel for machine learning processing.
Datasets are stored in storage/app/public/datasets/ and metadata is in the uploaded_datasets table.
"""

import pandas as pd
import os
import mysql.connector
from pathlib import Path

class DatasetLoader:
    def __init__(self, db_config=None):
        """
        Initialize the dataset loader with database configuration.
        
        Args:
            db_config (dict): Database connection config {host, user, password, database}
        """
        if db_config is None:
            db_config = {
                'host': 'localhost',
                'user': 'root',
                'password': '',
                'database': 'smartharvest'
            }
        self.db_config = db_config
        self.base_path = Path(__file__).parent / 'storage' / 'app' / 'public' / 'datasets'
    
    def get_available_datasets(self):
        """Get list of all available datasets from database."""
        conn = mysql.connector.connect(**self.db_config)
        cursor = conn.cursor(dictionary=True)
        
        query = """
        SELECT id, name, description, file_name, file_path, full_path, 
               record_count, file_size, uploaded_by, created_at
        FROM uploaded_datasets
        ORDER BY created_at DESC
        """
        
        cursor.execute(query)
        datasets = cursor.fetchall()
        
        cursor.close()
        conn.close()
        
        return datasets
    
    def load_dataset(self, dataset_id=None, dataset_name=None):
        """
        Load a dataset by ID or name.
        
        Args:
            dataset_id (int): Dataset ID from database
            dataset_name (str): Dataset name
            
        Returns:
            pandas.DataFrame: Loaded dataset
        """
        conn = mysql.connector.connect(**self.db_config)
        cursor = conn.cursor(dictionary=True)
        
        if dataset_id:
            query = "SELECT * FROM uploaded_datasets WHERE id = %s"
            cursor.execute(query, (dataset_id,))
        elif dataset_name:
            query = "SELECT * FROM uploaded_datasets WHERE name LIKE %s"
            cursor.execute(query, (f'%{dataset_name}%',))
        else:
            raise ValueError("Either dataset_id or dataset_name must be provided")
        
        dataset_info = cursor.fetchone()
        cursor.close()
        conn.close()
        
        if not dataset_info:
            raise ValueError(f"Dataset not found")
        
        # Load the CSV file
        file_path = dataset_info['full_path']
        if not os.path.exists(file_path):
            # Try relative path
            file_path = str(self.base_path / dataset_info['file_name'])
        
        if not os.path.exists(file_path):
            raise FileNotFoundError(f"Dataset file not found: {file_path}")
        
        # Load based on file extension
        if file_path.endswith('.csv'):
            df = pd.read_csv(file_path)
        elif file_path.endswith('.xlsx') or file_path.endswith('.xls'):
            df = pd.read_excel(file_path)
        else:
            raise ValueError(f"Unsupported file format: {file_path}")
        
        print(f"Loaded dataset: {dataset_info['name']}")
        print(f"Records: {len(df)}")
        print(f"Columns: {list(df.columns)}")
        
        return df, dataset_info
    
    def get_latest_dataset(self):
        """Get the most recently uploaded dataset."""
        datasets = self.get_available_datasets()
        if not datasets:
            raise ValueError("No datasets available")
        return self.load_dataset(dataset_id=datasets[0]['id'])


# Example usage
if __name__ == "__main__":
    loader = DatasetLoader()
    
    # List all available datasets
    print("Available datasets:")
    datasets = loader.get_available_datasets()
    for ds in datasets:
        print(f"  ID: {ds['id']}, Name: {ds['name']}, Records: {ds['record_count']}")
    
    if datasets:
        # Load the latest dataset
        print("\nLoading latest dataset...")
        df, info = loader.get_latest_dataset()
        print(f"\nDataset preview:")
        print(df.head())
        print(f"\nDataset shape: {df.shape}")
        print(f"\nDataset info: {info['name']} uploaded by {info['uploaded_by']}")
