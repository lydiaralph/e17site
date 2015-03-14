<?php

/*
 * getFileName.php
 * Part of createNewRecord.php
 * Checks file and uploads to MySQL DB
 * 
 */

function getFileName($field_values){
        //$date, $time, $sermon_title, $cleanFileName) {
    $found_error = "";
    
    $file_name = $_FILES['file_name']['name'];

    if (!$file_name) {
        FB::error("No file uploaded. Please upload an mp3 file or a PDF file.");
        exit;
    } else {
        // Find extension: file should be of audio or PDF type
        $ext = $folder = "";
        $ext = checkFileType($_FILES['file_name']['type']);
        $folder = getFolder($ext);
        if ($ext == "" || $folder == "") {
            FB::error("'$file_name' had no extension or extension was invalid. "
            . "Please upload mp3 or PDF files only.");
            exit;
        }
    
        // Sanitize file name. New file name based on date, time, title.
        $new_file_name =              $field_values['date'] . '_' . 
                                      $field_values['time'] . '_' . 
                str_replace(" ", '_', $field_values['sermon_title']);
        
        //  TODO: $length = wavDur($_FILES['filename']['tmp_name']);
        $file_path = $folder . "/" . $new_file_name . $ext;
        
        if ($file_path == ""){
            FB::error("Could not determine file path");
            exit;
        } else if(!move_uploaded_file($_FILES['file_name']['tmp_name'], $file_path)){
            FB::error("Upload failed");
            exit;
        }
    }
    return $filepath;
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
        case '.mp3': $folder = 'mp3'; break;
        case '.pdf': $folder = 'pdf_notes'; break;
        default: $folder = ''; break;
    }
    return $folder;
}

?>