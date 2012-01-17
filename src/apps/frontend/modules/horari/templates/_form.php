<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php
/**
 * This form was slightly modified while trying to get user configuration
 * working. $url_tail is constructed by ternary operations in auto-generated
 * _form.php partials, this could probably be switched back to that format.
 * This partial is only used for the last step, setting the groups for the
 * uta object.
 * A key part to getting the form to save properly is appending the user and
 * subject ids to the action url.
 */
	$url_tail = '';
	if($form->getObject()->isNew()){
		$url_tail = $url_tail . 'create';
	} else {
		$url_tail = $url_tail . 'update';
	}

	if(!$form->getObject()->isNew()){
		$url_tail = $url_tail . '?usuari_id='.$form->getObject()->getUsuariId().'&assignatura_id='.$form->getObject()->getAssignaturaId();
	}
?>
<form action="<?php echo url_for('horari/'.$url_tail) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
		<tbody>
			<?php echo $form['grup_teoria']->renderRow(array(), 'Grup teoria<br /><span style="font-weight: normal; color: gray; size: 90%;">exemple: 1</span>') ?>
			<?php echo $form['grup_practiques']->renderRow(array(), 'Grup pràctiques<br /><span style="font-weight: normal; color: gray; size: 90%;">exemple: P101</span>') ?>
			<?php echo $form['grup_seminari']->renderRow(array(), 'Grup seminaris<br /><span style="font-weight: normal; color: gray; size: 90%;">exemple: S101</span>') ?>
    </tbody>
  </table>
</form>
