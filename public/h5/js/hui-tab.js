/*
hui 选项卡组件
作者 : 深海  5213606@qq.com
官网 : http://hui.hcoder.net/
*/
hui.tab = function(selector){
	var tabLists = hui(selector);
	for(var i = 0; i < tabLists.length; i++){var tabRun = new huiTabListBase(tabLists.dom[i]);}
}
huiTabListBase = function(dom){
	this.swipe       = hui(dom);
	this.swipeIn     = this.swipe.find('.hui-tab-body-items')
	this.items       = this.swipe.find('.hui-tab-item');
	this.itemSize    = this.items.length;
	this.scale       = 1 / this.itemSize;
	this.swipeIn.css({width: this.itemSize * 100 +'%'});
	this.items.css({width: this.scale * 100 +'%'});
	this.width       = this.swipe.width();
	this.index       = 0;
	this.titles      = this.swipe.find('.hui-tab-title');
	this.titles.find('div').css({width: this.scale * 100 + '%'});
	this.titles.find('div').first().addClass('hui-tab-active');
	this.titles.find('div').click(function(){
		_self.index  = hui(this).index()
		_self.changeTo();
	});
	var _self = this;
	//监测滑动
	this.swipeIn.swipe(function (e){
		this.isMove    = true;
		if(_self.index >= _self.itemSize - 1 && e.deltaX < 0){
			this.isMove    = false;
			return false;
		}else if(_self.index == 0 && e.deltaX > 0){
			this.isMove    = false;
			return false;
		}
		_self.moveScale  = e.deltaX / _self.width * - 1 * _self.scale * 1.5;
		_self.moveScale += (_self.index) * _self.scale;
		_self.swipeIn.dom[0].style.transform  = 'translate3d('+ (_self.moveScale * -100) +'%, 0px, 0px)';
	});
	this.swipeIn.swipeEnd(function(e){
		if(!this.isMove){return false;}
		_self.index  = Math.round(_self.moveScale / _self.scale);
		_self.changeTo();
	});
	this.changeTo = function(){
		_self.swipeIn.dom[0].style.transform  = 'translate3d('+ (_self.scale * _self.index * -100) +'%, 0px, 0px)';
		_self.swipeIn.dom[0].style.transition = 'linear 300ms';
		setTimeout(function(){_self.swipeIn.dom[0].style.transition = 'none';}, 300);
		_self.titles.find('div').removeClass('hui-tab-active');
		_self.titles.find('div').eq(_self.index).addClass('hui-tab-active');
	};
}