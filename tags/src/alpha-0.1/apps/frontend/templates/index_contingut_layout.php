<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div class="content">
				<h1>
				</h1>
				<div id="opcions">
					<?php include_slot('opcions') ?>
				</div>
			</div>
		</div>
		<div id="content">
			<div id="central">	
				<div class="sidebar">
					<?php include_slot('sidebar') ?>
				</div>
				<div class="content">
					<?php echo $sf_content ?>
				</div>
			</div>
			<div id="footer">
				<div class="content">
					<span class="footer_bar">
						<?php include_component('index', 'footer') ?>
					</span>
					<span class="symfony">
						powered by 
						<a href="http://www.symfony-project.org/">
							<img src="/images/symfony.png" alt="symfony framework" />
						</a>
					</span>
				</div>
			</div>
		</div>
	</div>
</body>
</html>