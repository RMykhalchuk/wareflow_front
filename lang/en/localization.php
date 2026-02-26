<?php


$folderPath = base_path('lang/en/pages');

$mergedArray = [];

// Get all files in the folder
$files = scandir($folderPath);

// Loop through files
foreach ($files as $file) {
    // Exclude . and .. directories
    if ($file !== '.' && $file !== '..') {
        $filePath = $folderPath . '/' . $file;

        // Check if it's a file
        if (is_file($filePath)) {
            // Include the file and merge its array with the existing merged array
            $arrayFromFile = include($filePath);

            if (is_array($arrayFromFile)) {
                $mergedArray = array_merge($mergedArray, $arrayFromFile);
            }
        }
    }
}

return $mergedArray;
