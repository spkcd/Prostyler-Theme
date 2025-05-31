<?php global $cbt_options ?>
<?php if ( $cbt_options['breadcrumb'] == 1) { ?>
		<?php if ( function_exists('custom_breadcrumb') ) { custom_breadcrumb(); } ?>
<?php } ?>