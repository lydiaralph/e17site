<?php

$rows = mysql_num_rows($full_database);

echo '<table>';
for ($j = 0; $j < $rows ; ++$j)
{
  $row = mysql_fetch_row($full_database);
  if (!$row){continue;}
  echo '<tr class="sermonRecord">';
  
  echo '<td>';
  
  /* TODO - use field db names as columns */
  /*
  if ($full_db_fields) {
        foreach ($full_db_fields as $field) {
            echo "<li>DB field: " . $field['Field'] . "</li>";
            //echo "<span> : " . $field->name . "</span>";
        }
    } else
        echo "<span>full_db_fields does not exist</span>";
  
  if(!$full_database){
  echo "<span>full_database does not exist</span>";}
  */
  
  for($column=1; $column < (sizeof($row)); $column++){
  
      if($column == 8){
          echo '</td><td><span class="mp3-icon"><a href="/mp3/';
          echo $row[$column];
          echo '">\'\f109\'</a></span>';
          
          echo '</td><td><span class="mp3"><a href="/mp3/';
          echo $row[$column];
          echo '">' . $row[$column] . '</a></span>';                       
      }
      else{
        echo "<span class=\"";

        switch($column){
            case 1: 
                echo "title";
                break;
            case 2:
                echo "series";
                break;

            case 3:
                echo "date";
                break;

            case 4:
                echo "time";
                break;

            case 5:
                echo "book";
                break;

            case 6:
                echo "ref";
                break;

            case 7:
                echo "preacher";
                break;

            case 8:
            default: break;
        }
        echo "\">";
        echo $row[$column];
        echo "</span>";
      }
  }
  
  echo '</td></tr>';
}
  echo '</table>';


?>


<!--
for ($j = 0; $j < $rows ; ++$j)
{
  $row = mysql_fetch_row($full_database);
  if (!$row) continue;
  echo '<div class="row">';
  */ // Row header
//  echo '<span class="columnDate">' . $row[$full_db_fields['date']] . '</span>'; // Date
  //echo '<span class="columnTime">' . $row['time'] . '</span>'; // Time
  //echo '<span class="columnTitle"><a href=mp3/' . $row[7] . '>' . $row[3] . '</a></span>'; // Title & MP3 File
  //echo '<span class="columnSeries">' . $row[4] . '</span>'; // Series
  /*echo '<span class="columnReference"><a href="https://www.biblegateway.com/passage/?search=%22'
           . $row[5] . " " . $row[6] . '%22&&version=NIV">'
         . $row[5] . " " . $row[6] . '</a></span>';*/ // Ref
  //echo '<span class="cell columnPreacher">' . $row['preacher'] . '</span>'; // Preacher
  // echo '<span class="cell">' . $row[9] . '</span>'; // Length
  //echo '</div>';
//}
-->


<!--
<tr>
<td>
<li>
<a href="#" onclick="return AudioPlayerByType('sermons','80-71');" class="watch"><strong>Listen</strong></a>
-->
<!-- TODO #2 Use enum instead of $row[1] to make code more scalable -->
<!-- 
<p class="sermonTitle"><a href='/resources/sermons/80-71/crucial-lessons-for-a-wise-father'>Crucial Lessons for a Wise Father</a></p>
<p class="sermonSeries">Family wisdom</p>
<p><span class="sermonDate">June 17, 1990</span><span class="sermonTime">AM</span></p>
<p class="sermonRef">Proverbs</p>
<p class="preacher">Billy Graham</p>
</li>
</td>
</tr> -->