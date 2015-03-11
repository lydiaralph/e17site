/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var thecols = new Array();
thecols["dateselect"]     = "0";
thecols["timeselect"]     = "1";
thecols["seriesselect"]   = "3";
thecols["bible_bookselect"] = "4";
thecols["preacherselect"]       = "5";

/*
function cursor(val) {
   document.body.style.cursor=val;
}
*/

var highlight = 1;    // Used for subtle striped highlighting

function normal()
{
  highlight++;
  if ( highlight % 2 ) return ""; 
  else return "hl";
}


function removefilter()
{
   // Iterate through rows, and make them all visible
   var allrows = document.getElementById("sermonsmp3").rows;
   for (p = 0; p < allrows.length; p++) {
      allrows[p].style.display = '';
   }
}

function filter(columntag, findvalue, firstnmatches)
{
   // Iterate through rows, find the column and hide row unless value matches
   var allrows = document.getElementById("sermonsmp3").rows;
   var colnumber = thecols[columntag];
   var found = 0;
   for (p = 0; p < allrows.length; p++) {
      var coldata = "nothing";
      var col = allrows[p].cells[colnumber];    // Find the right column
      if (col.hasChildNodes()) {                // Data could be in <a href> inside <td>
         var fc = col.firstChild;
         coldata = fc.nodeValue;
         if (coldata == undefined && fc.hasChildNodes()) {
            coldata = fc.firstChild.nodeValue;
         }
      }

      if (found < firstnmatches &&
          coldata.indexOf(findvalue) == 0) {    // Find value, matching from start
         allrows[p].style.display = '';       // Stripe the rows nicely
         found++;
      } else {         
            allrows[p].style.display = 'none'; // Hide results that don't match
      }
   }
 //  setfound(found);
}

/* function setfound(found)
{
   var foundtext = document.getElementById("foundtext");
   if (found > 0) {
     foundtext.innerHTML = "Found " + found + " results";
   } else {
     foundtext.innerHTML = "&nbsp;";
   }
}
*/

function resetall()
{
   resetselectors();
   removefilter();
//   setfound(0);
}

function resetselectors(field)
{
   // highlight = 1;
 
   // Reset all selectors apart from this one
   var form = document.getElementById('selform');
   for (p = 0; p < form.elements.length; p++) {
      if ( form.elements[p] != form.elements[field] ) {
         form.elements[p].selectedIndex = 0;
      }
   }
}

function select(id)
{
 //  cursor("wait");
   resetselectors(id);   // Reset all selectors apart from this one

   var selector = document.getElementById(id);
   var selected = selector.selectedIndex;
   var curvalue = selector.options[selected].value;

   if (curvalue)  { filter(id, curvalue, 9999); }
   else           { removefilter(); }
//   cursor("auto");
}


