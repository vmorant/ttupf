<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
		<meta name="HandheldFriendly" content="true" />
		<meta name="viewport" content="width=device-width, height=device-height" />
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <?php echo $sf_content ?>
    <?php echo link_to("Sortir", 'sf_guard_signout') ?>
		<?php echo link_to("Configura l'horari", 'configura') ?>
  </body>
</html>
