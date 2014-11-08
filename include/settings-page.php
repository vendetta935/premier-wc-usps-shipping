<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>USPS Shipping for WooCommerce Settings</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
				
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>USPS Shipper Information</span></h3>
						<div class="inside">
							
							<?php 
							/*** Display USPS shipper information pass/fail message if set ***/
							if(isset($test_message)) {
								echo '<p><strong>' . $test_message . '</strong></p>';
							}
							/*** Show form to enter USPS shipper information  if it's not set, if validation failed or the Update button has been pressed,  ***/
							if( ( !isset($usps_Verified) || !$usps_Verified ) || ( isset($uspstest) && !$uspstest ) || $usps_shipping_options_update == 'y' ) : ?>
							<p>Register for an account on the <a href="https://www.usps.com/business/web-tools-apis/developers-center.htm" target="_blank">USPS Web Tools Developer Resource Center</a> to obtain a username.</p>
							<form name="usps_shipping_options_form" method="post" action="">
								<input type="hidden" name="usps_shipping_options_submitted" value="y">
								<table class="form-table">
									<tr>
										<td><label for="usps_UserID">Username:</label></td>
										<td><input name="usps_UserID" id="usps_UserID" type="text" value="" class="regular-text" /></td>
									</tr>
									<tr>
										<td><label for="usps_FromPostalCode">Shipper Postal Code (From Zip):</label></td>
										<td><input name="usps_FromPostalCode" id="usps_FromPostalCode" type="text" value="" class="regular-text" /></td>
									</tr>
								</table>
								<p><input class="button-primary" type="submit" name="Submit" value="Submit" /></p>
							</form>
							<?php endif ?>
								
							<?php 
							/*** Display shipper information if it's set ***/ 
							if( ( isset($usps_Verified) && $usps_Verified ) || $usps_shipping_options_update == 'y' ) : ?>
								
								<?php 
								/*** Display 'current settings' if validation failed or update button has been pressed ***/
								if( !$uspstest || $usps_shipping_options_update == 'y' ) : ?>
									<p>Current settings:</p>
								<?php endif ?>
								<table class="widefat">
									<tr>
										<td>Username:</td>
										<td><?php echo $usps_UserID; ?></td>
									</tr>
									<tr>
										<td>Shipper Postal Code:</td>
										<td><?php echo $usps_FromPostalCode; ?></td>
									</tr>
									<tr>
										<td>Last updated:</td>
										<td><?php echo date_i18n('M jS y', $usps_LastUpdated); ?></td>
									</tr>
								</table>
								<?php 
								/*** Show Update button if it hasn't been pressed ***/
								if( $usps_shipping_options_update != 'y' ) : ?>
									<form name="usps_shipping_options_update" method="post" action="">
										<input type="hidden" name="usps_shipping_options_update" value="y">
										<p><input class="button-primary" type="submit" name="Update" value="Change/Update" /></p>
									</form>
								<?php endif ?>
							<?php endif ?>
							
						</div> <!-- .inside -->
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- post-body-content -->
			
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				
				<div class="meta-box-sortables">
					
					<div class="postbox">
						
						<div class="inside">
							<h2>System Information</h2>
							<p>This plugin sends rate requests to USPS using the cURL method. Verify that cURL is enabled on your server in the <a href="<?php echo admin_url( 'admin.php?page=wc-status' ); ?>" target="_blank">WooCommerce System Status page</a> under fsockopen/cURL.</p>
							<h2>Developer Information</h2>
							<p>Author: Andrew Dushane, <a href="http://premierprograming.com" target="_blank">Premier Programing</a></p>
						</div><!-- .inside -->
						
					</div>  <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables -->
				
			</div> <!-- #postbox-container-1 .postbox-container -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->
