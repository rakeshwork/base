<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $c_charset;?>" />
<title><?php echo isset($page_title)? $page_title : c('website_name');?></title>
<?php 
/*
<script type="text/javascript" src="<?php echo c('js_url').'jquery/jquery-1.6.4.js';?>"></script>
*/
?>

<?php if(isset($jquery) && $jquery == true):?>

<?php endif;?>

<link rel="stylesheet" href="<?php echo $this->config->item('css_template_cdn_url');?>">


<?php echo load_files('css');?> 

<script type="text/javascript"> 
var base_url = "<?php echo c('base_url');?>";
var image_url = "<?php echo c('image_url');?>";
</script> 
   
</head> 
<body>
<div id="iframe_dump"></div>