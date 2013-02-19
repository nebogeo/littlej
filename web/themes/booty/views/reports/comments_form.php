<!-- start submit comments block -->
<div id="comment-form" class="well">
	
	<h3><?php echo Kohana::lang('ui_main.leave_a_comment');?></h3>
	<?php
	if ($form_error)
	{
		?>
		<!-- red-box -->
		<div class="red-box">
			<h3><?php echo Kohana::lang('ui_main.error');?></h3>
			<ul>
				<?php
					foreach ($errors as $error_item => $error_description)
					{
						print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
					}
				?>
			</ul>
		</div>
		<?php
	}
	?>
	<?php print form::open(NULL, array('id' => 'commentForm', 'name' => 'commentForm')); ?>
	<?php if ( ! $user) { ?>
	<div class="report_row">
		<label><?php echo Kohana::lang('ui_main.name');?></label>
		<?php print form::input('comment_author', $form['comment_author'], ' class="text"'); ?>
	</div>

	<div class="report_row">
		<label><?php echo Kohana::lang('ui_main.email'); ?></label>
		<?php print form::input('comment_email', $form['comment_email'], ' class="text"'); ?>
	</div>
	<?php
		} else {
	?>
	<div class="report_row">
		<strong><?php echo $user->name; ?></strong>
	</div>
	<?php } ?>

	<div class="report_row">
		<label><?php echo Kohana::lang('ui_main.comments'); ?></label>
		<?php print form::textarea('comment_description', $form['comment_description'], ' rows="4" cols="40" class="textarea long" ') ?>
	</div>

	<div class="report_row">
		<label><?php echo Kohana::lang('ui_main.security_code'); ?>: 
		<i><?php print $captcha->render(); ?></i></label>
		<?php print form::input('captcha', $form['captcha'], ' class="text"'); ?>
	</div>

	<?php
	// Action::comments_form - Runs right before the end of the comment submit form
	Event::run('ushahidi_action.comment_form');
	?>
	<div class="report_row">
		<input name="submit" type="submit" value="<?php echo Kohana::lang('ui_main.reports_btn_submit'); ?> <?php echo Kohana::lang('ui_main.comment'); ?>" class="btn btn-primary" />
	</div>
	<?php print form::close(); ?>
	
</div> <!-- end submit comments block -->