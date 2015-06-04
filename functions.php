<?php
error_reporting(0);
include "config/config.php";
require_once ('libs/phpthumb/ThumbLib.inc.php');
require_once ('libs/resize.php');
require_once ('libs/watermark.class.php');

function httpify($link)
{
	if (preg_match("#https?://#", $link) === 0)
    $link = 'http://'.$link;
	return $link;
}
function isImage($path)
{
    $a = getimagesize($path);
    $image_type = $a[2];   
    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG)))
    {
        return true;
    }
    return false;
}
function authenticate($username,$password)
{
	$username = mysql_real_escape_string($username);
	$password = md5($password);
	$sql = "select email from settings where username='$username' and password='$password'";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0)
	return true;
	return false;
}
function send_email($from,$to,$subject,$body)
{
	    // from the form
       $subject = trim(strip_tags($subject));
       $email = trim(strip_tags($from));
       $message = htmlentities($body);
        $admin=get_admin();
        $title=get_title();
       $too = $to;

      
    $body = "<html><body><p face='Georgia, Times' color='red'>";
    $body .= "<p>Hello " . $admin . ",</p>";
    $body .= "<p>" . $message . "</p>";
    $body .= "<p>---</p>";
    $body .= "<p>" . $title . "</p></p></body></html>";
    


       $headers = "From: " . get_title() . "<" . $from . "> \r\nReply-To: " . $from."\r\n";
       $headers .= "Return-Path: .$from.\r\n";
       $headers  .= "MIME-Version: 1.0\r\n";
       $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
       


       // send the email
       mail($too, $subject, $body, $headers);
}
function reset_pass($email)
{
	$email = mysql_real_escape_string($email);
	$match_user = "select username from settings where email='$email'"; 
	$qry_user = mysql_query($match_user);
	$row_user = mysql_fetch_array($qry_user);
	$username = $row_user['username'];
	$password = md5(genPassword());
	send_email(get_admin_email(),$email,"Password Received","Your Login Details Updated\nUsername: " . $username . "\nYour new password is: " . $password . "\nLogin Here: " . rootpath() . "/admin");
	$update_query = "UPDATE settings SET password='$password' WHERE email='$email'";
	$qry = mysql_query($update_query);
}
function genPassword() 
{
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;
    while ($i <= 8) 
	{
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}
function update_media_settings($title_length,$posts_per_page,$related_posts,$allow_voting,$auto_approve,$allow_pictures,$allow_videos,$allow_gifs,$show_nav)
{
	$update_query = "UPDATE media_setting 
						SET title_length='$title_length',
							posts_per_page='$posts_per_page',
								related_posts=' $related_posts',
									allow_voting='$allow_voting',
										auto_approve='$auto_approve',
											allow_pictures='$allow_pictures',
												allow_videos='$allow_videos',
													allow_gifs='$allow_gifs',
														show_nav='$show_nav'";
	mysql_query($update_query);
}
function valid_color_code($color)
{
	if(preg_match('/^#[a-f0-9]{6}$/i', $color))
	return true;
	else
	return false;
}
function post_title_length()
{
	$sql = "select title_length from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['title_length'];
}
function posts_per_page()
{
	$sql = "select posts_per_page from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['posts_per_page'];
}
function related_posts_count()
{
	$sql = "select related_posts from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['related_posts'];
}
function voting_allowed()
{
	$sql = "select allow_voting from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['allow_voting'];
}
function show_nav()
{
	$sql = "select show_nav from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['show_nav'];
}
function auto_approve()
{
	$sql = "select auto_approve from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['auto_approve'];
}
function allow_pictures()
{
	$sql = "select allow_pictures from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['allow_pictures'];
}
function allow_videos()
{
	$sql = "select allow_videos from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['allow_videos'];
}
function allow_gifs()
{
	$sql = "select allow_gifs from media_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['allow_gifs'];
}
function thumb_width()
{
	$sql = "select width from thumbnails_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['width'];
}
function thumb_height()
{
	$sql = "select height from thumbnails_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['height'];
}
function thumb_quality()
{
	$sql = "select quality from thumbnails_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['quality']==1)
		return 100;
	else if($fetch['quality']==2)
		return 85;
	else if($fetch['quality']==3)
		return 70;
	else
		return 100;
}
function logo_format()
{
	$sql = "select logo_format from settings";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['logo_format'];
}
function get_logo()
{
$sql = "select logo from settings";
$query = mysql_query($sql);
$fetch = mysql_fetch_array($query);
return $fetch['logo'];
}
function get_favicon()
{
	$sql = "select favicon from settings";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['favicon'];
}
function guest_submission_enabled()
{
	$sql = "select enable_submission from settings";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['enable_submission'];
}
function thumb_watermark_enabled()
{
	$sql = "select watermark from thumbnails_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['watermark'];
}
function image_watermark_enabled()
{
	$sql = "select enabled from watermark_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['enabled'];
}
function image_watermark_opacity()
{
	$sql = "select opacity from watermark_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['opacity'];
}
function image_watermark_text()
{
	$sql = "select text from watermark_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['text'];
}
function image_watermark_fontsize()
{
	$sql = "select font_size from watermark_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['font_size'];
}
function image_watermark_color()
{
	$sql = "select color from watermark_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['color'];
}
function gif_watermark_image()
{
	$sql = "select gif_watermark from thumbnails_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['gif_watermark'];
}
function vid_watermark_image()
{
	$sql = "select vid_watermark from thumbnails_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['vid_watermark'];
}
function image_watermark_position()
{
	$sql = "select position from watermark_setting";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['position'];
}
function update_thumbs_setting($width,$height,$quality,$watermark_enabled,$gif_watermark_url,$vid_watermark_url)
{
	$update_query = "UPDATE thumbnails_setting SET width='". $width ."',height='".$height."',quality='". $quality ."',watermark='" . $watermark_enabled . "',gif_watermark='" . $gif_watermark_url . "',vid_watermark='" . $vid_watermark_url . "'";
	mysql_query($update_query);
}
function get_contact_email()
{
	$query = "SELECT contact_email from settings";
	$qry = mysql_query($query);
	$fetch = mysql_fetch_array($qry);
	if(trim($fetch["contact_email"]))
	return $fetch["contact_email"];
	else
	return get_admin_email();
}
function is_alpha($val)
{
	return (bool)preg_match("/^([0-9a-zA-Z ])+$/i", $val);
}
function checkEmail($email)
{
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}
function get_logo_text()
{
	$sql = "select logo_text from settings";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['logo_text'];
}
function get_admin()
{
	$sql = "select username from settings";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['username'];
}
function get_admin_email()
{
	$sql = "select email from settings";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['email'];
}
function get_analytics_code()
{
	$sql = "select tracking_code from analytics";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['tracking_code'];
}
function rootpath()
{
	$sql = "select rootpath from settings";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['rootpath']!="")
	{
		return $fetch['rootpath'];
	}
	else
	{
		$server = $_SERVER['SERVER_NAME'];
		$root = 'http://' . $server . dirname($_SERVER['SCRIPT_NAME']);
		if(substr($root, -1)=="/")
		return ('http://' . $server);
		else
		return $root;
	}
}
function already_exists($permalink)
{
	$sql = "select id from media where permalink='" . $permalink . "'";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['id'])
	return true;
	else
	return false;
}
function curPageURL() 
{
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else
	{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function has_min_size($img)
{
	list($width, $height) = getimagesize($img);
	if($width<thumb_width() || $height<thumb_height())
	return false;
	return true;
}
function count_votes($permalink)
{
	$permalink = mysql_real_escape_string($permalink);
	$match = "update media set votes=votes+1 where permalink='$permalink'"; 
	mysql_query($match);
}
function return_votes($permalink)
{
	$permalink = mysql_real_escape_string($permalink);
	$sql = "select votes from media where permalink ='$permalink'";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['votes'])
	return $fetch['votes'];
	else
	return 0;
}
function count_views($permalink)
{
	$permalink = mysql_real_escape_string($permalink);
	$select = "select views from media where permalink='$permalink'";
	$query = mysql_query($select);
	$fetch = mysql_fetch_array($query);
	$oldval = $fetch['views'];
	$newval = $oldval+1;
	$match = "update media set views=$newval where permalink='$permalink'"; 
	mysql_query($match);
}
function return_views($permalink)
{
	$permalink = mysql_real_escape_string($permalink);
	$sql = "select views from media where permalink ='$permalink'";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['views'])
	return $fetch['views'];
	else
	return 0;
}
function update_ads($leaderboard1,$leaderboard2,$medrec)
{

	$update_query = "UPDATE ads SET leaderboard1='".mysql_real_escape_string($leaderboard1)."',leaderboard2='".mysql_real_escape_string($leaderboard2)."',rectangle='".mysql_real_escape_string($medrec)."'";
	mysql_query($update_query);
}

function show_rectangle_ad()
{
	$match_ad= "select rectangle from ads limit 1"; 
	$qry_ad = mysql_query($match_ad);
	$row_ad = mysql_fetch_array($qry_ad);
	return ($row_ad['rectangle']);
}

function show_leaderboard1_ad()
{
	$match_ad= "select leaderboard1 from ads limit 1"; 
	$qry_ad = mysql_query($match_ad);
	$row_ad = mysql_fetch_array($qry_ad);
	return ($row_ad['leaderboard1']);
}

function show_leaderboard2_ad()
{
	$match_ad= "select leaderboard2 from ads limit 1"; 
	$qry_ad = mysql_query($match_ad);
	$row_ad = mysql_fetch_array($qry_ad);
	return ($row_ad['leaderboard2']);
}

function next_prev_link($id)
{
	$id = mysql_real_escape_string($id);
	$sql = "select * from media where approved=1 and orderid>$id order by orderid ASC limit 1";
	$query = mysql_query($sql);
	$fetch=mysql_fetch_array($query);
	$next_permalink = $fetch['permalink'];
	$next_title = $fetch['title'];
	$next_type = $fetch['type'];
	if($next_type==0)
	$next_type="picture";
	else if($next_type==1)
	$next_type="video";
	else if($next_type==2)
	$next_type="gif";
	else
	$next_type="picture";
	if($next_title=="")
	{
	$sql = "select * from media where orderid=(select min(orderid) from media where approved=1)";
	$query = mysql_query($sql);
	$fetch=mysql_fetch_array($query);
	$next_permalink = $fetch['permalink'];
	$next_title = $fetch['title'];
	$next_type = $fetch['type'];
	if($next_type==0)
	$next_type="picture";
	else if($next_type==1)
	$next_type="video";
	else if($next_type==2)
	$next_type="gif";
	}
	$sql = "select * from media where approved=1 and orderid<$id order by orderid DESC limit 1";
	$query = mysql_query($sql);
	$fetch=mysql_fetch_array($query);
	$prev_permalink = $fetch['permalink'];
	$prev_title = $fetch['title'];
	$prev_type = $fetch['type'];
	if($prev_type==0)
	$prev_type="picture";
	else if($prev_type==1)
	$prev_type="video";
	else if($prev_type==2)
	$prev_type="gif";
	else
	$prev_type="picture";
	if($prev_title=="")
	{
	$sql = "select * from media where orderid=(select max(orderid) from media where approved=1)";
	$query = mysql_query($sql);
	$fetch=mysql_fetch_array($query);
	$prev_permalink = $fetch['permalink'];
	$prev_title = $fetch['title'];
	$prev_type = $fetch['type'];
	if($prev_type==0)
	$prev_type="picture";
	else if($prev_type==1)
	$prev_type="video";
	else if($prev_type==2)
	$prev_type="gif";
	else
	$prev_type="picture";
	}
	return array($next_permalink,$next_title,$prev_permalink,$prev_title,$next_type,$prev_type);
}

function unicode_escape_sequences($str)
{
$working = json_encode($str); 
$working = preg_replace('/\\\u([0-9a-z]{4})/', '&#x$1;', $working); 
return json_decode($working); 
}
function add_picture_url($title,$description,$media,$source)
{
	$get_url = $media;
	$url = trim($get_url);
	$title=mysql_real_escape_string($title);
	if(mb_check_encoding($title,"UTF-8"))
	$title=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $title);
	$description=mysql_real_escape_string($description);
	$source=mysql_real_escape_string($source);
	if($source=="")
	{
		$source= getdomain($media);
	}
	
	if($url && isImage($url))
	{
		$file = fopen($url,"rb");
		$ext = end(explode(".",strtolower(basename($url))));
		if($ext=="gif" && !allow_gifs())
		return 0;
		else if($ext!="gif" && !allow_pictures())
		return 0;
		if(valid_file_extension($ext))
		{
			$filename = filename() . $ext;
			$newfile = fopen(dirname(__FILE__) . "/uploads/" . $filename, "wb");
			if($newfile)
			{
				while(!feof($file))
				{
					fwrite($newfile,fread($file,1024 * 8),1024 * 8);
				}
			}
		gen_thumbnail($filename);
		if(!has_min_size(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename))
		{
			$resize = new ResizeImage(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename);
			$resize->resizeTo(thumb_width(), thumb_height(), 'exact');
			$resize->saveImage(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename, thumb_quality());
		}
		if($ext!="gif" && image_watermark_enabled())
			add_watermark($filename);
		else if($ext=="gif" && thumb_watermark_enabled())
			add_gif_label($filename);
		$permalink=gen_permalink($title);
		if($ext=="gif")
			$type=2;
			else
			$type=0;
			$sql="INSERT INTO media(permalink,type,title,description,source,file,thumb,date,orderid,approved) VALUES('$permalink',$type,'$title','$description','$source','$filename','$filename','" . date('Y-m-d H:i:s') . "'," . gen_order_id(auto_approve()) . "," . auto_approve() . ")";
			$query=mysql_query($sql);
			return select_media_id($permalink);
		}
    }
	return false;
}

function gen_order_id($id)
{
	if($id)
	{
		$sql = "select max(orderid) as max_order from media";
		$query = mysql_query($sql);
		$fetch = mysql_fetch_array($query);
		return $fetch['max_order']+1;
	}
	return 0;
}
function add_picture_file($title,$description,$source)
{
	$file = $_FILES["myfile"]["name"];
	$ext = end(explode(".",strtolower($file)));
	if($ext=="gif" && !allow_gifs())
		return 0;
		else if($ext!="gif" && !allow_pictures())
		return 0;
	$title=mysql_real_escape_string($title);
	if(mb_check_encoding($title,"UTF-8"))
	$title=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $title);
	$description=mysql_real_escape_string($description);
	$source=mysql_real_escape_string($source);
	
	if(valid_file_extension($ext))
	{
		$filename=filename() . $ext;
		move_uploaded_file($_FILES["myfile"]["tmp_name"],dirname(__FILE__) . "/uploads/" . $filename);
		if(isImage(dirname(__FILE__) . "/uploads/" . $filename))
		{
			gen_thumbnail($filename);
			if($ext!="gif" && image_watermark_enabled())
			add_watermark($filename);
			else if($ext=="gif" && thumb_watermark_enabled())
			add_gif_label($filename);
			if(!has_min_size(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename))
			{
				$resize = new ResizeImage(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename);
				$resize->resizeTo(thumb_width(), thumb_height(), 'exact');
				$resize->saveImage(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename, thumb_quality());
			}
			$permalink=gen_permalink($title);
			if($ext=="gif")
			$type=2;
			else
			$type=0;
			$sql="INSERT INTO media(permalink,type,title,description,source,file,thumb,date,orderid,approved) VALUES('$permalink',$type,'$title','$description','$source','$filename','$filename','" . date('Y-m-d H:i:s') . "'," . gen_order_id(auto_approve()) . "," . auto_approve() . ")";
			$query=mysql_query($sql);
			return select_media_id($permalink);
		}
		else 
		{
			unlink(dirname(__FILE__) . "/uploads/" . $filename);
			return 0;
		}
	}
	return 0;
}
function regen_video_thumb($id,$media)
{
	if(valid_video_url($media))
	{
		$type=getdomain($media);
		if($type=="youtube.com")
		{
			$url=get_youtube_thumb($media);
		}
		else if($type=="vimeo.com")
		{
			$url=get_vimeo_thumb($media);
		}
		else if($type=="facebook.com")
		{
			$url=get_facebook_thumb($media);
		}
		else if($type=="vine.co")
		{
			$url=get_vine_thumb($media);
		}
		else if($type=="dailymotion.com")
		{
			$url=get_dailymotion_thumb($media);
			if(getRedirectUrl($url)=="http://s2.dmcdn.net/no-such-asset/x240-80X.jpg")
			$url="";
		}
		else if($type=="metacafe.com")
		{
			$url=get_metacafe_thumb($media);
		}
		$url = strtok($url, '?');
		if(!isImage($url))
		return 0;
		$filename = fetch_remote_image($url,$id);
	}
	if(!$filename==0)
	{
		if(!has_min_size(dirname(__FILE__) . "/uploads/" . $filename))
		{
			$resize = new ResizeImage(dirname(__FILE__) . "/uploads/" . $filename);
			$resize->resizeTo(thumb_width(), thumb_height(), 'exact');
			$resize->saveImage(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename, thumb_quality());
		}
		else
		{
			$options = array('jpegQuality' => thumb_quality());
			$thumb = PhpThumbFactory::create(dirname(__FILE__) . "/uploads/" . $filename,$options);
			$thumb->adaptiveResize(thumb_width(), thumb_height());
			$thumb->save(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename);
		}
		if(thumb_watermark_enabled())
			add_video_label($filename);
			$update_sql = "update media set thumb='" . $filename . "' where id=" . $id;
			mysql_query($update_sql);
			return $filename;
	}
	return 0;
}
function isGIF($path)
{
    $a = getimagesize($path);
    $image_type = $a[2];    
    if(in_array($image_type , array(IMAGETYPE_GIF)))
    {
        return true;
    }
    return false;
}
function regen_gif_thumb($id,$url,$new)
{
	if(!isGIF($url))
	return 0;
	if($new)
	{
		$url = strtok($url, '?');
		$filename = fetch_remote_image($url,$id);
	}
	else
	{
		$filename=strtolower(basename($url));
	}
	if(!$filename==0)
	{
		if(!has_min_size(dirname(__FILE__) . "/uploads/" . $filename))
		{
			$resize = new ResizeImage(dirname(__FILE__) . "/uploads/" . $filename);
			$resize->resizeTo(thumb_width(), thumb_height(), 'exact');
			$resize->saveImage(dirname(__FILE__) . "/uploads/thumbs/" . $filename, thumb_quality());
		}
		else
		{
			$options = array('jpegQuality' => thumb_quality());
			$thumb = PhpThumbFactory::create(dirname(__FILE__) . "/uploads/" . $filename,$options);
			$thumb->adaptiveResize(thumb_width(), thumb_height());
			$thumb->save(dirname(__FILE__) . "/uploads/thumbs/" . $filename);
		}
		if(thumb_watermark_enabled())
		add_gif_label($filename);
		$update_sql = "update media set thumb='" . $filename . "' where id=" . $id;
		mysql_query($update_sql);
		return $filename;
	}
	return 0;
}
function regen_pic_thumb($id,$url,$new)
{
	if(!isImage($url))
		return 0;
		if($new)
		{
			$url = strtok($url, '?');
			$filename = fetch_remote_image($url,$id);
		}
		else
		{
			$filename=strtolower(basename($url));
		}
		if(!$filename==0)
		{
			if(!has_min_size(dirname(__FILE__) . "/uploads/" . $filename))
			{
				$resize = new ResizeImage(dirname(__FILE__) . "/uploads/" . $filename);
				$resize->resizeTo(thumb_width(), thumb_height(), 'exact');
				$resize->saveImage(dirname(__FILE__) . "/uploads/thumbs/" . $filename, thumb_quality());
			}
			else
			{
				$options = array('jpegQuality' => thumb_quality());
				$thumb = PhpThumbFactory::create(dirname(__FILE__) . "/uploads/" . $filename,$options);
				$thumb->adaptiveResize(thumb_width(), thumb_height());
				$thumb->save(dirname(__FILE__) . "/uploads/thumbs/" . $filename);
			}
			$update_sql = "update media set file='" . $filename . "' where id=" . $id;
			mysql_query($update_sql);
			return $filename;
		}
		else
		return 0;
		return true;
}
function add_video($title,$description,$media,$source)
{
	if(valid_video_url($media))
	{
		$type=getdomain($media);
		
		$title=mysql_real_escape_string($title);
		if(!mb_check_encoding($title,"UTF-8"))
		$title=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $title);
		$description=mysql_real_escape_string($description);
		$source=mysql_real_escape_string($source);
		if($source=="")
		{
			$source= getdomain($media);
		}
		
		if($type=="youtube.com")
		{
			$url=get_youtube_thumb($media);
		}
		else if($type=="vimeo.com")
		{
			$url=get_vimeo_thumb($media);
		}
		else if($type=="facebook.com")
		{
			$url=get_facebook_thumb($media);
		}
		else if($type=="vine.co")
		{
			$url=get_vine_thumb($media);
		}
		else if($type=="dailymotion.com")
		{
			$url=get_dailymotion_thumb($media);
			if(getRedirectUrl($url)=="http://s2.dmcdn.net/no-such-asset/x240-80X.jpg")
				$url="";
		}
		else if($type=="metacafe.com")
		{
			$url=get_metacafe_thumb($media);
		}
		$url = strtok($url, '?');
		if(!isImage($url))
			return false;
			$filename = fetch_remote_image($url,0);
			if(!$filename==0)
			{
				if(!has_min_size(dirname(__FILE__) . "/uploads/" . $filename))
				{
					$resize = new ResizeImage(dirname(__FILE__) . "/uploads/" . $filename);
					$resize->resizeTo(thumb_width(), thumb_height(), 'exact');
					$resize->saveImage(dirname(__FILE__) . "/uploads/thumbs/" . $filename, thumb_quality());
				}
				else
				{
					$options = array('jpegQuality' => thumb_quality());
					$thumb = PhpThumbFactory::create(dirname(__FILE__) . "/uploads/" . $filename,$options);
					$thumb->adaptiveResize(thumb_width(), thumb_height());
					$thumb->save(dirname(__FILE__) . "/uploads/thumbs/" . $filename);
				}
				if(thumb_watermark_enabled())
					add_video_label($filename);
					$permalink=gen_permalink($title);
					$sql="INSERT INTO media(permalink,type,title,description,file,thumb,source,date,orderid,approved) VALUES('$permalink',1,'$title','$description','$media','$filename','$source','" . date('Y-m-d H:i:s') . "'," . gen_order_id(auto_approve()) . "," . auto_approve() . ")";
					mysql_query($sql) or die(mysql_error());
					return select_media_id($permalink);
			}
			else
				return false;
	}
	return true;
}
function update_social($facebook,$twitter,$google,$social_sharing)
{
	$update_query = "UPDATE social_profiles SET facebook='".$facebook."',twitter='".$twitter."',google='".$google."',social_sharing=" . $social_sharing;
	mysql_query($update_query);
}
function social_sharing_enabled()
{
	$match = "select social_sharing from social_profiles"; 
	$qry = mysql_query($match);
	$row = mysql_fetch_array($qry);
	return ($row['social_sharing']);
}
function get_twitter()
{
	$match = "select twitter from social_profiles"; 
	$qry = mysql_query($match);
	$row = mysql_fetch_array($qry);
	return ($row['twitter']);
}
function get_facebook()
{
	$match = "select facebook from social_profiles"; 
	$qry = mysql_query($match);
	$row = mysql_fetch_array($qry);
	return ($row['facebook']);
}
function get_google()
{
	$match = "select google from social_profiles"; 
	$qry = mysql_query($match);
	$row = mysql_fetch_array($qry);
	return ($row['google']);
}
function getRedirectUrl($url) 
{
    // initialize cURL
    $curl = curl_init($url);
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_FOLLOWLOCATION  => true,
    )); 
    // execute the request
    $result = curl_exec($curl);
    // fail if the request was not successful
    if ($result === false) {
        curl_close($curl);
        return null;
    }
    // extract the target url
    $redirectUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
    curl_close($curl);
    return $redirectUrl;
}
function fetch_remote_image($url,$regen)
{
	$url = getRedirectUrl($url);
	$file = fopen($url,"rb");
	$ext = end(explode(".",strtolower(basename($url))));
	if(valid_file_extension($ext))
	{
		if($regen)
			$filename=md5($regen) . "." . $ext;
		else
			$filename = filename() . $ext;
			$newfile = fopen(dirname(__FILE__) . "/uploads/" . $filename, "wb+");
		if($newfile)
		{
			while(!feof($file))
			{
				fwrite($newfile,fread($file,1024 * 8),1024 * 8);
			}
		}
		else return 0;
	}
	return $filename;
}
function get_embed_code($url)
{
	$type=getdomain($url);
	if($type=="facebook.com")
	{
		$queryString = parse_url($url, PHP_URL_QUERY);
		parse_str($queryString, $params);
		$embed_code = '<iframe src="https://www.facebook.com/video/embed?video_id=' . trim($params['v']) . '" width="640" height="480" frameborder="0"></iframe>';
	}
	else if($type=="vimeo.com")
	{
		preg_match('/(\d+)/', $url, $output);
		$id = $output[0];
		$embed_code = '<iframe src="http://player.vimeo.com/video/' . trim($id) . '" width="640" height="480" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
	}
	else if($type=="youtube.com")
	{
		$queryString = parse_url($url, PHP_URL_QUERY);
		parse_str($queryString, $params);
		$embed_code='<iframe title="YouTube video player" class="youtube-player" type="text/html" width="640" height="480" src="http://www.youtube.com/embed/' . trim($params['v']) . '" frameborder="0" allowFullScreen></iframe>';
	}
	else if($type=="vine.co")
	{
		$id = preg_replace('/^.*\//','',$url);
		$embed_code='<iframe class="vine-embed" src="https://vine.co/v/' . trim($id) . '/embed/simple?audio=1" width="620" height="620" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>';
	}
	else if($type=="dailymotion.com")
	{
		$output = parse_url($url);
		$url= $output['path'];
		$parts = explode('/',$url);
		$parts = explode('_',$parts[2]);
		$embed_code='<object width="640" height="480"><param name="movie" value="http://www.dailymotion.com/swf/video/' . trim($parts[0]) . '?background=493D27&foreground=E8D9AC&highlight=FFFFF0"></param><param name="allowFullScreen" value="true"></param>
		<param name="allowScriptAccess" value="always"></param><embed type="application/x-shockwave-flash" src="http://www.dailymotion.com/swf/video/' . trim($parts[0]) . '?background=493D27&foreground=E8D9AC&highlight=FFFFF0" width="560" height="315" allowfullscreen="true" allowscriptaccess="always"></embed></object>';
	}
	else if($type=="metacafe.com")
	{
		$path = parse_url($url, PHP_URL_PATH);
		$pieces = explode('/', $path);
		$id = $pieces[2];
		$embed_code='<iframe src="http://www.metacafe.com/embed/' . $id . '" width="640" height="480" allowFullScreen frameborder=0></iframe>';
	}
	return $embed_code;
}
function get_youtube_thumb($url)
{
	$queryString = parse_url($url, PHP_URL_QUERY);
	parse_str($queryString, $params);
	if (isset($params['v'])) 
	{
		return "http://i3.ytimg.com/vi/" . trim($params['v']) . "/mqdefault.jpg";
	}
	return true;
}
function get_vimeo_thumb($url)
{
	preg_match('/(\d+)/', $url, $output);
	$id = trim($output[0]);
	$data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
	$data = json_decode($data);
	return $data[0]->thumbnail_medium;
}
function get_facebook_thumb($url)
{
	$queryString = parse_url($url, PHP_URL_QUERY);
	parse_str($queryString, $params);
	return "http://graph.facebook.com/" . trim($params['v']) . "/picture";
}
function get_vine_thumb($url)
{
	$id = trim(preg_replace('/^.*\//','',$url));
	$vine = file_get_contents_curl("http://vine.co/v/" . $id);
	preg_match('/property="og:image" content="(.*?)"/', $vine, $matches);
	return ($matches[1]) ? $matches[1] : false;
}
function get_dailymotion_thumb($url)
{
	$output = parse_url($url);
	$url= $output['path'];
	$parts = explode('/',$url);
	$parts = explode('_',$parts[2]);
	$content = file_get_contents_curl("https://api.dailymotion.com/video/" . trim($parts[0]) ."?fields=thumbnail_large_url");
	$obj = json_decode($content,true);
	return $obj['thumbnail_large_url'];
}
function get_metacafe_thumb($url)
{
	$path = parse_url($url, PHP_URL_PATH);
	$pieces = explode('/', $path);
	$id = $pieces[2];
	$title = $pieces[3];
	if($title=="")
	$title = $id;
	if($id && $title)
	return "http://s4.mcstatic.com/thumb/{$id}/0/6/videos/0/6/{$title}.jpg";      
	else
	return "";
}
function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
function valid_video_url($url)
{
	$allowed = array("youtube.com", "vimeo.com","facebook.com","vine.co","dailymotion.com","metacafe.com");
	$viddom = getdomain($url);
	if (!arrcontains($viddom, $allowed))
	{
		return false;
	}
	return true;
}
function isalpha($val)
{
    return (bool)preg_match("/^([0-9a-zA-Z ])+$/i", $val);
}
function valid_file_extension($ext)
{
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	if (!in_array($ext, $allowedExts))
	{
		return false;
	}
	return true;
}
function valid_url($url)
{
	$validation = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) && (preg_match("#^http(s)?://[a-z0-9-_.]+\.[a-z]{2,4}#i", $url));
	if($validation)
		return true;
	else
	return false;
}
function arrcontains($str, array $arr)
{
    foreach($arr as $a) 
	{
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}
function filename()
{
	$sql = "select max(id) as maxid from media"; 
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return (md5($fetch['maxid']+1) . ".");
}
/*
 * PHP function to text-watermark an image
 * http://salman-w.blogspot.com/2008/11/watermark-your-images-with-text-using.html
 *
 * Writes the given text on a GD image resource using
 * the specified true-type font, size, color, etc
 */

define('WATERMARK_MARGIN_ADJUST', 5);
define('WATERMARK_FONT_REALPATH', dirname(__FILE__) . '/fonts/');

function render_text_on_gd_image(&$source_gd_image, $text, $font, $size, $color, $opacity, $rotation, $align)
{
    $source_width = imagesx($source_gd_image);
    $source_height = imagesy($source_gd_image);
    $bb = imagettfbbox_fixed($size, $rotation, $font, $text);
    $x0 = min($bb[0], $bb[2], $bb[4], $bb[6]) - WATERMARK_MARGIN_ADJUST;
    $x1 = max($bb[0], $bb[2], $bb[4], $bb[6]) + WATERMARK_MARGIN_ADJUST;
    $y0 = min($bb[1], $bb[3], $bb[5], $bb[7]) - WATERMARK_MARGIN_ADJUST;
    $y1 = max($bb[1], $bb[3], $bb[5], $bb[7]) + WATERMARK_MARGIN_ADJUST;
    $bb_width = abs($x1 - $x0);
    $bb_height = abs($y1 - $y0);
    switch ($align) {
        case 1:
            $bpy = -$y0;
            $bpx = -$x0;
            break;
        case 2:
            $bpy = -$y0;
            $bpx = $source_width / 2 - $bb_width / 2 - $x0;
            break;
        case 3:
            $bpy = -$y0;
            $bpx = $source_width - $x1;
            break;
        case 21:
            $bpy = $source_height / 2 - $bb_height / 2 - $y0;
            $bpx = -$x0;
            break;
        case 22:
            $bpy = $source_height / 2 - $bb_height / 2 - $y0;
            $bpx = $source_width / 2 - $bb_width / 2 - $x0;
            break;
        case 23:
            $bpy = $source_height / 2 - $bb_height / 2 - $y0;
            $bpx = $source_width - $x1;
            break;
        case 4:
            $bpy = $source_height - $y1;
            $bpx = -$x0;
            break;
        case 5:
            $bpy = $source_height - $y1;
            $bpx = $source_width / 2 - $bb_width / 2 - $x0;
            break;
        case 6;
            $bpy = $source_height - $y1;
            $bpx = $source_width - $x1;
            break;
    }
    $alpha_color = imagecolorallocatealpha(
        $source_gd_image,
        hexdec(substr($color, 0, 2)),
        hexdec(substr($color, 2, 2)),
        hexdec(substr($color, 4, 2)),
        127 * (100 - $opacity) / 100
    );
    return imagettftext($source_gd_image, $size, $rotation, $bpx, $bpy, $alpha_color, WATERMARK_FONT_REALPATH . $font, $text);
}

/*
 * Fix for the buggy imagettfbbox implementation in gd library
 */

function imagettfbbox_fixed($size, $rotation, $font, $text)
{
    $bb = imagettfbbox($size, 0, WATERMARK_FONT_REALPATH . $font, $text);
    $aa = deg2rad($rotation);
    $cc = cos($aa);
    $ss = sin($aa);
    $rr = array();
    for ($i = 0; $i < 7; $i += 2) {
        $rr[$i + 0] = round($bb[$i + 0] * $cc + $bb[$i + 1] * $ss);
        $rr[$i + 1] = round($bb[$i + 1] * $cc - $bb[$i + 0] * $ss);
    }
    return $rr;
}

/*
 * Wrapper function for opening file, calling watermark function and saving file
 */

define('WATERMARK_OUTPUT_QUALITY', 100);

function create_watermark_from_string($source_file_path, $output_file_path, $text, $font, $size, $color, $opacity, $rotation, $align)
{
    list($source_width, $source_height, $source_type) = getimagesize($source_file_path);
    if ($source_type === NULL)
	{
        return false;
    }
    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_file_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_file_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_file_path);
            break;
        default:
            return false;
    }
    render_text_on_gd_image($source_gd_image, $text, $font, $size, $color, $opacity, $rotation, $align);
    imagejpeg($source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY);
    imagedestroy($source_gd_image);
}

/*
 * Uploaded file processing function
 */
function process_image_upload($filename)
{
    $temp_file_name = $filename;
    $uploaded_file_path = dirname(__FILE__) . "/uploads/" . $temp_file_name;
    $processed_file_path = dirname(__FILE__) . "/uploads/" . $temp_file_name;
    /*
     * PARAMETER DESCRIPTION
     * (1) SOURCE FILE PATH
     * (2) OUTPUT FILE PATH
     * (3) THE TEXT TO RENDER
     * (4) FONT NAME -- MUST BE A *FILE* NAME
     * (5) FONT SIZE IN POINTS
     * (6) FONT COLOR AS A HEX STRING
     * (7) OPACITY -- 0 TO 100
     * (8) TEXT ANGLE -- 0 TO 360
     * (9) TEXT ALIGNMENT CODE -- POSSIBLE VALUES ARE 11, 12, 13, 21, 22, 23, 31, 32, 33
     */
    $result = create_watermark_from_string(
        $uploaded_file_path,
        $processed_file_path,
        image_watermark_text(),
        'arial.ttf',
        image_watermark_fontsize(),
        str_replace("#","",image_watermark_color()),
        image_watermark_opacity(),
        0,
        image_watermark_position()
    );
}
function add_watermark($filename)
{
	process_image_upload($filename);
	return true;
}
function add_gif_label($filename)
{
	$gif_watermark_image = dirname(__FILE__) . "/" . gif_watermark_image();
	$watermark_options = array(
				'watermark' 	=> $gif_watermark_image,
				'halign'		=> Watermark::ALIGN_CENTER,
				'valign'		=> Watermark::ALIGN_MIDDLE,			
				'type'			=> IMAGETYPE_GIF,);
	Watermark::output(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename, dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename, $watermark_options);
	return true;
}
function add_video_label($filename)
{
	$vid_watermark_image = dirname(__FILE__) . "/" . vid_watermark_image();
	$ext = end(explode(".",strtolower(basename($filename))));
	if($ext=="gif")
	$type = IMAGETYPE_GIF;
	else if($ext=="jpg")
	$type = IMAGETYPE_JPEG;
	else if($ext=="png")
	$type = IMAGETYPE_PNG;
	$watermark_options = array(
				'watermark' 	=> $vid_watermark_image,
				'halign'		=> Watermark::ALIGN_CENTER,
				'valign'		=> Watermark::ALIGN_MIDDLE,			
				'type'			=> $type,);
	Watermark::output(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename, dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename, $watermark_options);
	return true;
}



function gen_thumbnail($filename)
{
	$options = array('jpegQuality' => thumb_quality());
	$thumb = PhpThumbFactory::create(dirname(__FILE__) . "/uploads/" . $filename,$options);
	$thumb->adaptiveResize(thumb_width(), thumb_height());
	$thumb->save(dirname(__FILE__) . "/uploads/" . 'thumbs/' . $filename);
	return true;
}

// Returns Limited Words From String

function string_limit_words($string, $word_limit)
{
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}

// Generates Permalink

function gen_permalink($title)
{
	$permalink=string_limit_words($title, 9);

if(!mb_check_encoding($permalink,"UTF-8"))
	{
		$permalink=preg_replace('/[^a-z0-9]/i',' ', $permalink);
		$permalink=trim(preg_replace("/[[:blank:]]+/"," ",$permalink));
		$permalink=strtolower(str_replace(" ","-",$permalink));
	}
else
	{
		$permalink=trim($title);
		$permalink=str_replace(" ","-",$permalink);
	}
	$final = $permalink;
	$count = 1;
	while(already_exists($final))
	{
		$final = $permalink . "-" . $count;
		$count++; 
	}
	return $final;
}
function getdomain($url) 
{ 
	$parsed = parse_url($url); 
	return str_replace('www.','', strtolower($parsed['host'])); 
}
function list_pages()
{
	$sql = "select permalink,title from pages where status=1 order by display_order";
	$query = mysql_query($sql);
	while($fetch = mysql_fetch_array($query))
	{
		$strpages .= '<a href="' . rootpath(). '/page/' . $fetch['permalink'] . '">' . $fetch['title'] . '</a> | ';
	}
	$strpages = substr($strpages, 0, -3);
	return $strpages;
}
function get_title()
{
	$match = "select title from settings"; 
	$qry = mysql_query($match);
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
		$row = mysql_fetch_array($qry);
		return ($row['title']);
	}
	else
	{
		return ("9GAG Clone Script");
	}
}
function get_description()
{
	$match = "select description from settings"; 
	$qry = mysql_query($match);
	$row = mysql_fetch_array($qry);
	return ($row['description']);
}
function get_tags()
{
	$match = "select meta_tags from settings"; 
	$qry = mysql_query($match);
	$row = mysql_fetch_array($qry);
	return ($row['meta_tags']);
}
function captcha_status()
{
	$match = "select captcha from settings"; 
	$qry = mysql_query($match);
	$row = mysql_fetch_array($qry);
	return ($row['captcha']);
}
function pagination($page,$prev,$next,$last,$path)
{
	if($page==1)
		echo('<a href="#" onclick="return false;" class="previous disabled">&laquo; Newer</a>');
	else
		echo('<a href="' . $path . '/' . $prev . '" class="previous">&laquo; Newer</a>');			
	if($page==$last)
		echo('<a href="#" onclick="return false;" class="next disabled">Older &raquo;</a>');
	else
		echo('<a href="' . $path . '/' . $next . '" class="next">Older &raquo;</a>');
}
function is_valid_page($permalink)
{
	$sql = "select id from pages where permalink ='" . $permalink . "'";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0)
	return true;
	else
	return false;
}
function is_valid_tag($tag)
{
	$sql = "select tag_id from tags where tag ='" . $tag . "'";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0)
	return true;
	else
	return false;
}
function is_date( $str ) {
    try {
        $dt = new DateTime( trim($str) );
    }
    catch( Exception $e ) {
        return false;
    }
    $month = $dt->format('m');
    $day = $dt->format('d');
    $year = $dt->format('Y');
    if( checkdate($month, $day, $year) ) {
        return true;
    }
    else {
        return false;
    }
}

//===============================Tags Functions--------------------

function tag_exist($tag)
{
	$sql_tagid = "select tag_id from tags where tag='$tag'";
	$query_tagid = mysql_query($sql_tagid);	
	$count_tagid = mysql_num_rows($query_tagid);
	$array_tagid = mysql_fetch_array($query_tagid);
	if($count>0)
	{
		return $array_tagid['tag_id'];
	}
		return 0;
}

function insert_tag($tag)
{
	if(tag_exist($tag))
	{
		return tag_exist($tag);
 	}
	else
	{
		$sql="INSERT INTO `tags`(`tag_id`, `tag`) VALUES (NULL,'$tag')";
		$query=mysql_query($sql);	
		$sqll="select tag_id from tags where tag='$tag'";
		$querry=mysql_query($sqll);
		$array = mysql_fetch_array($querry);
		return $array['tag_id'];
	}
}
function select_media_id($permalink)
{
	$sqll="select id from media where permalink='$permalink'";
	$querry=mysql_query($sqll);
	$array = mysql_fetch_array($querry);
	return $array['id'];
}
function add_media_tag($post_id,$tag_id)
{
	foreach($tag_id as $tagid)
	{
	if($tagid!=0)
	{
		$sql="INSERT INTO `media_tags`(`id`, `tag_id`) VALUES ('$post_id','$tagid')";
		$query=mysql_query($sql);
		}
	}
}
function delete_tags($id)
{
	//Count Tags
	$sqll="select tag_id from `media_tags` where id='$id'";
	
	$sqlll="select COUNT(tag_id) as count from `media_tags`  where `tag_id` IN (" . $sqll . ")";
	$que=mysql_query($sqlll);
	while($resullt=mysql_fetch_array($que))
	{
			if($resullt['count']<=1)
			{
				$sq="DELETE FROM `tags` WHERE `tag_id`='".$result['tag_id']."'";
				$qu=mysql_query($sq);
			}     
	}
	
	$sql="DELETE FROM `media_tags` WHERE id='$id'"; 
	$query=mysql_query($sql);	
}
function get_mediatag($id)
{
	$tagss="";
	$sql="select tag_id from `media_tags` where `id`=".$id."";
	
			$sqll="select tag from tags where `tag_id` IN (" . $sql . ")";
			$que=mysql_query($sqll);
			while($resultt=mysql_fetch_array($que))
			{
				$tagss .= $resultt['tag'] . ',';
			}  
			
    
	return $tagss;
}
function show_mediatag($id)
{
	$tagss;
	$sql="select tag_id from `media_tags` where `id`=".$id."";
	
			$sqll="select tag from tags where `tag_id` IN (" . $sql . ")";
			$que=mysql_query($sqll);
			while($resultt=mysql_fetch_array($que))
			{
				$tagss = $resultt['tag'];
			}
			
    
	return $tagss;
}

// ===============================Rss Feeds
function rss_enable()
	{
		$show= "select enable from rss_settings";
		$qry=mysql_query($show);
		$array= mysql_fetch_array($qry);
		return $array['enable'];
	}

	function rss_cat_enable()
	{
		$show= "select cat_rss_enable from rss_settings";
		$qry=mysql_query($show);
		$array= mysql_fetch_array($qry);
		return $array['cat_rss_enable'];

	}
	function rss_tag_enable()
	{
		$show= "select rss_tags from rss_settings";
		$qry=mysql_query($show) or die(mysql_error());
		$array= mysql_fetch_array($qry);
		return $array['rss_tags'];	
		
	}

	function rss_description()
	{
		$show= "select desc_length from rss_settings";
		$qry=mysql_query($show);
		$array= mysql_fetch_array($qry);
		return $array['desc_length'];
	}

	function rss_limit()
	{
		$show= "select limit_rss from rss_settings";
		$qry=mysql_query($show);
		$array= mysql_fetch_array($qry);
		return $array['limit_rss'];

	}
	function is_valid_type($type)
	{
		if($type=="pictures" || $type=="videos" || $type=="gifs")
		{
		return true;
		}		
		return false;				
	}
	
	function sitemap_cats_status()
	{
		$match = "select cats_status from sitemaps"; 
		$qry = mysql_query($match);
		$array=mysql_fetch_array($qry);
		return $array["cats_status"];
	}
	function sitemap_pages_status()
	{
		$match = "select pages_status from sitemaps"; 
		$qry = mysql_query($match);
		$array=mysql_fetch_array($qry);
		return $array["pages_status"];
	}
	function sitemap_tags_status()
	{
		$match = "select tags_status from sitemaps"; 
		$qry = mysql_query($match);
		$array=mysql_fetch_array($qry);
		return $array["tags_status"];
	}
	function sitemap_cont_form_status()
	{
		$match = "select cont_form_status from sitemaps"; 
		$qry = mysql_query($match);
		$array=mysql_fetch_array($qry);
		return $array["cont_form_status"];
	}
	function sitemap_posts_status()
	{
		$match = "select posts_status from sitemaps"; 
		$qry = mysql_query($match);
		$array=mysql_fetch_array($qry);
		return $array["posts_status"];
	}
	function sitemap_output_path()
	{
		$match = "select output_path from sitemaps"; 
		$qry = mysql_query($match);
		$array=mysql_fetch_array($qry);
		return $array["output_path"];
	}
	function sitemap_last_modified()
	{
		$match = "select last_modified from sitemaps"; 
		$qry = mysql_query($match);
		$array=mysql_fetch_array($qry);
		return $array["last_modified"];
	}
	
	function fb_admin_id()
	{
		$sql = "select admin_id from comment_setting";
		$query = mysql_query($sql);
		$fetch = mysql_fetch_array($query);
		return $fetch['admin_id'];
		
	}
	function fb_app_id()
	{
		$sql = "select app_id from comment_setting";
		$query = mysql_query($sql);
		$fetch = mysql_fetch_array($query);
		return $fetch['app_id'];
	}
	function return_tags($id)
	{
		$tags="";
		$sql="select tag_id from `media_tags` where `id`=".$id."";
			$sqll="select * from tags where `tag_id` IN (" . $sql . ")";
			$que=mysql_query($sqll);
			while($resultt=mysql_fetch_array($que))
			{
				$tagss = $resultt['tag'];
				$tagid =$resultt['tag_id'];
				$tags .= "<a href='".rootpath()."/tags/".$tagss."'>".$tagss."</a> , ";
													
			} 
		return $tags;
	}
?>