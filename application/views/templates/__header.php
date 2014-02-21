<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html>
    <head>
      <title><?= $config['configuration']['title'] ?></title>
      <!-- Bootstrap -->
      <link href="<?= base_url() ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet">      
      <script type="text/javascript" src="<?= base_url() ?>/public/bootstrap/jquery-1.8.2.min.js"></script>
      <script type="text/javascript" src="<?= base_url() ?>/public/bootstrap/js/bootstrap.min.js"></script>
      
      <style type="text/css">
      	body {
      		padding-top: 50px;
      	}
      </style>
      <link href="<?= base_url() ?>/public/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    </head>
    <body>
      <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <h1 style="width: 500px"><?php echo $config['configuration']['header']; ?></h1>
	    <?php if($this->session->userdata("user")) { ?>
	       <div style="float: right;margin-top: -40px">
		  <?= anchor("welcome/setting","Edit account") ?> | 
		  <?= anchor("welcome/logout","Log out") ?>
	       </div>
	    <? } ?>
          </div>
        </div>
      </div>
      <div class="container" style="margin-top:20px">
      <? if(!isset($noBreadcrumb)) { ?>
      <ul class="breadcrumb">
		    <li><?php echo anchor('welcome','Home')?> <span class="divider">/</span></li>
		    <?php 
		    while($bc = current($breadcrumbs)) {
				echo "<li><a href='{$bc['href']}'>{$bc['title']}</a>";
				if(next($breadcrumbs))
					echo "<span class='divider'>/</span>";
				echo "<li>";
			}
		    ?>
		</ul>
      <? } ?>