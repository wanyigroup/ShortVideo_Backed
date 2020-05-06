/*
hui 下拉菜单美化组件
作者 : 深海  5213606@qq.com
官网 : http://www.hcoder.net/hui
*/
hui.extend('selectBeautify', function(callBack, isIcon){
	if(this.length != 1){return false;} if(typeof(isIcon) == 'undefined'){isIcon = true;}
	this.hide();
	this.selectParent = this.parent();
	this.selectHtml = this.dom[0].options[this.dom[0].selectedIndex].text;
	var div = document.createElement('div');
	div.innerHTML = this.selectHtml+' <span class="hui-icons hui-icons-down2"></span>';
	hui(div).appendTo(this.selectParent);
	this.selectMenuParent = hui('#hui-select-beautify');
	if(this.selectMenuParent.length < 1){
		var dom = document.createElement('div')
		dom.setAttribute('id', 'hui-select-beautify');
		document.body.appendChild(dom);
		this.selectMenuParent = hui('#hui-select-beautify');
	}
	var thisObj = this;
	this.selectParent.click(function(){
		var sets = hui.offset(this), heigth = hui(this).height(true);
		thisObj.selectMenuParent.css({top:(sets.top + heigth)+'px'});
		var selectListHtml = '<div><ul>';
		var sedIndexVal = thisObj.dom[0].selectedIndex;
		for(var i = 0; i < thisObj.dom[0].options.length; i++){
			if(i == sedIndexVal && isIcon){
				selectListHtml += '<li class="hui-select-beautify-sed" liIndex="'+i+'">'+thisObj.dom[0].options[i].text+'</li>';
			}else{
				selectListHtml += '<li liIndex="'+i+'">'+thisObj.dom[0].options[i].text+'</li>';
			}
		}
		selectListHtml += '</ul></div>';
		thisObj.selectMenuParent.html(selectListHtml);
		thisObj.selectMenuParent.show();
		var lis = thisObj.selectMenuParent.find('li');
		lis.click(function(){
			var cIndex = Number(this.getAttribute('liIndex'));
			thisObj.dom[0].selectedIndex  = cIndex;
			thisObj.selectParent.find('div').html(thisObj.dom[0].options[cIndex].text + ' <span class="hui-icons hui-icons-down2"></span>');
			thisObj.selectMenuParent.hide();
			if(callBack){callBack(thisObj.dom[0].value);}
		});
		hui('#hui-mask').click(function(){
			thisObj.selectMenuParent.hide();
		});
	});
});