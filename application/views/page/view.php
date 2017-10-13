<h3><?php echo @$oPage->title;?></h3>
<div class="row">

        <?php $sClass = 'col-md-' . (12/$oPage->show);?>

        <?php for($i=1;$i<=$oPage->show;++$i): ?>

            <?php $sContent = 'content'.$i;?>
            <div class="<?php echo $sClass;?>">
                <?php echo $oPage->$sContent;?>
            </div>
        <?php endfor;?>

</div>
