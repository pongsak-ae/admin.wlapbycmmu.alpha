(function($){
	$.fn.placeholder = function(){
		this.each(function() {
			var label = $(this).prev();
			label.css({
				"display": "block",
			   	"position": "absolute",
			   	"padding-left": "3px"
			}).click(function(){
				$(this).next().focus();
			});
			var me = this;
			// console.log(this,$(this).val());
			if($.trim(this.value) != "") label.hide();

			$(this).keypress(function(){
				if (this.value == "") {
					label.hide();
				}
			});

			$(this).bind("keyup change",function(){
				if (me.value == "") {
					label.show();
				}else{
					label.hide();
				}
			});

			setInterval(function  () {
				// console.log("setInterval" , Math.random());
				if (me.value == "") {
					label.show();
				}else{
					label.hide();
				}
			},200);
			var interval = null
			$(this).focus(function(){
				if(this.value ==""){
					label.fadeTo(300,0.3);
				}
				interval = setInterval(function  () {
					// console.log("setInterval" , Math.random());
					if (me.value == "") {
						label.show();
					}else{
						label.hide();
					}
				},200);
			});

			$(this).blur(function(){
				clearInterval(interval);
				if(this.value == ""){
					label.fadeTo(300,1);
				}
			});

		});
	};
})(jQuery);