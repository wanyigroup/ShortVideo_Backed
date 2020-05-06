/*
hui 星级评论组件
作者 : 深海  5213606@qq.com
官网 : http://hui.hcoder.net/
*/
function huiStar(selector){
	this.dom          = hui(selector); 
	this.starNum      = 5;
	this.size         = 35;
	this.color        = '#CCCCCC';
	this.colorActive  = '#F9BE66';
	var _self         = this;
	this.draw  = function(){
		if(this.dom.length < 1){return ;}
		var starHtml = '';
		for(var i = 0; i < this.starNum; i++){
			starHtml += '<div class="hui-fl hui-icons hui-icons-star" style="font-size:'+this.size+'px; color:' + this.color + ';" starVal="'+(i+1)+'"></div>';
		}
		this.dom.html(starHtml);
		this.dom.find('div').click(function(){
			var starVal  = this.getAttribute('starVal');
			var stars = _self.dom.find('div');
			stars.css({color : _self.color});
			_self.dom.attr('starVal', starVal);
			for(var i = 0; i < starVal; i++){
				stars.dom[i].style.color = _self.colorActive;
			}
			if(_self.change){_self.change(starVal);}
		});
	}
	this.getVal  = function(){
		var starVal  = this.dom.attr('starVal');
		if(!starVal){return 0;}
		return Number(starVal);
	}
}