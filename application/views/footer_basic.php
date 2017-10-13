        
        </div>
        </section>
        <!-- end: Page section -->
            </div>
</div>
<!-- end: Container -->



<?php
//p( ($aHiddenStuff) );

if(isset($aHiddenStuff) && $aHiddenStuff):?>
	<?php foreach($aHiddenStuff AS $aItem):?>
		<div id="<?php echo $aItem['container_id']?>" style="display:none;">
			<?php echo $aItem['content']?>
		</div>
	<?php endforeach;?>
<?php endif;?>


<?php if( $enable_social_buttons ):?>
	<!-- AddThis  BEGIN -->
	<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-514fcd3f71d2a817"></script>
	<!-- AddThis END -->
<?php endif;?>
<?php echo load_files('js');?>
</body>
</html>