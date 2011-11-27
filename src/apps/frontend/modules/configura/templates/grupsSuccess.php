<?php
/**
 * usuari_id and assignatura_id params missing from action url
 * which is why include_partial is used for this view.
 */
/*
<form action="<?php echo url_for('configura/update') ?>" method="POST">
	<div class="configure-form">
		<?php echo $form ?>
	</div>
	<div>
		<input type="submit" />
	</div>
</form>
 */
?>
 
<?php include_partial('form', array('form' => $form)) ?>
