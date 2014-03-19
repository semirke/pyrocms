<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="robots" content="noindex,nofollow">
	<title><?= $this->settings->site_name; ?> - <?= lang('login_title');?></title>

	<base href="<?= base_url(); ?>"/>
	<meta name="robots" content="noindex, nofollow"/>

	<?php Asset::css(array('build.css')); ?>
	<?php Asset::js(array('jquery.min.js', 'modernizr.min.js')); ?>

	<?= Asset::render() ?>
</head>

<body id="login">


	<main class="container animated-fast fadeInUp">
	<div class="row-fluid">
	<div class="col-md-3 col-md-offset-4 p">

		<center id="page-title" class="brand">
			<h2><?= Asset::img('icon-logo-darker.png', 'PyroCMS', ['height' => '40px']); ?> <strong>Pyro</strong>CMS</h2>
		</center>

		<?php $this->load->view('admin/partials/notices') ?>

		<section>

			<?= Form::open(['url' => 'admin/login', 'id' => 'login', 'class' => 'form margin-top']) ?>

			<div class="form-group">
				<label for="remember-check">Email</label>
				<input type="text" name="email" class="form-control" placeholder="<?= lang('global:email'); ?>"/>
			</div>

			<div class="form-group">
				<label for="remember-check">Password</label>
				<input type="password" name="password" class="form-control" placeholder="<?= lang('global:password'); ?>"/>
			</div>

			<div class="form-group">
				<label for="remember-check">
					<input type="checkbox" name="remember" id="remember-check" checked />
					<?= lang('user:remember'); ?>
				</label>

				<br/>
			
				<button class="btn btn-primary btn-block" type="submit" name="submit" value="<?= lang('login_label'); ?>">
					<span><?= lang('login_label'); ?></span>
				</button>
			</div>

			<?= Form::close(); ?>

		</section>

	</div>
	</div>
	</main>

	

	<footer class="navbar navbar-fixed-bottom">
	<center class="container">
		<div class="row-fluid">
		<div class="col-md-3 col-md-offset-4 p animated-fast fadeInUp">
			Copyright &copy; 2009 - <?= date('Y'); ?> PyroCMS LLC 
			<br><span id="version"><?= CMS_VERSION.' '.CMS_EDITION; ?></span>
		</div>
		</div>
	</center>
	</footer>

	<script type="text/javascript">
		Pyro = { 'lang': {} };

		var APPPATH_URI					= "<?= APPPATH_URI;?>";
		var SITE_URL					= "<?= rtrim(site_url(), '/').'/';?>";
		var BASE_URL					= "<?= BASE_URL;?>";
		var BASE_URI					= "<?= BASE_URI;?>";
		var UPLOAD_PATH					= "<?= UPLOAD_PATH;?>";
		var DEFAULT_TITLE				= "<?= addslashes(Settings::get('site_name')); ?>";
		Pyro.current_module				= "<?= isset($module_details['slug']) ? $module_details['slug'] : null; ?>";
		Pyro.admin_theme_url			= "<?= BASE_URL . ci()->theme->path; ?>";
		Pyro.apppath_uri				= "<?= APPPATH_URI; ?>";
		Pyro.base_uri					= "<?= BASE_URI; ?>";
		Pyro.lang.remove				= "<?= lang('global:remove'); ?>";
		Pyro.lang.dialog_message 		= "<?= lang('global:dialog:delete_message'); ?>";
		Pyro.csrf_cookie_name			= "<?= config_item('cookie_prefix').config_item('csrf_cookie_name'); ?>";
	</script>

	<?php Asset::js('build.min.js', null, 'deferred'); ?>

	<?= Asset::render_js('deferred') ?>

</body>
</html>