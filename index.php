<?php
/*
Plugin Name: Kento Notify
Plugin URI: http://kentothemes.com
Description: Get notified by bubble like facebook if some posted comment on post.
Version: 1.0
Author: KentoThemes
Author URI: http://kentothemes.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function kento_vote_latest_jquery() {
	wp_enqueue_script('jquery');

	
}
add_action('init', 'kento_vote_latest_jquery');




//Include Javascript library
wp_enqueue_script('inkthemes', plugins_url( '/js/demo.js' , __FILE__ ) , array( 'jquery' ));
wp_enqueue_script('jquery-mousewheel', plugins_url( '/js/jquery.mousewheel.js' , __FILE__ ));
wp_enqueue_script('perfect-scrollbar', plugins_url( '/js/perfect-scrollbar.js' , __FILE__ ));

// including ajax script in the plugin Myajax.ajaxurl
wp_localize_script( 'inkthemes', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));

define('KENTO_VOTE_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );


wp_enqueue_style('kento-vote-style', KENTO_VOTE_PLUGIN_PATH.'css/style.css');
wp_enqueue_style('perfect-scrollbar', KENTO_VOTE_PLUGIN_PATH.'css/perfect-scrollbar.css');




register_activation_hook(__FILE__, kento_notify_install());

function kento_notify_install()
	{
		global $wpdb;
		$sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "kento_notify"
					 ."( UNIQUE KEY id (id),
						id int(100) NOT NULL AUTO_INCREMENT,
						userid  int(10) NOT NULL,
						commentid  int(10) NOT NULL,
						viewed  varchar(20) NOT NULL)";
			$wpdb->query($sql);

	}







function is_user_logged($is_logged_in)

	{
		if ( is_user_logged_in() )
		return "looged";
		else
		return "notlooged";

	}




function get_diff_date_time($comment_date)

	{

		
		
		$current_date = $_COOKIE['wp_notify_client_date_time'];
		
		$time_one = new DateTime($comment_date);
		$time_two = new DateTime($current_date);
		$difference = $time_one->diff($time_two);
		
		$is24 = $difference->format('%d');

		if($is24 < 1){
			return $difference->format('%h hours %i minutes %s seconds')." ago";
			
			}
		else {
			return $difference->format('%m month %d day')." ago";
			
			}
	

	}



function is_viewed($comment_id)
	{
		global $wpdb;
		$userid = get_current_user_id();
    	$table = $wpdb->prefix . "kento_notify";
		
		$result = $wpdb->get_results("SELECT * FROM $table WHERE userid = '$userid' AND commentid = '$comment_id'", ARRAY_A);
		$viewed_status = $result[0]['viewed'];
		
		if($viewed_status == "viewed" )
			{
			return	$viewed_status;
			}
			
		elseif($viewed_status == "unviewed")
			{
			return $viewed_status;
			}
		else
			{
			return "unviewed";
			}
		
	}

function count_unviewed()
	{
		global $wpdb;
		$userid = get_current_user_id();
		
    	$table = $wpdb->prefix . "comments";
		$wpdb->get_results("SELECT * FROM $table WHERE comment_approved = 1 ", ARRAY_A);		
		$total_comments = $wpdb->num_rows;
		
    	$table = $wpdb->prefix . "kento_notify";
		$wpdb->get_results("SELECT * FROM $table WHERE viewed = 'viewed' AND userid=$userid", ARRAY_A);		
		$total_viewed = $wpdb->num_rows;
		
		$total_unviewed = $total_comments - $total_viewed;
		

					return $total_unviewed;


	}




function update_viewed_unviewed()
	{
		$userid = get_current_user_id();
		
		
		$commentid = $_POST['commentid'];
		$viewed =  $_POST['viewed'];
					
		global $wpdb;
		$table = $wpdb->prefix . "kento_notify";
		
		$result = $wpdb->get_results("SELECT * FROM $table WHERE userid = '$userid' AND commentid = '$commentid'", ARRAY_A);
		$is_viewed = $result[0]['viewed'];
		$is_exist = $wpdb->num_rows;
		if($is_exist > 0 )
			{
				if($is_viewed=="viewed")
					{
						
						$wpdb->update( 
							$table,array( 'viewed' => 'unviewed'), 
							array( 'commentid' => $commentid, 'userid' => $userid ), 
							array('%s',), 
							array( '%d','%d' )
						);		
					}
				elseif($is_viewed=="unviewed")
					{	
						
						
						$wpdb->update( 
							$table,array( 'viewed' => 'viewed' ), 
							array( 'commentid' => $commentid,'userid' => $userid ), 
							array('%s',), 
							array( '%d','%d' ) 
						);
					}	
				
				
			}
		else{
				if($viewed=="viewed")
					{
						$wpdb->insert( 
							$table, 
							array( 'id' => '', 'userid' => $userid, 'commentid' => $commentid,'viewed' => 'unviewed'), 
							array( 	'%d','%d','%d','%s')
						);
					}
				elseif($viewed=="unviewed")
					{
						$wpdb->insert( 
							$table, 
							array( 'id' => '', 'userid' => $userid, 'commentid' => $commentid,'viewed' => 'viewed'), 
							array( 	'%d','%d','%d','%s')
						);
					}
				
				
			}
	
	
	}



add_action('wp_ajax_update_viewed_unviewed', 'update_viewed_unviewed');
add_action('wp_ajax_nopriv_update_viewed_unviewed', 'update_viewed_unviewed');







function wp_notify_insert()
	{

    global $wpdb;
    $table = $wpdb->prefix . "comments";
		$result = $wpdb->get_results("SELECT * FROM $table WHERE comment_approved = 1  ORDER BY comment_ID DESC LIMIT 20 ", ARRAY_A);
		$total_comments = $wpdb->num_rows;
		$wp_notify.= "<div class='wp-notify-box' >";
		for($i=0; $i<$total_comments; $i++)
		
			{	$comment_author = $result[$i]['comment_author'];
			
				$user_id = $result[$i]['user_id'];
				$comment_author_email = $result[$i]['comment_author_email'];
				
				$comment_content = $result[$i]['comment_content'];
				$comment_post_ID = $result[$i]['comment_post_ID'];
				$comment_ID = $result[$i]['comment_ID'];
				$comment_date = $result[$i]['comment_date'];
				$timezone = date_default_timezone_get();


				
				
				$wp_notify.= "<div original-title='".$comment_content."' class='wp-notify-single-box ".is_viewed($comment_ID)."' commentid='".$comment_ID."' viewed='".is_viewed($comment_ID)."'><div class='wp-notify-who'>".get_avatar($comment_author_email,100)."";
				$wp_notify.= "<div class='wp-notify-comment-single single-tooltip-".$comment_ID."'  '>".$comment_content."</div>";
				$wp_notify.= "<strong>".$comment_author."</strong> Commented on ";
				$wp_notify.= "<strong><a href='".get_permalink( $comment_post_ID )."#comment-".$comment_ID."'>".get_the_title($comment_post_ID)."</a></strong><span class='wp-notify-date'>".get_diff_date_time($comment_date)."<span></div>";
				
				$wp_notify.= "</div><div class='clear'></div>";
				

			}
$wp_notify.= "</div>";
		
		return $wp_notify;
		
		
		
		
		
		
		

		
		
		

	// $wpdb->query("INSERT INTO $table VALUES('',$postid,1,0)");
	


	



	die();
	return true;

	}








function kento_notify(){





$wp_notify.=  "<div id='wp-notify' class='wp-notify' >";
$wp_notify.= "<div class='wp-notify-bubble'>".count_unviewed()."</div>";
$wp_notify.= "<div id='wp-notify-comments'></div>";
$wp_notify.= "<div id='wp-notify-comments-box'>".wp_notify_insert()."</div>";
$wp_notify.=  "</div>";
$wp_notify.= "<div id='wp-notify-black'></div>";

echo $wp_notify;



}




























?>