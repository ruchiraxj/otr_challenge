# 1. Configurations

1. Please update the configurations as the initial step. You can change configs here: ./configs/configs.php
2. Report dates and Vat rates are configurable, You can configure these data by changing the values in the configuration File
3. Reports are stored in "./reports/" Folder
3. To open the UI in browser you can visit (http://.../otr_challenge/public/report.php)


# 2. Generate Report Using UI

1. Upload database
2. go to configs/configs.php
3. Make the Configuration changes accordingly
4. Open browser and visit the file (ex: http://.../otr_challenge/public/report.php)


# 3. Generate Reports As An External User

1. Upload database
2. go to configs/configs.php
3. Make the Configuration changes accordingly

## 3.1 Generate Turnover Per Brand Report
  
#### Method: 
GET

#### URL: 
http://.../otr_challenge/report/turnoverPerBrand?start_date={start_date}&end_date={end_date}

#### REQUEST HEADERS: 
Authorization : 67b1c2ab-e4cf-3ddd-81bb-c7a9b30691f1

## 3.2 Generate Daily Turnover Report
  
#### Method:
GET

#### URL:
http://.../otr_challenge/report/dailyTurnover?start_date={start_date}&end_date={end_date}

#### REQUEST HEADERS: 
Authorization : 67b1c2ab-e4cf-3ddd-81bb-c7a9b30691f1


# 4. STEPS TO RUN UNIT TEST

1. open console
2. Navigate to the Folder path
3. Run "composer install" (Make sure you have composer installed)
4. Run "./vendor/bin/phpunit"


+++++++++++++++++++++++++++++++++++++
