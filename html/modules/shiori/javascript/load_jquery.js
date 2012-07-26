if(typeof jQuery == "undefined"){

	var shiori_script = document.getElementById("shiori_load_jquery");
	var shiori_url = shiori_script.getAttribute("src").replace(/load_jquery\.js/, '');
	var shiori_jquery_url = shiori_url + 'jquery-1.3.2.min.js';

	var script = document.createElement('script');
	script.src = shiori_jquery_url;
	script.type = 'text/javascript';
	script.defer = true;
	document.getElementsByTagName('head').item(0).appendChild(script);
}

