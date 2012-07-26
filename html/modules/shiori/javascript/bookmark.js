if(typeof jQuery != "undefined"){
	$(document).ready(function(){
		var star = $("#shiori_bookmark_star");
		var star_url = star.attr("src");
		var xoops_url = star_url.replace(/\/modules\/.*/, '');
		var image_url = star_url.replace(/\/(un)*bookmarked\.png$/, '');
		var shiori_dir = star_url.replace(/^.+\/modules\/([^\/]+)\/.*$/, '$1');
		var shiori_url = xoops_url + '/modules/' + shiori_dir;
		var title = document.title;
		var url = location.href;
	
		$.post(shiori_url + '/index.php?controller=ajax', { url: url },
			function(data){
				if ( data == 1 )
				{
					star.attr("src", image_url + '/bookmarked.png');
				}
				star.show();
			},
			'json'
		);
	
	
		star.click(function(){
		
			if ( $("#shiori_bookmark_star").attr("src").match(/\/bookmarked\.png$/) )
			{
				var bookmarked = true;
			}
			else
			{
				var bookmarked = false;
			}
		
			star.attr("src", image_url + '/loading.gif');
	
			if ( bookmarked )
			{
				$.post(shiori_url + "/index.php?controller=ajax&action=ticket", {},
					function(data){
						$.post(shiori_url + "/index.php?controller=ajax&action=delete", { url: url, ticket: data },
							function(data)
							{
								if ( data == "ERROR" )
								{
									star.attr("src", image_url + '/error.png');
									setTimeout(function(){
										star.attr("src", image_url + '/bookmarked.png');
									}, 1500
									);
								}
								else
								{
									star.attr("src", image_url + '/unbookmarked.png');
								}
							},
							"json"
						);
					},
					"json"
				);
			}
			else
			{
				$.post(shiori_url + "/index.php?action=form", { url: url, title: title },
					function(data){
						$.post(shiori_url + "/index.php?action=save",
							$(data).find("#shiori_form").serialize(),
							function(data)
							{
								star.attr("src", image_url + '/bookmarked.png');
							}
						);
					}
				);
			}
	
			return false;
		});
	});
}