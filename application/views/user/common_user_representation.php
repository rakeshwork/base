
<h3>Director</h3>

<?php $oUser = $this->user_model->getUserBy('account_no', $oProgram->program_director, 'full');?>

<div class="media">
    <a href="<?php echo $c_base_url, $oUser->username;?>" class="pull-left">
    <?php echo getCurrentProfilePic($oUser, 'tiny', true, array('attributes' => array('class' => 'thumbnail')));?>    
    </a>
    <div class="media-body">
        <a href="<?php echo $c_base_url, $oUser->username;?>">
            <h4 class="media-heading"><?php echo $oProgram->program_director_name;?></h4>    
        </a>
    </div>
</div>

<hr/>