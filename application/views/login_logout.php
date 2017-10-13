<?php if( $bLoggedIn ):?>
	<div class="btn btn-default-group pull-right">
		<button class="btn btn-default"><?php echo s('FULL_NAME');?></button>
		<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<?php if($this->authentication->is_admin_logged_in()):?>
			<li>
				<a href="<?php echo c('base_url');?>admin" title="Control Panel">Control Panel</a>
			</li>
			<?php endif;?>
			<li>
				<a href="<?php echo c('base_url'), 'profile/view/', s('ACCOUNT_NO');?>" title="View Profile">Profile</a>
			</li>
			<li>
				<a href="<?php echo $c_base_url . 'profile/edit/'.$oCurrUser->account_no;?>" title="Edit Profile">Edit Profile</a>
			</li>
			<li>
				<a href="<?php echo $c_base_url;?>account/overview" title="Account Settings">Account Settings</a>
			</li>		
			<li class="divider"></li>
			<li>
				<a href="<?php echo $sLogoutUrl;?>">Logout</a>
			</li>		
		</ul>
	</div>
	
<?php else:?>

	<?php /*SHow the login buttons*/?>
	<?php /*
	<div id="account_menu" class="login_btn btn-default_cnt r">
	
		<div>
		<a id="fb_login_btn btn-default" href="<?php echo $sLoginUrl;?>" title="Login via Facebook" class="l">&nbsp;</a>
		<div class="login_or l">or</div>
		<div class="login_btn btn-default l">
			<a href="<?php echo c('base_url').'user/login';?>" title="Login via system">Login</a>
		</div>
		</div>
		
		

		<div class="forgot_up m-t-5 l">
			<a href="<?php echo c('base_url').'account/recovery';?>" class="l">Forgot Username/ Password</a>
			<span class="l m-l-10 m-r-10">&nbsp;|&nbsp;</span>
			<a href="<?php echo c('base_url').'user/signup';?>" class="l">SignUp</a>
		</div>
	</div>
	*/ ?>
<?php endif;?>