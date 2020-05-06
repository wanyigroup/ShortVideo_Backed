/*
hui 下拉刷新、上拉加载更多组件
作者 : 深海  5213606@qq.com
官网 : http://www.hcoder.net/hui
*/
hui.refreshY = 0, hui.refreshIng = false, hui.refreshTitle, hui.refreshIcon1, hui.refreshNumber = 0, hui.loadMoreText = '';
hui.refresh = function(selector, func, icons1, icons2, loading){
	if(!icons1){icons1 = '<span class="hui-icons hui-icons-down"></span>继续下拉刷新';}
	hui.refreshIcon1 = icons1;
	if(!icons2){icons2 = '<span class="hui-icons hui-icons-up"></span>释放立即刷新';}
	if(!loading){loading = '<div class="hui-loading-wrap"><div class="hui-loading" style="margin:18px 5px 0px 0px;"></div><div class="hui-loading-text">加载中</div></div>';}
	var dom = hui(selector); hui.refreshTitle = dom.find('.hui-refresh-icon');
	hui.refreshTitle.html(icons1);
	var huiRefreshStartY = 0, winInfo;
	dom.dom[0].addEventListener('touchstart',function(e){
		hui.refreshY = 0;
		winInfo = hui.winInfo();
		huiRefreshStartY = e.touches[0].clientY;
	}, false);
	dom.dom[0].addEventListener('touchmove',function(e){
		if(winInfo.scrollTop > 1){return false;}
		hui.refreshY = e.changedTouches[0].clientY - huiRefreshStartY;
		hui.refreshY  = hui.refreshY / 4;
		if(hui.refreshY < 1 || hui.refreshY >= 60){return;}
		if(hui.refreshY >= 50){hui.refreshTitle.html(icons2);}
		hui.refreshTitle.css({'marginTop' : (hui.refreshY - 60) + 'px'});
	}, false);
	dom.dom[0].addEventListener('touchend',function(e){
		if(hui.refreshIng){return false;}
		if(winInfo.scrollTop > 1){return false;}
		if(hui.refreshY >= 50){
			hui.refreshIng = true;
			hui.refreshTitle.html(loading);
			hui.refreshNumber++;
			func();
		}else{
			hui.refreshIng = false;
			hui.refreshTitle.css({'marginTop' :'-60px'});
		}
	}, false);
	hui.refreshIng = true;
	func();
}
hui.endRefresh = function(){
	hui.refreshIng = false;
	hui.refreshTitle.css({'marginTop' : '-60px'});
	hui.refreshTitle.html(hui.refreshIcon1);
}
/* 上拉加载更多 */
hui.loadMoreEnd = false;
hui.loadMore = function(func, title, loading){
	if(!title){title = '<span class="hui-icons hui-icons-up"></span>上拉加载更多';}
	if(!loading){loading = '<div class="hui-loading-wrap"><div class="hui-loading" style="margin:8px 5px 0px 0px;"></div><div class="hui-loading-text">加载中</div></div>';}
	hui.loadMoreText = title;
	var dom = hui('#hui-load-more'), winInfo = hui.winInfo();
	if(dom.length < 1){
		dom = document.createElement('div');
		dom.setAttribute('id', 'hui-load-more');
		dom.innerHTML = title;
		document.body.appendChild(dom);
		dom = hui('#hui-load-more');
	}
	var loadMoreTimer = null;
	hui.onScroll(function(e){
		if(hui.refreshIng || hui.loadMoreEnd){return false;}
		if(loadMoreTimer != null){clearTimeout(loadMoreTimer);}
		loadMoreTimer = setTimeout(function(){
			var sets = dom.offset();
			if(sets.top < e + winInfo.height){
				hui.refreshIng = true;
				dom.html(loading);
				func();
			}
		},200);
	});
}
hui.endLoadMore = function(isEnd, endMsg){
	if(!endMsg){endMsg = '已经加载全部';}
	var dom = hui('#hui-load-more');
	if(isEnd){dom.html(endMsg); hui.loadMoreEnd = true;}
	hui.refreshIng = false;
}
hui.resetLoadMore = function(){
	hui.loadMoreEnd = false;
	hui('#hui-load-more').html(hui.loadMoreText);
}