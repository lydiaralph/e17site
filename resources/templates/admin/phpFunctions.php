<?php

// PHP functions

function isNotBlank($value) {
    return ($value == "" || $value == null) ? true : false;
}

function sanitizeString($var)
{
  if(get_magic_quotes_gpc()) $var = stripslashes($var);
  $var = htmlentities($var);
  $var = strip_tags($var);
  return $var;
}

function sanitizeStringDigits($var)
{
    $var = sanitizeString($var);
    $var = preg_replace("/[^0-9]/","",$var);
    return $var;
}

function sanitizeMySQL($var)
{
  $var = mysql_real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}

function wavDur($file) {
  $fp = fopen($file, 'r');
  if (fread($fp,4) == "RIFF") {
    fseek($fp, 20);
    $rawheader = fread($fp, 16);
    $header = unpack('vtype/vchannels/Vsamplerate/Vbytespersec/valignment/vbits',$rawheader);
    $pos = ftell($fp);
    while (fread($fp,4) != "data" && !feof($fp)) {
      $pos++;
      fseek($fp,$pos);
    }
    $rawheader = fread($fp, 4);
    $data = unpack('Vdatasize',$rawheader);
    $sec = $data[datasize]/$header[bytespersec];
    $minutes = intval(($sec / 60) % 60);
    $seconds = intval($sec % 60);
    return str_pad($minutes,2,"0", STR_PAD_LEFT).":".str_pad($seconds,2,"0", STR_PAD_LEFT);
  }
}
