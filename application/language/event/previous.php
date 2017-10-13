<?php showMessage();?>

<h3>Previous Events</h3>

<div class="row">
    <div class="col-md-3">
        Showing Category : <?php echo form_dropdown('event_type', $aEventTypesTitle, $iType, 'class="type form-control"');?>
    </div>
</div>

<div  class="row">
    <?php if($aAllEvents):?>

        <?php foreach($aAllEvents AS $oEvent):?>

        <?php //p($oEvent);?>
        <div class="col-md-12"></div>
        <div class="col-md-11">
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
            <div>
                <?php echo substr($oEvent->description, 0, 500);?>
            </div>
        </div>
        <?php endforeach;?>

        <?php echo $sPagination;?>

    <?php else:?>
      <div class="col-md-12 text-center">There are no events</div>

    <?php endif;?>
</div>
