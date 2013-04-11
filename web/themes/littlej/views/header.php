<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title><?php echo $page_title.$site_name; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel="stylesheet" type="text/css">
	<?php echo $header_block; ?>
		<?php
	// Action::header_scripts - Additional Inline Scripts from Plugins
	Event::run('ushahidi_action.header_scripts');
	?>
</head>


<?php
  // Add a class to the body tag according to the page URI
  // we're on the home page
  if (count($uri_segments) == 0)
  {
    $body_class = "page-main";
  }
  // 1st tier pages
  elseif (count($uri_segments) == 1)
  {
    $body_class = "page-".$uri_segments[0];
  }
  // 2nd tier pages... ie "/reports/submit"
  elseif (count($uri_segments) >= 2)
  {
    $body_class = "page-".$uri_segments[0]."-".$uri_segments[1];
  }

?>

<body id="page" class="<?php echo $body_class; ?>">

<?php echo $header_nav; ?>

  <!-- top bar-->
  <!-- / searchbox -->

<center>
<a href="<?php echo url::site();?>"><img src="<?php echo $banner; ?>" alt="<?php echo $site_name; ?>" /></a>
</center>

	<!-- wrapper -->
	<div class="rapidxwpr floatholder">

	  <div id="lefty">

	  <!-- mainmenu -->
	  <div id="mainmenu" class="clearingfix">
	    <ul>
<!--	      <?php nav::main_tabs($this_page); ?> -->
	    </ul>

	  </div>
	  <!-- / mainmenu -->
	  </div>

	  <div id="righty">
		<!-- header -->
		<!-- / header -->
         <!-- / header item for plugins -->
        <?php
            // Action::header_item - Additional items to be added by plugins
	        Event::run('ushahidi_action.header_item');
        ?>

        <?php if(isset($site_message) AND $site_message != '') { ?>
			<div class="green-box">
				<h3><?php echo $site_message; ?></h3>
			</div>
		<?php } ?>

		<!-- main body -->
		<div id="middle">
			<div class="background layoutleft">
