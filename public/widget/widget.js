//domain_url = "http://127.0.0.1:8001";
//domain_url = "https://www.bookdrhock.com";
domain_url = "https://fandomz.org";

function fandomz_widget_load()
{
	$('body').find('.fandomz-poll-widget').each(function(){
		$this = $(this);
		getSlug =$this.attr('data-slug');
		getLang =$this.attr('data-lang');
		$.ajax({
	        url: domain_url+"/pollwidget/getlist/"+getSlug,
	        type: "get",
	        async:false,	        
	        success: function(response) {
	         	$this.html(response);   
	        }
	    });
	});
}
$(document).ready(function () {
	fandomz_widget_load();
});	