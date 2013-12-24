

      jQuery(document).ready(function (jQuery) {
        "use strict";
        jQuery('.wp-notify-box').perfectScrollbar();
      });





       jQuery(document).ready(function(){
            
            jQuery(document).bind('mousemove', function(e){
           jQuery('#wp-notify .wp-notify-single-box[original-title]').css({
               left:  e.pageX + 10,
               top:   e.pageY + 10,
            });
        });
          
        });





	var currentDate = new Date()
	var seconds = currentDate.getSeconds()
	var minutes = currentDate.getMinutes()
	var hours = currentDate.getHours()
	var day = currentDate.getDate()
	var month = currentDate.getMonth() + 1
	var year = currentDate.getFullYear()
	var clientdate = year + "-" + month + "-" + day + " "+ hours+":"+ minutes+":"+ seconds;
	document.cookie='wp_notify_client_date_time='+clientdate;



jQuery(document).ready(function(){
	
	jQuery("#wp-notify-black").click(function()
	{
		
		jQuery("#wp-notify-comments-box").hide();
		jQuery(this).hide();
	});
	
	jQuery(".wp-notify-single-box").mouseenter(function()
	{
		var commentid = jQuery(this).attr("commentid");
		
		jQuery(".single-tooltip-"+commentid).css("display","block");

	});
	jQuery(".wp-notify-single-box").mouseleave(function()
	{
		var commentid = jQuery(this).attr("commentid");
		
		jQuery(".single-tooltip-"+commentid).hide();
	});



	
	jQuery("#wp-notify-comments").click(function()
	{
		jQuery("#wp-notify-comments-box").toggle(); 
		jQuery("#wp-notify-black").css("display","block"); 
	});
	
	
	//is user logged

	
	jQuery(".wp-notify-single-box").click(function()
	{
	var islogged = jQuery("#wp-notify").attr("logged");


	});	
	
	
	
	
	
	
	
	
	
	
// count comment and if 0 then hide bubble

	var count = jQuery(".wp-notify-bubble").text();
	
	if(count<=0)
		{
			jQuery(".wp-notify-bubble").css("display","none"); 
		}
	else
		{
			jQuery(".wp-notify-bubble").css("display","block");
		}
	
	
	
	
	// database insert 
	
	jQuery(".wp-notify-single-box").click(function(){


		var count_current = jQuery(".wp-notify-bubble").text();
		if(count_current>=0)
			{
				jQuery(".wp-notify-bubble").css("display","block"); 
			}





	var commentid = jQuery(this).attr("commentid");
	var viewed = jQuery(this).attr("viewed");






   
	if(viewed=="viewed")
		{
			jQuery(this).removeClass("viewed");
			jQuery(this).addClass("unviewed");
			jQuery(this).attr('viewed', 'unviewed');
			var t=parseInt(count_current)+1;
			
			jQuery(".wp-notify-bubble").text(t);
			
		if(t<1)
			{
				jQuery(".wp-notify-bubble").css("display","none"); 
			}
		
		}
	else if(viewed=="unviewed")
		{
			jQuery(this).removeClass("unviewed");
			jQuery(this).addClass("viewed");
			jQuery(this).attr('viewed', 'viewed');
			var t=parseInt(count_current)-1;
			
			jQuery(".wp-notify-bubble").text(t);
		if(t<1)
			{
				jQuery(".wp-notify-bubble").css("display","none"); 
			}
			
		}
	
jQuery.ajax({
type: 'POST',
url: MyAjax.ajaxurl,
data: {"action": "update_viewed_unviewed", "commentid":commentid, "viewed":viewed},
success: function(data){

}
});


});

});


