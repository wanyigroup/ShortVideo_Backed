/*
hui popover message 
作者 : 深海  5213606@qq.com
官网 : http://hui.hcoder.net/
*/
hui.extend('popoverMsg' ,function(directionX, directionY, msg, width, height, addSets){
	if(this.length < 1){return;}
	if(!directionX){directionX = 'left';}
	if(!directionY){directionY = 'down';}
	if(!addSets){addSets = {left:0, top:0};}
	if(!width){width = this.width();}
	if(!height){height = 'auto';}else{height += 'px';}
	var thisObj = this;
	this.dom[0].onclick = function(){
		hui.maskShow();
		var HUI_PopoverMsg = document.getElementById('hui-popover-msg');
		if(!HUI_PopoverMsg){
			HUI_PopoverMsg = document.createElement('div');
			HUI_PopoverMsg.setAttribute('id', 'hui-popover-msg');
			document.body.appendChild(HUI_PopoverMsg);
		}
		HUI_PopoverMsg.style.width = width + 'px';
		var  heightStyle = '', sets = thisObj.offset();
		if(height != 'auto'){heightStyle = ' style="height:'+height+'; overflow-Y:auto;"'}
		if(directionX == 'left' && directionY == 'down'){
			HUI_PopoverMsg.innerHTML = '<div><div class="hui-arrow-up"></div></div><div id="hui-popover-msg-text"'+heightStyle+'>'+msg+'</div>';
			HUI_PopoverMsg.style.top  = sets.top + thisObj.height()+ addSets.top + 'px';
			HUI_PopoverMsg.style.left = sets.left + addSets.left + 'px';
		}else if(directionX == 'right' && directionY == 'down'){
			HUI_PopoverMsg.innerHTML = '<div><div class="hui-arrow-up hui-fr"></div></div><div id="hui-popover-msg-text"'+heightStyle+'>'+msg+'</div>';
			HUI_PopoverMsg.style.top  = sets.top + thisObj.height()+ addSets.top + 'px';
			HUI_PopoverMsg.style.left = sets.left + thisObj.width() -width + addSets.left + 'px';
		}else if(directionX == 'left' && directionY == 'up'){
			HUI_PopoverMsg.innerHTML = '<div id="hui-popover-msg-text"'+heightStyle+'>'+msg+'</div><div><div class="hui-arrow-down"></div></div>';
			HUI_PopoverMsg.style.top  = sets.top - hui(HUI_PopoverMsg).height() + addSets.top + 'px';
			HUI_PopoverMsg.style.left = sets.left + addSets.left + 'px';
		}else if(directionX == 'right' && directionY == 'up'){
			HUI_PopoverMsg.innerHTML = '<div id="hui-popover-msg-text"'+heightStyle+'>'+msg+'</div><div><div class="hui-arrow-down hui-fr"></div></div>';
			HUI_PopoverMsg.style.top  = sets.top - hui(HUI_PopoverMsg).height() + addSets.top + 'px';
			HUI_PopoverMsg.style.left = sets.left + thisObj.width() -width + addSets.left + 'px';
		}
		document.body.appendChild(HUI_PopoverMsg);
		hui('#hui-mask').click(function(){hui.maskHide(); hui(HUI_PopoverMsg).remove();});
	};
});