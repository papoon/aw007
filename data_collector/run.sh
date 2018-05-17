#!/bin/bash

php startCollection.php
cd ..
cd scripts
python3 annotation/invertedIndexCalculator.py
python3 annotation/invertedIndexBuilder.py
cd ../data_collector
