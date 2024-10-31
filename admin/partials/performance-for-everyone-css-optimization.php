<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap wppfe_performance">
    <div class="row ">
        <div class="col-6">
            <h2>Styling(CSS) Optimization Settings</h2>
            <hr>
            <form method="post" action="options.php">
				<?php
				settings_fields( 'performance_for_everyone_styles_options' );
				do_settings_sections( 'performance_for_everyone_styles' );
				submit_button();
				?>
            </form>
        </div>
        <div class="col-6">
            <h2>We will answer to your questions</h2>
            <hr>
        </div>
    </div>
</div>
