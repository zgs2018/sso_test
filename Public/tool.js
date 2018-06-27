var utily = {}
/**
 * 存储localStorage
 */
utily.setStore = function(name, content){
	if (!name) return;
	if (typeof content !== 'string') {
		content = JSON.stringify(content);
	}
	window.localStorage.setItem(name, content);
}

/**
 * 获取localStorage
 */
utily.getStore = function(name){
	if (!name) return;
	return window.localStorage.getItem(name);
}

/**
 * 删除localStorage
 */
utily.removeStore = function(name){
	if (!name) return;
	window.localStorage.removeItem(name);
}

utily.logout = function(refresh){
    utily.removeStore('xy_nickname')
    utily.removeStore('xy_headpic')
    if (refresh) {
    	location.reload()
    }
}