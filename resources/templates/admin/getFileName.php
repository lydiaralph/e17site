<?php

/*
 * getFileName.php
 * Part of createNewRecord.php
 * Checks file and uploads to MySQL DB
 * 
 */

function getFileName($date, $time, $sermon_title, $cleanFileName) {
    $file_name = $_FILES['file_name']['name'];

    if (!$file_name) {
        $found_error .= "No file uploaded. Please upload an mp3 file or a PDF file.";
    } else {
        $new_file_name = $date . '_' . $time . '_' . str_replace(" ", '_', $sermon_title);

        $ext = $folder = "";

        $found_error = checkFileType($ext, $folder);

        if ($ext == "") {
            $found_error .= "'$file_name' is not of an accepted file type. Please upload mp3 or PDF files only.";
        }
        

        //  $length = wavDur($_FILES['filename']['tmp_name']);
        $cleanFileName = $new_file_name . $ext;
        $file_path = $folder . "/" . $cleanFileName;
        $success = move_uploaded_file($_FILES['file_name']['tmp_name'], $file_path);

        // upload file

        if (!$success) {
            $found_error .= "Upload failed";
        }
    }
    return $found_error;
}

function checkFileType($ext, $folder) {
    switch ($_FILES['file_name']['type']) {
        case 'audio/mp3':
        case 'audio/mpeg':
        case 'audio/mpeg1':
        case 'audio/mpeg2':
        case 'audio/mpeg3':
        case 'audio/mpeg4':
            $ext = '.mp3';
            $folder = 'mp3';
            break;

        case 'application/pdf':
            $ext = '.pdf';
            $folder = 'pdf_notes';
            break;

        default:
            $ext = '';
            $found_error = "File had no extension or extension was invalid";
            break;
    }
    return $found_error;
}

?>