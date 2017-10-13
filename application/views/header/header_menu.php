
<!-- start: Top Menu - New-->
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


      <ul class="nav navbar-nav">
        <li <?php echo ($sCurrentMainMenu == 'home') ? 'class=active' : '';?>>
            <a href="<?php echo $c_base_url;?>" >Home </a>
        </li>

      </ul>


      <ul class="nav navbar-nav navbar-right">

        <li class="">
            <a href="<?php echo $c_base_url, 'about_us';?>" >About </a>
        </li>

        <li>
            <a href="<?php echo $c_base_url;?>contact_us">Contact Us</a>
        </li>


		<?php if( $this->authentication->is_user_logged_in() ):?>

		<li class="dropdown">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                About  <span class="caret"></span>
            </a>

            <ul class="dropdown-menu header_actions_menu" role="menu">
                <li>
                    <a href="<?php echo $c_base_url;?>about_us" class="">About Us</a>
                </li>
                <li><hr class="small"></li>
                <li>
                    <a href="<?php echo $c_base_url;?>" class="">Page # 1</a>
                </li>
                <li>
                    <a href="<?php echo $c_base_url;?>" class="">Page # 2</a>
                </li>
                <li>
                    <a href="<?php echo $c_base_url;?>" class="">Page # 3</a>
                </li>
                <li>
                    <a href="<?php echo $c_base_url;?>" class="">Page # 4</a>
                </li>
            </ul>

        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo s('USERNAME')?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">

                <li>
                    <a href="<?php echo $c_base_url;?>account/overview">Account Overview</a>
                </li>
                <li>
                    <a href="<?php echo $c_base_url, 'profile/view/', s('USERNAME');?>">View Profile</a>
                </li>
                <li>
                    <a href="<?php echo $c_base_url;?>profile/edit">Edit Profile</a>
                </li>
                <li>
                    <a href="<?php echo $c_base_url;?>logout">Logout</a>
                </li>
            </ul>
        </li>

		<?php else:?>

        <li class="">
            <a href="<?php echo $c_base_url;?>user/login" >Login</a>
        </li>

		<?php endif;?>

				<?php /*?>
        <li class="">
						<?php if( $this->authentication->is_user_logged_in() ):?>
            	<a href="<?php echo $c_base_url;?>logout" >Logout</a>
            <?php else:?>
            	<a href="<?php echo $c_base_url;?>user/login" >Login</a>
            <?php endif;?>

        </li>
        <?php */?>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<?php /*?>
<?php if( $this->authentication->is_user_logged_in() ):?>
	<div class="pull-right">Welcome <?php echo s('FULL_NAME');?></div>
<?php endif;?>
<?php */?>
