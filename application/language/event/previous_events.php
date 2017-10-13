<?php if( $aUpcomingEvents ):?>
    <?php foreach( $aPreviousEvents AS $oItem):?>

    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-default btn btn-default-danger m-t-10">
                <?php echo date('M d', strtotime($oItem->starting_on));?>
            </button>
        </div>
        <div class="col-md-10">
            <h4><a href="<?php echo $c_base_url, 'event/view/', $oItem->seo_name;?>"><?php echo $oItem->title?></a><br></h4>
            <div><i class="glyphicon glyphicon-map-marker"></i> <small><?php echo $oItem->venue;?></small></div>
            
            <div>
                <?php if( $this->thanal_resource->getResourceThumbnailUrl($oItem->display_image, 'small') ):?>
                <img src="<?php echo $this->thanal_resource->getResourceThumbnailUrl($oItem->display_image, 'small');?>"
                    style="float:right;margin-left:10px;"
                    alt="<?php echo $oItem->title?>"/>
                <?php endif;?>
    
                <?php echo $oItem->excerpt;?>
            </div>
            
        </div>
    </div>
    
    <?php endforeach;?>
    
    <hr/>
    
    <div class="row">
        <div class="col-md-12 tac">
            <a href="<?php echo $c_base_url, 'event'?>">See all events</a>
        </div>
    </div>
<?php else:?>
    No previous events
<?php endif;?>