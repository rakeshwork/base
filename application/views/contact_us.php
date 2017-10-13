





<div class="container">
	<div class="row">
		<div class="col-md-5">
				<h2>Contact us <small>get in touch with us by filling form below</small></h2>
				<hr class="colorgraph">
				<?php showMessage();?>
				
							<div id="errormessage"></div>
							<?php echo form_open_multipart('contact_us/submit/' . $form_token, 'id="contactUsForm" class=""'); ?>
									<div class="form-group">
											<input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
											<div class="validation"></div>
									</div>
									<div class="form-group">
											<input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
											<div class="validation"></div>
									</div>
									<div class="form-group">
											<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
											<div class="validation"></div>
									</div>
									<div class="form-group">
											<textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
											<div class="validation"></div>
									</div>

									<div class="text-center"><button type="submit" class="btn btn-theme btn-block btn-md">Send Message</button></div>
							</form>
							<hr class="colorgraph">

		</div>
		<div class="col-md-7">
			<div class=" clearfix">
				<div id="google-map"  data-latitude="9.092205" data-longitude="76.747268"></div>
			</div>
		</div>
	</div>
</div>

</div>
<?php /*?>
    <h3><?php echo $oAddress->title?></h3>
    <?php echo $oAddress->content1;?>
<?php */?>
