<?php

/**
 * Generate a CSV file in the filesystem
 * 
 * @param Array $data Data to be inserted in the report
 * @param String $folder_path Folder path to the save location
 * @param String $file Name of the File
 * 
 * @return String Path to the newly created file
 */

function generateCsv($data, $folder_path, $file)
{
    if(!file_exists($folder_path)){
        throw new Exception('Invalid report path');
    }

    $file_path = $folder_path.'/'.$file; 
    $fp = fopen(($file_path), 'w');
    if (!$fp) {
        throw new Exception('Report path is inaccessible');
    }
    foreach ($data as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);
    return BASE_URL.'/reports/'.$file;
}