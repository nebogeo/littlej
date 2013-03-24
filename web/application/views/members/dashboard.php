<?php
/**
 * Dashboard view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
?>

     
     <div class="bg">
     <h2><?php echo $title; ?></h2>
     
     <!-- missions -->
     
     <div class="box" style="padding:20px;">

       <div style="float:left; width:50%"">
        <div class="member_profile">
           <div class="member_photo"><img src="<?php echo members::gravatar($user->email); ?>" width="80" /></div>
							<div class="member_info">
								<div class="member_info_row"><span class="member_info_label"><?php echo Kohana::lang('ui_admin.name');?>:</span> <?php echo html::specialchars($user->name); ?></div>

								<?php if(count($user->openid) > 0) { ?>
								<div class="member_info_row"><span class="member_info_label"><?php echo Kohana::lang('ui_admin.openids');?></span>:
									<ul>
										<?php
										foreach ($user->openid as $openid)
										{
											$openid_server = parse_url($openid->openid_server);
											echo "<li>".$openid->openid_email." (".$openid_server["host"].")</li>";
										}
										?>
									</ul>
								</div>
								<?php } ?>

								<?php
									if (isset($user->username) AND // Only show if it's set
										($user->username != '' OR $user->username != NULL) AND // Don't show if the user hasn't set a username
										(valid::email($user->username) == false) AND // Don't show if it's a valid email address because it won't work
										($user->public_profile == 1) // Only show if they've set their profile to be public
									)
									{
								?>
								<div class="member_info_row"><span class="member_info_label"><?php echo Kohana::lang('ui_main.public_profile_url');?></span>:
									<br/><a href="<?php echo url::base().'profile/user/'.$user->username; ?>"><?php echo url::base().'profile/user/'.$user->username; ?></a>
								</div>
								<?php
									}
								?>

							</div>
						</div>

     </div>


       <div style="float:right; width:50%">
     
       Your status<br/> 
       <span style="font-size:200%"><?php echo $level_name ?></span>
       <div id="progressbar">
       <div id='indicator' style='width:<?php
                     $total=count($completed_missions)+count($pending_missions);
                     if ($total>0) 
                     {
                         echo (count($completed_missions)/$total*100);
                     }
                     else
                     {
                         echo 0;
                     }
                     ?>%' >
       </div>
       </div>

       <?php echo count($pending_missions)?> 
       <?php if (count($pending_missions)>1) { echo "missions"; } else { echo "mission"; } ?>
       to go till you are a <?php echo $next_level_name ?>! 
     
       <?php
       if(count($pending_missions) > 0) {
           $mission = reset($pending_missions); ?>
           Next mission: <strong><?php echo $mission['name']; ?></strong> 
           (<?php echo $mission['description']; ?>)
                                                                              
           <?php } ?>

       </div>

     <div style="clear:both;"></div>                
     </div>

				<!-- column -->

                 <div class="dash-container">

                 <div class="dash-stats" style="width: 32%; background: #aaa;">
                 <div class="dash-number"><?php echo $reputation; ?></div>
                 Reputation
                 </div>

                 <div class="dash-stats">
                 <div class="dash-number"><?php echo $reports_total; ?></div>
                 Reports
                 </div>

                 <div class="dash-stats">
                 <div class="dash-number"><?php echo $total_photos; ?></div>
                 Photos
                 </div>
                 
                 <div class="dash-stats">
                 <div class="dash-number">0</div>
                 Video
                 </div>

                 <div class="dash-stats">
                 <div class="dash-number">0</div>
                 Audio
                 </div>


                 </div>
                 <br/>

					<!-- badge box -->
					<div class="box">

						<h3><?php echo Kohana::lang('ui_main.badges');?></h3>
						<div style="clear:both;"></div>
						<div style="text-align:center;">
						<?php
							if(count($badges) > 0) {
								foreach($badges as $badge) {
						?>

								<div class="badge">
									<center><img src="<?php echo $badge['img_m']; ?>" alt="<?php echo Kohana::lang('ui_main.badge').' '.$badge['id'];?>" width="80" height="80" style="margin:5px;" /></center>
									<br/><strong><?php echo $badge['name']; ?></strong>
								</div>

						<?php
								}
							}else{
								echo Kohana::lang('ui_main.sorry_no_badges');
							}
						?>
						</div>
						<div style="clear:both;"></div>

					</div>
					<!-- box -->


					<!-- info-container -->
					<div class="info-container" style="background: #fff;">
						<div class="i-c-head">
							<h3>Latest Activity</h3>
							<ul>
								<li class="none-separator"><a href="<?php echo url::site() . 'members/reports' ?>"><?php echo Kohana::lang('ui_main.view_all');?></a></li>
								<li><a href="#" class="rss-icon"><?php echo Kohana::lang('ui_main.rss');?></a></li>
							</ul>
						</div>
						<?php
						if ($reports_total == 0)
						{
						?>
						<div class="post">
							<h3><?php echo Kohana::lang('ui_main.no_results');?></h3>
						</div>
						<?php
						}
						foreach ($incidents as $incident)
						{
							$incident_id = $incident->id;
							$incident_title = $incident->incident_title;
							$incident_description = text::limit_chars($incident->incident_description, 150, '...');
							$incident_date = $incident->incident_date;
							$incident_date = date('g:i A', strtotime($incident->incident_date));
							$incident_mode = $incident->incident_mode;	// Mode of submission... WEB/SMS/EMAIL?

							if ($incident_mode == 1)
							{
								$submit_mode = "mail";
							}
							elseif ($incident_mode == 2)
							{
								$submit_mode = "sms";
							}
							elseif ($incident_mode == 3)
							{
								$submit_mode = "mail";
							}
							elseif ($incident_mode == 4)
							{
								$submit_mode = "twitter";
							}

							// Incident Status
							$incident_approved = $incident->incident_active;
							if ($incident_approved == '1')
							{
								$incident_approved = "ok";
							}
							else
							{
								$incident_approved = "none";
							}

							$incident_verified = $incident->incident_verified;
							if ($incident_verified == '1')
							{
								$incident_verified = "ok";
							}
							else
							{
								$incident_verified = "none";
							}
							?>
							<div class="post">
								<ul class="post-info">
									<li><a href="#" class="<?php echo $incident_approved; ?>"><?php echo utf8::strtoupper(Kohana::lang('ui_main.approved'));?>:</a></li>
									<li><a href="#" class="<?php echo $incident_verified ?>"><?php echo utf8::strtoupper(Kohana::lang('ui_main.verified'));?>:</a></li>
									<li class="last"><a href="#" class="<?php echo $submit_mode; ?>"><?php echo utf8::strtoupper(Kohana::lang('ui_main.source'));?>:</a></li>
								</ul>
								<h4><strong><?php echo $incident_date; ?></strong><a href="<?php echo url::site() . 'members/reports/edit/' . $incident_id; ?>"><?php echo $incident_title; ?></a></h4>
								<p><?php echo $incident_description; ?></p>
							</div>
							<?php
						}
						?>
						<a href="<?php echo url::site() . 'members/reports' ?>" class="view-all"><?php echo Kohana::lang('ui_main.view_all_reports');?></a>
					</div>
				</div>


<!--
					<div class="box">
						<h3><?php echo Kohana::lang('ui_main.quick_stats');?></h3>
						<ul class="nav-list">
							<li>
								<a href="<?php echo url::site() . 'members/reports' ?>" class="reports"><?php echo Kohana::lang('ui_admin.my_reports');?></a>
								<strong><?php echo number_format($reports_total); ?></strong>
								<ul>
									<li><a href="<?php echo url::site() . 'members/reports?status=a' ?>"><?php echo Kohana::lang('ui_main.not_approved');?></a><strong>(<?php echo $reports_unapproved; ?>)</strong></li>

								</ul>
							</li>
							<li>
								<a href="<?php echo url::site() . 'members/checkins' ?>" class="checkins"><?php echo Kohana::lang('ui_admin.my_checkins');?></a>
								<strong><?php echo $checkins; ?></strong>
							</li>
							<li>
								<a href="<?php echo url::site() . 'members/alerts' ?>" class="alerts"><?php echo Kohana::lang('ui_admin.my_alerts');?></a>
								<strong><?php echo $alerts; ?></strong>
							</li>
							<li>
								<a href="#" class="votes"><?php echo Kohana::lang('ui_admin.my_votes');?></a>
								<strong><?php echo $votes; ?></strong>
								<ul>
									<li><a href="#"><?php echo Kohana::lang('ui_admin.my_votes_up');?></a><strong>(<?php echo $votes_up; ?>)</strong></li>
									<li><a href="#"><?php echo Kohana::lang('ui_admin.my_votes_down');?></a><strong>(<?php echo $votes_down; ?>)</strong></li>
								</ul>
							</li>
							<li>
								<a href="<?php echo url::site() . 'members/private' ?>" class="messages"><?php echo Kohana::lang('ui_admin.private_messages');?></a>
								<strong><?php echo "0"; ?></strong>
							</li>
						</ul>
					</div>
-->
				</div>

