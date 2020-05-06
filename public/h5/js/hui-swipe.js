/*
hui 轮播组件
作者 : 深海  5213606@qq.com
官网 : http://hui.hcoder.net/
*/
function huiSwipe(selector){
	this.swipe       = hui(selector);
	this.swipeIn     = this.swipe.find('.hui-swipe-items')
	this.items       = this.swipe.find('.hui-swipe-item');
	this.itemSize    = this.items.length;
	this.realSize    = this.items.length + 2;
	this.scale       = 1 / this.realSize;
	this.swipeIn.css({width: this.realSize * 100 +'%'});
	this.items.css({width: this.scale * 100 +'%'});
	this.width       = this.swipe.width();
	this.index       = 1;
	this.speed       = 1000;
	this.delay       = 5000;
	this.timer       = null;
	this.indicatorOn = true;
	this.autoPlay    = true;
	var _self = this;
	var lastItem     = this.items.last();
	this.items.eq(0).clone().appendTo(this.swipeIn);
	lastItem.clone().prependTo(this.swipeIn);
	this.items       = this.swipe.find('.hui-swipe-item');
	this.swipeIn.css({transform : 'translate3d(' + this.scale * -100 +'%, 0px, 0px)'});
	/* 进度标示 */
	this.indicator  = this.swipe.find('.hui-swipe-indicator');
	if(this.indicator.length < 1){
		var indicatorDom = document.createElement('div');
		indicatorDom.setAttribute('class', 'hui-swipe-indicator');
		var html = '<div class="hui-fr">';
		for(var i = 0; i < this.itemSize; i++){html += '<div class="hui-swipe-indicators"></div>';}
		indicatorDom.innerHTML = html + '</div>';
		hui(indicatorDom).appendTo(this.swipe);
	}
	this.indicator  = this.swipe.find('.hui-swipe-indicator');
	this.indicator.find('.hui-swipe-indicators').eq(0).addClass('hui-swipe-indicator-active');
	this.items.show();
	this.changeIndicator = function(index){
		setTimeout(function(){
			_self.indicator.find('.hui-swipe-indicators').removeClass('hui-swipe-indicator-active');
			_self.indicator.find('.hui-swipe-indicators').eq(index - 1).addClass('hui-swipe-indicator-active');
		}, 500);
	};
	//监测滑动
	this.swpieMove = 0;
	this.swipe.swipe(function(e){
		if(_self.timer){clearTimeout(_self.timer);}
		_self.moveScale  = e.deltaX / _self.width * - 1 * _self.scale * 1.5;
		_self.moveScale += (_self.index) * _self.scale;
		_self.swipeIn.dom[0].style.transform  = 'translate3d('+ (_self.moveScale * -100) +'%, 0px, 0px)';
	});
	this.swipe.swipeEnd(function(e){
		_self.index  = Math.round(_self.moveScale / _self.scale);
		_self.change();
	});
	this.change = function(){
		if(_self.timer){clearTimeout(_self.timer);}
		_self.swipeIn.dom[0].style.transform  = 'translate3d('+ (_self.scale * _self.index * -100) +'%, 0px, 0px)';
		_self.swipeIn.dom[0].style.transition = 'linear 300ms';
		setTimeout(function(){_self.swipeIn.dom[0].style.transition = 'none';}, 300);
		if(_self.index < 1){
			_self.index = this.itemSize;
			setTimeout(function(){
				_self.swipeIn.dom[0].style.transform  = 'translate3d('+ (_self.scale * _self.index * -100) +'%, 0px, 0px)';
				_self.swipeIn.dom[0].style.transition = 'none';
			}, 200);
			_self.changeIndicator(_self.index);
		}else if(_self.index > _self.itemSize){
			_self.index = 1;
			setTimeout(function(){
				_self.swipeIn.dom[0].style.transform  = 'translate3d('+ (_self.scale * _self.index * -100) +'%, 0px, 0px)';
				_self.swipeIn.dom[0].style.transition = 'none';
			}, 200);
			_self.changeIndicator(_self.index);
		}else{
			_self.changeIndicator(_self.index);
		}
		if(_self.autoPlay){_self.timer = setTimeout(function(){_self.index++; _self.change();}, _self.delay);}
	};
	this.run = function(){
		if(this.autoPlay){this.timer = setTimeout(_self.change, _self.delay);}
		if(this.indicatorOn){this.indicator.show();}
	}
}
//补充一个函数解决旧版本的命名错误，使用 huiSwipe 可以删除本行
function huiSwpie(selector){return new huiSwipe(selector);}