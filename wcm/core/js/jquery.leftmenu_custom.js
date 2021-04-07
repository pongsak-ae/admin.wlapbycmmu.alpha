(function($){
	
	$.fn.initLeftMenu = function(parameters){
		var defaults = {
			width: 169,
			height: 350,
			content: '.pageContent',
			title: '.header_section .title',
			rootText: 'Root'
		}
		$.extend(defaults, parameters);
		
		function newStructure($target_html, $target_rel)
		{
			var new_html = '';
			new_html += '<ul class="dd-node" rel="'+$target_rel+'">';
			new_html += '<li class="dd-breadcrumb">'+'<a href="#" rel="root" class="current">' + defaults.rootText + '</a>'+'</li>';
			new_html += '<li class="dd-node-data"><ul>'+$target_html.html()+'</ul></li>';
			new_html += '</ul>';
			return $(new_html).appendTo('.drilldown');
		}
		
		var activeElement = -1;
		function listChild($obj)
		{
			var childElement = $obj.find('.dd-node-data').children().children('li');	// find all child element(li) of current obj(ul)
			
			childElement.each(function(i){
				var current_element_rel = $(this).attr('rel');					// get current rel to setup reletion
				var current_element_src = $(this).children('a').attr('href');	// get current src
				var current_element_name = $(this).children('a').html();		// get current name to setup breadcrumb
				var current_element_ul = $(this).children('ul');				// find next node (ul)
				haveUL = current_element_ul.length;								// is this element have next node (ul) ???

				if( haveUL ){
					// rebuild new structure
					make_new = newStructure(current_element_ul, current_element_rel);
					
					// rebuild new breadcrumb
					make_breadcrumb = $obj.find('.dd-breadcrumb').html();
					make_breadcrumb+= '<a href="'+current_element_src+'" rel="'+current_element_rel+'">'+current_element_name+'</a>';
					make_new.find('.dd-breadcrumb')
						.html(make_breadcrumb)
						.find('a').bind('click', function(){
							var current_node = $(this).parents('ul.dd-node');
							var current_node_rel = $(this).attr('rel');
							var prev_node = $('.drilldown ul.dd-node[rel='+current_node_rel+']');
							
							if(current_element_rel!=current_node_rel){
								prev_node.show()
									.css({ left: -1*defaults.width })
									.animate({ left: 0 }, 'fast', '', function(){ current_node.hide(); });
								current_node.animate({ left: defaults.width }, 'fast');
							}
							return false;
						})
						.each(function(){ $(this).removeClass('current'); }).end()
						.find('a:last-child').addClass('current');
						
					
					$(this).children('a')
						.bind('click',function(){
							var current_node = $(this).parents('ul.dd-node'); 				// get current node
							var current_node_rel = $(this).parent().attr('rel'); 	 			// get current rel
							var next_node = $('.drilldown ul.dd-node[rel='+current_node_rel+']'); 	// get next node
							
							// set animate
							next_node.show()
								.css({ left: defaults.width })
								.animate({ left: 0 }, 'fast', '', function(){ current_node.hide(); });
							current_node.animate({ left: -1*defaults.width }, 'fast');
							return false;
						}) 
						.addClass('next-button'); 		// set up next-button class
					
					current_element_ul.remove();		// remove last element
					
					// store the current active rel
					if($(this).children('a').hasClass('active')){
						activeElement = current_element_rel;
					}
					
					// display the current active class
					if(activeElement == current_element_rel && activeElement){
						make_new.show();
					}					
					
					listChild( make_new );
				} else {
					if($(this).children('a').hasClass('active')){
						activeElement = $(this).parents('.dd-node').attr('rel');
						$(this).parents('.dd-node').show();
						$(this).children('a').addClass('now-selected');
					}
				}
			});
		}
			
		return this.each(function(options){
			var target = $(this);
			
			// set position
			target.addClass('drilldown')
				.css({ width: defaults.width, height: defaults.height })
				.children().addClass('drilldown-root')
				.show().end();
				
			// register target li's reletion
			target.children()					// div.drilldown > ul
				.find('li').each(function(i){ 	        // div.drilldown > ul > li
					$(this).attr('rel', i); 
				});
				
			var new_html = newStructure( target.children(), 'root');
			
			// remove old structure
			target.children('.drilldown-root').remove();
			listChild(new_html);

			if(activeElement < 0) new_html.show();
		});
	};
	
})(jQuery);