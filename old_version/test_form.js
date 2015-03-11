/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var W3CDOM = (document.getElementsByTagName && document.createElement);

window.onload = function () {
	document.forms[0].onsubmit = function () {
		return validate()
	}
}

function validate() {
	validForm = true;
	firstError = null;
	errorstring = '';
	var x = document.forms[0].elements;
	for (var i=0;i<x.length;i++) {
		if (!x[i].value)
			writeError(x[i],'This field is required');
	}
	if (x['email'].value.indexOf('@') == -1)
		writeError(x['email'],'This is not a valid email address');
	if (!W3CDOM)
		alert(errorstring);
	if (firstError)
		firstError.focus();
	if (validForm)
		alert('All data is valid!');
	return false;
}

function writeError(obj,message) {
	validForm = false;
	if (obj.hasError) return;
	if (W3CDOM) {
		obj.className += ' error';
		obj.onchange = removeError;
		var sp = document.createElement('span');
		sp.className = 'error';
		sp.appendChild(document.createTextNode(message));
		obj.parentNode.appendChild(sp);
		obj.hasError = sp;
	}
	else {
		errorstring += obj.name + ': ' + message + '\n';
		obj.hasError = true;
	}
	if (!firstError)
		firstError = obj;
}

function removeError()
{
	this.className = this.className.substring(0,this.className.lastIndexOf(' '));
	this.parentNode.removeChild(this.hasError);
	this.hasError = null;
	this.onchange = null;
}