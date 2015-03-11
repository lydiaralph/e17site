var W3CDOM = (document.getElementsByTagName && document.createElement);

/* window.onload = function () {
 document.forms[0].onsubmit = function ()	{
 return validate()
 }
 }
 */

function validate(form) {
    validForm = true;
    fail = "";

    /*
     * TODO: this could be developed as part of dynamic checking, getting 
     * field_names from DB and doing auto-checks, not bespoke per field
     field_names = new Array("date", "sermon_title", "series", "preacher", "bible_book", 
     "bible_ch_start", "bible_verse_start",  "bible_ch_end", "bible_verse_end", "file_name");
     
     
     for (var fld=0; fld<field_names.length; fld++) {
     checkField(field_names[fld]);
     }
     */

    fail += validateDate("date");
    // TODO: validateBibleRef does not yet find field value
    //fail += validateBibleRef ("bible_ch_start", "bible_verse_start",  "bible_ch_end", "bible_verse_end");
    fail += checkField("series");
    fail += checkField("bible_book");
    fail += checkField("preacher");
    fail += checkField("sermon_title");
    fail += checkField("file_name");

    if (!W3CDOM)
        alert(fail);
    //  if (firstError)
    //    firstError.focus();

    /*if (fail == "") { 
     var confirmed = confirm("Please confirm your entries: \n" + 
     "Date: \t\t" + form.day.value + "\/" + form.month.value + "\/" + form.year.value + "\n" +
     "Sermon title: \t\t" + form.sermon_title.value + "\n" +
     "Series: \t\t" + form.select_series.value + "\n" +
     "Preacher: \t\t" + form.select_preacher.value + "\n" +
     "Bible reference: " + form.bible_book.value + " " + form.bible_ch_start.value + ":" + form.bible_verse_start.value 
     + " - " + form.bible_ch_end.value + ":" + form.bible_verse_end.value + "\n" +
     "File name:\t\t" + form.file_name.value);
     return confirmed; 
     }
     else {
     
     alert (fail); return false; }
     */
    
    
    // LER TODO temp commented out
    if(fail){
        alert(fail);
        return false;
    }
    else{
        return true;
    }
}

function highlightError(obj, message) {
    validForm = false;
    if (obj.hasError)
        return;
    obj.className += ' error';
    obj.onchange = removeError;
    //       obj.hasError = td_error;
}


function writeError(obj, message) {
    validForm = false;
    if (obj.hasError)
        return;
    if (!W3CDOM) {
        alert(fail);
        fail += obj.name + ': ' + message + '\n';
        obj.hasError = true;
    }
    else {
        var td_error = document.createElement('td');
        td_error.className = ' error';
        td_error.appendChild(document.createTextNode(message));
        obj.parentNode.appendChild(td_error);
    }

}

function removeError()
{
    this.className = this.className.substring(0, this.className.lastIndexOf(' '));
    this.parentNode.removeChild(this.hasError);
    this.hasError = null;
    this.onchange = null;
}

function checkField(field_name) {

    var error_text = "";

    switch (field_name) {
        case 'series':
        case 'preacher':
            var fieldValue = "";

            fieldValue = findFieldValue(field_name);

            // Default to displaying select option
            if (!fieldValue) {
                error_text = "Please enter a " + field_name;
                chooseInput(field_name, "add", "select");
                highlightError(document.getElementById("select_" + field_name));
                writeError(document.getElementById("select_" + field_name), 'Required field');
            }

            break;

        case 'bible_book':
        case 'sermon_title':
            var field = document.getElementById(field_name);
            
            fieldValue = findChildValue(field);
            
            if (!fieldValue) {
                error_text = "Please enter a " + field_name;
                parentField = field.parentNode;
                highlightError(parentField);
                writeError(parentField, 'Required field');
            }
            break;

        default:
            var field = document.getElementById(field_name);
            fieldValue = findChildValue(field);
            if (!fieldValue) {
                error_text = "Please enter a " + field_name;
                highlightError(field);
                writeError(field, 'Required field');
            }
            break;
    }
    return error_text;
}

// This function is specifically for preacher_ and series_ fields
// which can have values either in select_X or add_X fields
function findFieldValue(field_name) {
    var field = document.getElementById("select_" + field_name);
    var fieldValue = "";

    fieldValue = findChildValue(field);

    // Nothing in select_ field; try add_ field
    if (!fieldValue) {
        var field = document.getElementById("add_" + field_name);
        fieldValue = findChildValue(field);
    }

    return fieldValue;
}

// This function finds the value of a field by looking in up to two child
// nodes until a value is found
function findChildValue(field){
    var childValue = "";
    
    childValue = field.value;
    
    if(childValue == undefined && field.hasChildNodes()) {
        var fc = field.firstChild;
        childValue = fc.value;
        if (childValue == undefined && fc.hasChildNodes()) {
            childValue = fc.firstChild.value;
        }
    }
    return childValue;
}

function validateBibleRef(ch_start, ch_end, verse_start, verse_end) {
    var brfail = "";

    for (var arg = 0; arg < arguments.length; arg++) {
        var field = document.getElementById(arguments[arg]);

        if (!field.value) {
            highlightError(field);
            brfail += "Please enter a Bible reference";
            switch (arg) {
                case '0':
                    brfail += " chapter start\n";
                    break;
                case '1':
                    brfail += " chapter end.\n";
                    break;
                case '2':
                    brfail += " verse start.\n";
                    break;
                case '3':
                    brfail += " verse end.\n";
                    break;
                default:
                    break;
            }
        }
    }

    if (brfail != "") {
        writeError(field, "Required field");
        return brfail;
    }

    // Ensure bible ref makes sense
    if (ch_end < ch_start) {
        highlightError(document.getElementById(ch_end));
        highlightError(document.getElementById(ch_start));
        return "Bible ref: chapter start must not be after chapter end\n";
    }
    else if (ch_end == ch_start)
    {
        if (verse_end < verse_start) {
            highlightError(document.getElementById(verse_end));
            highlightError(document.getElementById(verse_start));
            return "Bible ref: verse start must not be after verse end\n";
        }
    }

    return "";
}


function validateDate(field) {
    var datefail = "";

    var select_opts = new Array("day", "month", "year");

    for (var j = 0; j < select_opts.length; j++) {
        var elem_select_opt = document.getElementById(select_opts[j]);
        for (var i = 0; i < elem_select_opt.length; i++)
        {
            if (elem_select_opt[i].selected)
                var found_text = true;
        }
        if (!found_text)
            datefail += "Please select a " + select_opts[j] + "\n";
    }

    if (datefail != "") {
        highlightError(document.getElementById("date"))
        writeError(document.getElementById(field), "Required field");
        if (datefail != "")
            return datefail;
    }
    /*    
     
     // Assume not leap year by default (note zero index for Jan)
     var daysInMonth = [31,28,31,30,31,30,31,31,30,31,30,31];
     
     // If evenly divisible by 4 and not evenly divisible by 100,
     // or is evenly divisible by 400, then a leap year
     
     if ( (!(year % 4) && year % 100) || !(year % 400)) {
     daysInMonth[1] = 29;
     }
     if (day <= daysInMonth[month])
     return (day + "\/" + month + "\/" + year + " is not a valid date\n");
     
     var date = new Date(year, month, day); 
     
     if (date == 'Invalid Date')
     return "Invalid date entered.\n";
     else if (Date.parse (date) == 0)
     return "Invalid date entered (parsed).\n";    
     */

    return "";
}




function validatePreacher(known_preacher, new_preacher) {
    if (known_preacher == "" && new_preacher == "")
        return "Please select a preacher.\n"
//    if( preacher == "") return "Please select a preacher.\n"
    return ""
}

function validateSeries(known_series, new_series) {
    if (known_series == "" && new_series == "")
        return "Please select a series.\n"
    return ""
}

function validateSermonTitle(sermon_title) {
    if (sermon_title == "")
        return "Please enter a sermon title.\n"
    return ""
}

function validateFile(fileName) {
    if (fileName == "")
        return "Please upload an mp3 file or a PDF file.\n";

    var validFileExtensions = [".mp3", ".pdf"];

    if (fileName.length > 0) {
        var blnValid = false;
        for (var j = 0; j < validFileExtensions.length; j++) {
            var sCurExtension = validFileExtensions[j];
            if (fileName.substr(fileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                blnValid = true;
                break;
            }
        }

        if (!blnValid) {
            return("Sorry, " + fileName + " is an invalid file type. Allowed extensions are: " + validFileExtensions.join(", ") + "\n");
        }
        return "";
    }
}

function chooseInput(field, old_choice, new_choice) {
    // Hide old choice
    document.getElementById(old_choice + "_" + field + "_label").className += ' hidden';
    document.getElementById(old_choice + "_" + field).className += ' hidden';
    document.getElementById(old_choice + "_" + field + "_link").className += ' hidden';

    // Display new choice
    document.getElementById(new_choice + "_" + field + "_label").className =
            document.getElementById(new_choice + "_" + field + "_label").className.replace(/\b hidden\b/, '');

    document.getElementById(new_choice + "_" + field).className =
            document.getElementById(new_choice + "_" + field).className.replace(/\b hidden\b/, '');

    document.getElementById(new_choice + "_" + field + "_link").className =
            document.getElementById(new_choice + "_" + field + "_link").className.replace(/\b hidden\b/, '');
}



// TODO: The PHP code in this function does not work
function newField(field, action) {
    var label = document.createElement(field + "_label");
    label.id = field + "_label";

    if (action == "add") {
        if (field == 'preacher')
            label.appendChild(document.createTextNode('Please enter preacher\'s full name: '));
        else if (field == 'series')
            label.appendChild(document.createTextNode('Please enter title of series: '));
    }
    else if (action == "select") {
        if (field == 'preacher')
            label.appendChild(document.createTextNode('Preacher: '));
        else if (field == 'series')
            label.appendChild(document.createTextNode('Series: '));
    }

    label.setAttribute("style", "font-weight:normal");

    // replace field label with new label
    var fieldDiv = document.getElementById(field + "_label");
    var parentDiv = fieldDiv.parentNode;
    parentDiv.replaceChild(label, fieldDiv);

    // create new input box
    if (action == "add") {
        var txtbox = document.createElement("input");
        txtbox.setAttribute("type", "text");
    }
    else if (action == "select") {
        var txtbox = document.createElement("select");
        txtbox.setAttribute("size", "4");

        // TODO: HERE
        var arr = $.parseJSON('<?php echo json_encode($preacher_list); ?>');
        var i = 0;
        for (i = 0; i <= arr.length; i++) {
            var option = document.createElement(arr[i]);
            option.text = arr[i];
            txtbox.add(option);
        }
    }

    txtbox.setAttribute("id", field);
    txtbox.setAttribute("value", "");
    txtbox.setAttribute("name", field);
    txtbox.setAttribute("style", "width:200px");

    // replace selection box with input box
    var fieldDiv = document.getElementById(field);
    var parentDiv = fieldDiv.parentNode;
    parentDiv.replaceChild(txtbox, fieldDiv);

    // create link to select field instead
    var link = document.createElement('a');
    link.id = "new_" + field;

    if (action == "add") {
        var linkText = document.createTextNode('Select ' + field + ' from list');
        link.setAttribute('href', "javascript:newField('" + field + "','select')");
    }
    else if (action == "select") {
        var linkText = document.createTextNode('New ' + field + '?');
        link.setAttribute('href', "javascript:newField('" + field + "','action')");
    }

    link.appendChild(linkText);

    var fieldname = "new_" + field;
    var fieldDiv = document.getElementById(fieldname);
    var parentDiv = fieldDiv.parentNode;

    // replace existing node sp2 with the new span txtbox sp1
    parentDiv.replaceChild(link, fieldDiv);
}

function uploadFile(file) {

    var xhr = new XMLHttpRequest();
    if (xhr.upload) {

        // create progress bar
        var o = $id("progress");
        var progress = o.appendChild(document.createElement("p"));
        progress.appendChild(document.createTextNode("upload " + file.name));


        // progress bar
        xhr.upload.addEventListener("progress", function(e) {
            var pc = parseInt(100 - (e.loaded / e.total * 100));
            progress.style.backgroundPosition = pc + "% 0";
        }, false);

        // file received/failed
        xhr.onreadystatechange = function(e) {
            if (xhr.readyState == 4) {
                progress.className = (xhr.status == 200 ? "success" : "failure");
            }
        };


        // start upload
        xhr.open("POST", $id("upload").action, true);
        xhr.setRequestHeader("X-FILENAME", file.name);
        xhr.send(file);

    }

}
