<div class="wrap narrow">

	<h2 class="title"><?php _e( 'AppThemes Updater Settings', 'appthemes-updater' ); ?></h2>

	<p><?php printf(
		__( 'Enter your unique API key. This can be found within your <a href="%1$s" target="_blank">AppThemes customer account</a>.', 'appthemes-updater' ),
		'https://my.appthemes.com/api-key/'
	); ?></p>

	<form method="post" action="">

		<table class="form-table">
			<tbody>
				<tr>
					<th valign="top" scope="row">
						<label for="appthemes_key"><?php _e( 'API Key:', 'appthemes-updater' ); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text code" name="appthemes_key" value="<?php echo esc_attr( APP_Upgrader::get_key() ); ?>">
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" name="appthemes_submit" value="<?php esc_attr_e( 'Save Settings', 'appthemes-updater' ); ?>" />
		</p>

	</form>

</div>

<div class="clear"></div>
