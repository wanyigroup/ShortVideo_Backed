/*
hui 表单元素初始及表单验证插件
作者 : 深海  5213606@qq.com
官网 : http://www.hcoder.net/hui
*/
hui.formInit = function(){hui.formTextClear(); hui.formPwdEye();};
hui.formTextClear = function(){
	var textClears = document.getElementsByClassName('hui-input-clear');
	if(textClears.length < 1){return;}
	for(var i = 0; i < textClears.length; i++){
		textClears[i].onkeyup = function(){
			var HuiInputsClear = document.getElementById('hui-input-clear');
			if(!HuiInputsClear){
				HuiInputsClearDiv = document.createElement("div");
				HuiInputsClearDiv.setAttribute("id", 'hui-input-clear')
				document.body.appendChild(HuiInputsClearDiv);
			}
			HuiInputsClear = document.getElementById('hui-input-clear');
			this.parentNode.appendChild(HuiInputsClear);
			var thisObj = this; HuiInputsClear.style.display = 'block';
			HuiInputsClear.onclick = function(){thisObj.value = ''; this.style.display = 'none';}
		}
	}
};
hui.formPwdEye = function(){
	var eyes = document.getElementsByClassName('hui-pwd-eye');
	if(eyes.length < 1){return;}
	for(var i = 0; i < eyes.length; i++){
		var eye = document.createElement('div');
		eye.setAttribute('class', 'hui-pwd-eyes');
		eye.setAttribute('onclick', 'hui.eyesChange(this);');
		document.body.appendChild(eye);
		hui(eye).appendTo(hui(eyes[i]).parent());
	}
};
hui.eyesChange = function(o){
	var _selfDom = hui(o), _inputDom = _selfDom.parent().find('.hui-input').eq(0);
	if(_selfDom.hasClass('hui-pwd-eyes-sed')){
		_selfDom.removeClass('hui-pwd-eyes-sed');
		_inputDom.dom[0].type     = 'password';
	}else{
		_selfDom.addClass('hui-pwd-eyes-sed');
		_inputDom.dom[0].setAttribute('type', 'text');
	}
};
function huiFormCheck(selector){
	var formIn  = hui(selector);
	if(formIn.length != 1){return true;}
	var inputs = formIn.find('input'), selects = formIn.find('select'), textareas = formIn.find('textarea'); res = true;
	for(var i = 0; i < inputs.dom.length; i++){res = huiFormCheckBase(inputs.dom[i]); if(!res){break;}}
	if(res){for(var i = 0; i < selects.dom.length; i++){res = huiFormCheckBase(selects.dom[i]); if(!res){break;}}}
	if(res){for(var i = 0; i < textareas.dom.length; i++){res = huiFormCheckBase(textareas.dom[i]); if(!res){break;}}}
	if(typeof(huiFormCheckAttach) != 'undefined' && res){if(!huiFormCheckAttach()){return false;}}
	return res;
}
function huiFormCheckBase(obj){
	var checkType  = obj.getAttribute('checkType');
	if(!checkType){return true;}
	var checkData  = obj.getAttribute('checkData');
	var checkMsg   = obj.getAttribute('checkMsg');
	if(!checkMsg){return true;}
	var checkVal   = obj.value;
	switch(checkType){
		case 'string' : 
			checkVal = checkVal.trim();
			var reg  = new RegExp('^.{'+checkData+'}$');
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'int' :
			var reg  = new RegExp('^\-?[0-9]{'+checkData+'}$');
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
			var reg2 = new RegExp('^\-?0+[0-9]+$');
			if(reg2.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'between' : 
			if(!huiFormCheckNumber(checkVal, checkData, checkMsg)){return false;}
		break;
		case 'betweenD' : 
			var reg  = new RegExp('^\-?[0-9]+$');
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
			if(!huiFormCheckNumber(checkVal, checkData, checkMsg)){return false;}
		break;
		case 'betweenF' : 
			var reg  = new RegExp('^\-?[0-9]+\.[0-9]+$');
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
			if(!huiFormCheckNumber(checkVal, checkData, checkMsg)){return false;}
		break;
		case 'same' : 
			if(checkVal != checkData){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'sameWithId' : 
			if(checkVal != hui('#'+checkData).val()){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'notSame' : 
			if(checkVal == checkData){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'notSameWithId' :
			if(checkVal == hui(checkData).val()){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'email' : 
			var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'phone' :
			var reg = /^1[0-9]{10}$/;
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'url'  :
			var reg = /^(\w+:\/\/)?\w+(\.\w+)+.*$/;
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'zipcode'  :
			var reg = /^[0-9]{6}$/;
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'reg'  : 
			var reg = new RegExp(checkData);
			if(!reg.test(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
		break;
		case 'fun'  : 
			eval('var res = '+checkData+'("'+checkVal+'");');
			if(!res){return huiFormCheckShowErrMsg(checkMsg);}
		break;
	}
	return true;
}
function huiFormCheckNumber(checkVal, checkData, checkMsg){
	checkVal = Number(checkVal); if(isNaN(checkVal)){return huiFormCheckShowErrMsg(checkMsg);}
	checkDataArray = checkData.split(',');
	if(checkDataArray[0] == ''){
		if(checkVal > Number(checkDataArray[1])){return huiFormCheckShowErrMsg(checkMsg);}
	}else if(checkDataArray[1] == ''){
		if(checkVal < Number(checkDataArray[0])){return huiFormCheckShowErrMsg(checkMsg);}
	}else{
		if(checkVal < Number(checkDataArray[0]) || checkVal > Number(checkDataArray[1])){return huiFormCheckShowErrMsg(checkMsg);}
	}
	return true;
}
function huiFormCheckShowErrMsg(checkMsg){hui.toast(checkMsg); return false;}
hui.getFormData = function(formId, reType){
	hui.formTpmValForChecked = {};
	if(formId.substr(0, 1) == '#'){formId = formId.substr(1, formId.length - 1);}
	if(reType == undefined){reType = 'obj';}
	var elements = hui.formGetElements(formId);
	var queryComponents = new Array();
	var returnObj = {};
	for (var i = 0; i < elements.length; i++) {
		var queryComponent = hui.serializeElement(elements[i]);
		if(queryComponent){queryComponents.push(queryComponent);}
	}
	var restr = queryComponents.join('&');
	if(reType != 'obj'){return restr;}
	var arrExplode = restr.split('&');
	for(var i = 0; i < arrExplode.length; i++){
		var cArr = arrExplode[i].split('=');
		eval('returnObj.'+cArr[0]+' = decodeURIComponent(cArr[1]);');
	}
	return returnObj;
}
hui.serializeElement = function(elementObj){
	var method = elementObj.tagName.toLowerCase();
	var parameter = hui.getInputs(elementObj, method);
	if(parameter){
		if(parameter[0].substr(-2) == '[]'){
			var subName = parameter[0].substr(0, parameter[0].length - 2);
			if(hui.formTpmValForChecked.subName != undefined){
				hui.formTpmValForChecked.subName += ','+parameter[1];
			}else{
				hui.formTpmValForChecked.subName = parameter[1];
			}
			var key = subName;
			return key + '=' + encodeURIComponent(hui.formTpmValForChecked.subName);
		}
		var key = encodeURIComponent(parameter[0]);
		return key + '=' + encodeURIComponent(parameter[1]);
	}else{
		return false;
	}
}

hui.getInputs = function(elementObj, method) {
	if(elementObj.name == ''){return false;}
	if(method == 'textarea' || method == 'select'){return [elementObj.name, elementObj.value];}
	switch (elementObj.type.toLowerCase()){  
		case 'submit':
		case 'hidden':
	   	case 'password':
		case 'text':
		case 'number':
		case 'email':
		case 'tel':
		case 'url':
		return [elementObj.name, elementObj.value];  
		case 'checkbox':
		case 'radio':
		return hui.inputSelector(elementObj);  
	}
	return false;
}

hui.inputSelector = function(elementObj){
	if(elementObj.checked){return [elementObj.name, elementObj.value];}
	return false;
}

hui.formGetElements = function (formId) {  
	var form = document.getElementById(formId);
	var elements = new Array();
	var tagElements = form.getElementsByTagName('input');
	for (var j = 0; j < tagElements.length; j++){elements.push(tagElements[j]);}
	tagElements = form.getElementsByTagName('textarea');
	for (var j = 0; j < tagElements.length; j++){elements.push(tagElements[j]);}
	tagElements = form.getElementsByTagName('select');
	for (var j = 0; j < tagElements.length; j++){elements.push(tagElements[j]);}
	return elements;
}