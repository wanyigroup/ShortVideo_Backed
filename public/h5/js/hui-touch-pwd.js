/*
hui 手势密码
作者 : 深海 5213606@qq.com
官网 : http://hui.hcoder.net/
*/
function huiTouchPwd(sets, callBack){
	this.sets = sets;
	this.init = function(){
		var wrap = document.getElementById(this.sets.wrapDiv);
		var html = '<canvas id="huiH5LookCanvas" width="'+this.sets.width+'" height="'+this.sets.height+'" display:inline-block;"></canvas>';
		wrap.innerHTML = html;
		this.canvas = document.getElementById('huiH5LookCanvas');
		this.ctx = this.canvas.getContext('2d');
		this.createCircle();
		var self = this;
		this.canvas.addEventListener("touchstart", function(e) {
			e.preventDefault();
			var po = self.getPosition(e);
			for (var i = 0; i < self.arr.length; i++) {
				if (Math.abs(po.x - self.arr[i].x) < self.r && Math.abs(po.y - self.arr[i].y) < self.r){
					self.touchStart = {x:self.arr[i].x, y:self.arr[i].y};
					self.lastPoint.push(self.arr[i]);
					self.drawPoint();
					break;
				}
			}
		}, false);
		this.canvas.addEventListener("touchmove", function(e){
			if(self.touchStart){self.move(self.getPosition(e));}
		}, false);
		this.canvas.addEventListener("touchend", function(e){
			if(!self.touchStart){return false;}
			self.touchStart = null;
			var pwd = '';
			for(var i = 0; i < self.lastPoint.length; i++){
				pwd += self.lastPoint[i].index;
			}
			callBack(pwd);
			setTimeout(function(){self.createCircle();},300);
		}, false);
	}
	this.move = function(po){
		this.ctx.clearRect(0, 0, this.ctx.canvas.width, this.ctx.canvas.height);
		for(var i = 0; i < this.arr.length; i++){this.drawCle(this.arr[i].x, this.arr[i].y);}
		this.drawPoint();
		this.drawLine(po);
		for(var i = 0; i < this.arr.length; i++) {
			var pt = this.arr[i];
			if (Math.abs(po.x - pt.x) < this.r && Math.abs(po.y - pt.y) < this.r){
				if(this.lastPoint[this.lastPoint.length - 1] != pt){this.lastPoint.push(pt);}
				this.drawPoint();
				break;
			}
		}
	}
	//绘制线条
	this.drawLine = function(po) {
		this.ctx.beginPath();
		this.ctx.lineWidth = 2;
		this.ctx.strokeStyle = '#3388FF';
		this.ctx.moveTo(this.touchStart.x, this.touchStart.y);
		for (var i = 1; i < this.lastPoint.length; i++) {this.ctx.lineTo(this.lastPoint[i].x, this.lastPoint[i].y);}
		this.ctx.lineTo(po.x, po.y);
		this.ctx.stroke();
		this.ctx.closePath();
	}
	//绘制圆心
	this.drawPoint = function(x, y) {
		for(var i = 0; i < this.lastPoint.length; i++) {
			this.ctx.fillStyle = '#3399FF';
			this.ctx.beginPath();
			this.ctx.arc(this.lastPoint[i].x, this.lastPoint[i].y, this.r / 2, 0, Math.PI * 2, true);
			this.ctx.closePath();
			this.ctx.fill();
		}
	}
	//获取touch点相对于canvas的坐标
	this.getPosition = function(e) {
		var rect = e.currentTarget.getBoundingClientRect();
		return {x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top};
	}
	this.createCircle = function(){
		var n = this.sets.pointNum;
		var count = 0;
		this.r = this.ctx.canvas.width / (2 + 4 * n);
		this.lastPoint = [];
		this.arr = [];
		var r = this.r;
		for (var i = 0; i < n; i++) {
			for (var j = 0; j < n; j++) {
				count++;
				var obj = {
					x: j * 4 * r + 3 * r,
					y: i * 4 * r + 3 * r,
					index: count
				};
				this.arr.push(obj);
			}
		}
		this.ctx.clearRect(0, 0, this.ctx.canvas.width, this.ctx.canvas.height);
		for(var i = 0; i < this.arr.length; i++){this.drawCle(this.arr[i].x, this.arr[i].y);}
	}
	this.drawCle = function(x, y){
		this.ctx.strokeStyle = '#CCC';
		this.ctx.lineWidth = 2;
		this.ctx.beginPath();
		this.ctx.arc(x, y, this.r, 0, Math.PI * 2, true);
		this.ctx.closePath();
		this.ctx.stroke();
	}
	this.init();
}