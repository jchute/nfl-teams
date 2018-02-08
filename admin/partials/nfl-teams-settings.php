<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://jereross.com/
 * @since      1.0.0
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/admin/partials
 */
?>

<div class="wrap">
    <h1 class="wp-heading-inline">NFL Teams</h1>

    <form method="post" action="options.php">
    <?php

	settings_errors();

	settings_fields( $this->plugin_name . '-options' );
	do_settings_sections( $this->plugin_name );
    submit_button();

    ?>
	</form>

    <br class="clear">
</div>
