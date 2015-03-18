<?php

/*
 * getFileName.php
 * Part of createNewRecord.php
 * Checks file and uploads to MySQL DB
 * 
 */

function getFileName($field_values){
    
    //$field_values = $field_lists[0];
    
    FB::log("Field values: ");
    FB::log($field_values);
    
    $found_error = ERROR_MESSAGE;
    
    $file_name = $_FILES['file_name']['name'];
    $file_path = "";

    if (!$file_name) {
        $found_error .= "No file uploaded. Please upload an mp3 file or a PDF file.";
        FB::error($found_error);
        return $found_error;
        //exit;
    } else {
        $file_path = getNewFilePath($field_values);
        
        if ($file_path == ""){
            $found_error .= "Could not determine file path";
            FB::error($found_error);
            return $found_error;
            //exit;
        } 
        
        if(!move_uploaded_file($_FILES['file_name']['tmp_name'], $file_path)){
            $found_error .= "Upload failed";
            FB::error($found_error);
            return $found_error;
            //exit;
        }
    }
    FB::log("File path to be returned: ");
    FB::log($file_path);
    return $file_path;
}

function getNewFilePath($field_values){
    // Find extension: file should be of audio or PDF type
    $ext = $folder = "";
    $ext = checkFileType($_FILES['file_name']['type']);
    $folder = getFolder($ext);
    if ($ext == "" || $folder == "") {
        $found_error .= "'$file_name' had no extension or extension was invalid. "
                . "Please upload mp3 or PDF files only.";
        FB::error($found_error);
        return $found_error;
        //exit;
    }
    $new_file_name = renameUploadedFile($field_values);

        //  TODO: $length = wavDur($_FILES['filename']['tmp_name']);
    $file_path = $folder . "/" . $new_file_name . $ext;

    return $file_path;
}

function renameUploadedFile($field_values) {

    if (isBlank($field_values['date']) || isBlank($field_values['time']) || isBlank($field_values['sermon_title'])) {
        $found_error .= "Something went wrong while generating a new file name";
        FB::error($found_error);
        FB::error("Check the following fields: ");
        FB::error("Date: " . $field_values['date']);
        FB::error("Time: " . $field_values['time']);
        FB::error("Sermon Title: " . $field_values['sermon_title']);
        return $found_error;
    }

    // Sanitize file name. New file name based on date, time, title.
    $new_file_name = $field_values['date'] . '_' .
            $field_values['time'] . '_' .
            str_replace(" ", '_', $field_values['sermon_title']);

    return $new_file_name;
}

function checkFileType($fileType) {
    $ext = "";
    switch ($fileType) {
        case 'audio/mp3':
        case 'audio/mpeg':
        case 'audio/mpeg1':
        case 'audio/mpeg2':
        case 'audio/mpeg3':
        case 'audio/mpeg4':
            $ext = '.mp3';
            break;

        case 'application/pdf':
            $ext = '.pdf';
            break;

        default:
            $ext = '';
            break;
    }
    return $ext;
}

function getFolder($ext){
    $folder = "";
    switch($ext){
        case '.mp3': 
            $folder = __ROOT__ . '/mp3'; 
            break;
        
        case '.pdf': 
            $folder = __ROOT__ . '/pdf_notes'; 
            break;
        
        default: 
            $folder = ''; 
            break;
    }
    return $folder;
}

?>