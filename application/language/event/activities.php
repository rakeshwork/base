<?php showMessage();?>

<h3>Upcoming Events</h3>

<div class="row">
    <div class="col-md-12">
        Showing Category : <?php echo form_dropdown('event_type', $aEventTypesTitle, $iType, 'class="type"');?>
    </div>
</div>

<div  class="row">
    
    <div class="col-md-12">
    <?php if($aAllEvents):?>
    
        <?php foreach($aAllEvents AS $oEvent):?>
        
        <?php //p($oEvent);?>
        
        <div>
            <h4>
                <a href="<?php echo $c_base_url, 'event/view/' , $oEvent->seo_name;?>">
                <?php echo $oEvent->title?></a>
            </h4>
            
            <div class="row">
                    <div class="col-md-1">
                        <button class="btn btn-default btn btn-default-danger">
                            <?php echo date('M d', strtotime($oEvent->starting_on));?>
                        </button>
                    </div>
                    <div class="col-md-11" style="">
                        <div>
                            <b>Event Type</b> : <?php echo $aEventTypesTitle[$oEvent->type]?>
                        </div>
                        <div>
                            <i class="glyphicon glyphicon-time"></i> &nbsp;
                            From : <?php echo date('j M Y (g A)', strtotime($oEvent->starting_on));?>
                             -- To : <?php echo date('j M Y (g A)', strtotime($oEvent->ending_on));?>
                        </div>
                        <div>
                            <i class="glyphicon glyphicon-map-marker"></i> &nbsp;
                            <?php echo $oEvent->venue;?>
                        </div>
                    </div>
            </div>
            

            <div class="row">
                <?php echo $oEvent->excerpt;?>
            </div>
            <div class="row">
                <a class="pull-right btn btn-default btn btn-default-primary" href="<?php echo $c_base_url, 'event/view/', $oEvent->seo_name?>">View Details</a>
            </div>
        </div>
        <?php endforeach;?>
        
        <?php echo $sPagination;?>
        
    <?php else:?>
        <div class="no_data">There are no upcoming events</div>
    <?php endif;?>
    
    </div>
    
</div>