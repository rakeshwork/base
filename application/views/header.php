<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

	<?php // OPEN GRAPH data - required for facebook to correctly read data from our page ?>
	<?php if( $bShowOpenGraphMetaDataInPage && isset($og_meta_data) && !empty($og_meta_data)):?>

	<meta property="og:app_id" content="<?php echo $og_meta_data['og_app_id']?>" />
	<meta property="og:og_url" 	content="<?php echo $og_meta_data['og_url']?>"/>
	<meta property="og:og_image" content="<?php echo $og_meta_data['og_image']?>"/>
	<meta property="og:og_site_name" content="<?php echo $og_meta_data['og_site_name']?>"/>
	<meta property="og:og_title" content="<?php echo $og_meta_data['og_title']?>"/>
	<meta property="og:og_description" content="<?php echo $og_meta_data['og_description']?>"/>
	<?php endif;?>

	<title><?php echo isset($page_title)? $page_title : getTitle();?></title>

	<?php //Meta Tags ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="" />

	<?php //BOOTSTRAP ?>
	<link rel="stylesheet" href="<?php echo $this->config->item('asset_url');?>bootstrap/3.3.5/css/bootstrap.min.css">
	<?php //JQUERY UI ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<?php //OTHER CSS ?>
	<?php echo load_files('css');?>
</head>


<body >

	<?php //HEADER MENU ?>
	<?php $this->load->view('header/header_menu', array('c_base_url' => $c_base_url));?>
	
	<div class="container">
