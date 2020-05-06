/*
hui 瀑布流组件
作者 : 深海
官网 : http://hui.hcoder.net/
*/
function huiWaterfall(idSelector){
	this.Waterfall = hui(idSelector);
	if(this.Waterfall.length != 1){
		console.log('请使用id选择器');
		return false;
	}
	this.WaterfallLeft = hui('#hui-water-fall-left');
	if(this.WaterfallLeft.length < 1){
		var div = document.createElement('div');
		div.setAttribute('id', 'hui-water-fall-left');
		this.Waterfall.dom[0].appendChild(div);
		this.WaterfallLeft = hui('#hui-water-fall-left');
	}
	this.WaterfallRight = hui('#hui-water-fall-right');
	if(this.WaterfallRight.length < 1){
		var div = document.createElement('div');
		div.setAttribute('id', 'hui-water-fall-right');
		this.Waterfall.dom[0].appendChild(div);
		this.WaterfallRight = hui('#hui-water-fall-right');
	}
	this.WaterTmp = hui('#HUI_WaterTmp');
	if(this.WaterTmp.length < 1){
		var div = document.createElement('div');
		div.setAttribute('id', 'hui-water-tmp');
		this.Waterfall.dom[0].appendChild(div);
		this.WaterTmp = hui('#hui-water-tmp');
	}
	this.addItems = function(doms){
		this.WaterTmp.html(doms);
		var items = this.WaterTmp.find('.hui-water-items');
		for(var i = 0; i < items.length; i++){
			if(i % 2 != 0){
				hui(items.dom[i]).appendTo(this.WaterfallRight);
			}else{
				hui(items.dom[i]).appendTo(this.WaterfallLeft);
			}
		}
	}
}