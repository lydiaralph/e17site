function removeFilter() {
    // Make all records visible

    var allRecords = document.getElementsByClassName("sermonRecord");

    for (var i = 0; i < allRecords.length; i++) {
        allRecords[i].style.display = '';
    }
    
}

function filter(columntag, findvalue, firstnmatches){
   // Iterate through records, find the field (span class="x") and hide 
   // record unless value of field matches filter option
   
   removeFilter();
   
   var recNo = 0;
   var doesNotMatch = false;
   var allRecords = document.getElementsByClassName(columntag);
    
   for(var i = 0; i < allRecords.length; i++) {
        doesNotMatch = false;
        if (columntag == "date") {
            // Get year from date string
            if (parseInt(allRecords[i].textContent) !== findvalue) {
                doesNotMatch = true;
            }
        }
        else if (allRecords[i].textContent !== findvalue) {
            doesNotMatch = true;
        }
        if (doesNotMatch) {
            var selector = ".sermonRecord";
            var parent = findParentBySelector(allRecords[i], selector);
            if (parent) {
                parent.style.display = 'none';
            }
        }
   }
}
    
function collectionHas(a, b) { //helper function (see below)
    for (var i = 0, len = a.length; i < len; i++) {
        if (a[i] == b)
            return true;
    }
    return false;
}

function findParentBySelector(elm, selector) {
    var all = document.querySelectorAll(selector);
    var cur = elm.parentNode;
    while(cur && !collectionHas(all, cur)) { //keep going up until you find a match
        cur = cur.parentNode; //go up
    }
    return cur; //will return null if not found
}

function resetall()
{
   resetselectors();
   removeFilter();
}

function resetselectors(field)
{
   // highlight = 1;
 
   // Reset all selectors apart from this one
   var form = document.getElementById('selform');
   for (p = 0; p < form.elements.length; p++) {
      if ( form.elements[p] !== form.elements[field] ) {
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
   var curvalue = selector.options[selected].text;

   if (curvalue)  { filter(id, curvalue, 9999); }
   else           { removeFilter(); }
//   cursor("auto");
}


