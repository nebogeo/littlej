<?php 
/**
 * Reports edit view page.
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
				<h2>
					<?php members::reports_subtabs("edit"); ?>
				</h2>
				<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'reportForm', 'name' => 'reportForm')); ?>
					<input type="hidden" name="save" id="save" value="">
					<input type="hidden" name="location_id" id="location_id" value="<?php print $form['location_id']; ?>">
					<input type="hidden" name="incident_zoom" id="incident_zoom" value="<?php print $form['incident_zoom']; ?>">
					<input type="hidden" name="country_name" id="country_name" value="<?php echo $form['country_name'];?>" />
					<!-- report-form -->
					<div class="report-form">
						<?php if ($form_error): ?>
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
						<?php endif; ?>

						<?php if ($form_saved): ?>
							<!-- green-box -->
							<div class="green-box">
								<h3><?php echo Kohana::lang('ui_main.report_saved');?></h3>
							</div>
						<?php endif; ?>
						
						<div class="head">
							<h3><?php echo $id ? Kohana::lang('ui_main.edit_report') : Kohana::lang('ui_main.new_report'); ?></h3>
							<div class="btns" style="float:right;">
								<ul>
									<li><a href="#" class="btn_save"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_report'));?></a></li>
									<li><a href="#" class="btn_save_close"><?php echo utf8::strtoupper(Kohana::lang('ui_main.save_close'));?></a></li>
									<li><a href="<?php echo url::base().'members/reports/';?>" class="btns_red"><?php echo utf8::strtoupper(Kohana::lang('ui_main.cancel'));?></a>&nbsp;&nbsp;&nbsp;</li>
									<?php if ($id): ?>
									<li><a href="<?php echo $previous_url;?>" class="btns_gray">&laquo; <?php echo utf8::strtoupper(Kohana::lang('ui_main.previous'));?></a></li>
									<li><a href="<?php echo $next_url;?>" class="btns_gray"><?php echo utf8::strtoupper(Kohana::lang('ui_main.next'));?> &raquo;</a></li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
						
						<!-- f-col -->
						<div class="f-col">
							<?php
							// Action::report_pre_form_admin - Runs right before report form is rendered
							Event::run('ushahidi_action.report_pre_form_messages', $id);
							?>

							<div class="row">
								<h4>What category would you like to put on your report?</h4> 
			                    <div class="report_category">
                        	    <?php
                                        $selected_categories = array();
                                        if (!empty($form['incident_category']) && is_array($form['incident_category'])) {
                                            $selected_categories = $form['incident_category'];
                                        }
                                        $columns = 2;
                                        echo category::form_dropdown('incident_category', $selected_categories, $columns);
                                ?>
             					</div>
							</div>


							<div class="row">
								<h4>Give your report a headline?</h4>
								<?php print form::input('incident_title', $form['incident_title'], ' class="text title"'); ?>
							</div>

							<?php if (!($id)): ?>
								<div class="row" id="datetime_default">
									<h4>
                                    When did this happen?  
									<?php echo Kohana::lang('ui_main.today_at').' '.$form['incident_hour']
										.":".$form['incident_minute']." ".$form['incident_ampm']; ?>

                                    <a href="#" id="date_toggle">Modify</a>
									</h4>
								</div>
							<?php endif; ?>
							<div class="row <?php if (!($id)) echo "hide"; ?> " id="datetime_edit">
								<div class="date-box">
									<h4>When did this happen?</h4>
									<?php print form::input('incident_date', $form['incident_date'], ' class="text"; style="height:50%; font-size:90%;";'); ?>								
									<?php print $date_picker_js; ?>				    
								</div>
								<div class="time">
									<h4><?php echo Kohana::lang('ui_main.time');?> <span>(<?php echo Kohana::lang('ui_main.approximate');?>)</span></h4>
									<?php
									print '<span class="sel-holder">' .
								    form::dropdown('incident_hour', $hour_array,
									$form['incident_hour']) . '</span>';
									
									print '<span class="dots">:</span>';
									
									print '<span class="sel-holder">' .
									form::dropdown('incident_minute',
									$minute_array, $form['incident_minute']) .
									'</span>';
									print '<span class="dots">:</span>';
									
									print '<span class="sel-holder">' .
									form::dropdown('incident_ampm', $ampm_array,
									$form['incident_ampm']) . '</span>';
									?>
								</div>
							</div>


							<div class="row">
                                <h4>Where did this happen?</h4>
                                <?php print form::input('location_name', $form['location_name'], ' class="text long"'); ?>
							</div>


          <div type=\"hidden\" style="display: none;">
                  <span><?php echo Kohana::lang('ui_main.latitude');?>:</span>
                  <?php print form::input('latitude', $form['latitude'], ' class="text"'); ?>
                  <span><?php echo Kohana::lang('ui_main.longitude');?>:</span>
                  <?php print form::input('longitude', $form['longitude'], ' class="text"'); ?>
          </div>


						<!-- f-col-1 -->
						<!-- div class="f-col-1" -->
							<div class="incident-location">
								<div id="divMap" class="map_holder_reports" style="width:90%">
									<div id="geometryLabelerHolder" class="olControlNoSelect">
										<div id="geometryLabeler">
											<div id="geometryLabelComment">
												<span id="geometryLabel">
													<label><?php echo Kohana::lang('ui_main.geometry_label');?>:</label> 
													<?php print form::input('geometry_label', '', ' class="lbl_text"'); ?>
												</span>
												<span id="geometryComment">
													<label><?php echo Kohana::lang('ui_main.geometry_comments');?>:</label> 
													<?php print form::input('geometry_comment', '', ' class="lbl_text2"'); ?>
												</span>
											</div>
											<div>
												<span id="geometryColor">
													<label><?php echo Kohana::lang('ui_main.geometry_color');?>:</label> 
													<?php print form::input('geometry_color', '', ' class="lbl_text"'); ?>
												</span>
												<span id="geometryStrokewidth">
													<label><?php echo Kohana::lang('ui_main.geometry_strokewidth');?>:</label> 
													<?php print form::dropdown('geometry_strokewidth', $stroke_width_array, ''); ?>
												</span>
												<span id="geometryLat">
													<label><?php echo Kohana::lang('ui_main.latitude');?>:</label> 
													<?php print form::input('geometry_lat', '', ' class="lbl_text"'); ?>
												</span>
												<span id="geometryLon">
													<label><?php echo Kohana::lang('ui_main.longitude');?>:</label> 
													<?php print form::input('geometry_lon', '', ' class="lbl_text"'); ?>
												</span>
											</div>
										</div>
										<div id="geometryLabelerClose"></div>
									</div>
								</div>
							</div>								

							<?php
							// Action::report_form_admin - Runs just after the report description
							Event::run('ushahidi_action.report_form_admin', $id);
							?>

							
							<div id="custom_forms">
								<?php
								foreach ($disp_custom_fields as $field_id => $field_property)
								{
									echo "<div class=\"row\">";
									echo "<h4>" . $field_property['field_name'] . "</h4>";
									if ($field_property['field_type'] == 1)
									{ // Text Field
										// Is this a date field?
										if ($field_property['field_isdate'] == 1)
										{
											echo form::input('custom_field['.$field_id.']', $form['custom_field'][$field_id],
												' id="custom_field_'.$field_id.'" class="text"');
											echo '<script type="text/javascript">
													$(document).ready(function() {
													$("#custom_field_'.$field_id.'").datepicker({ 
													showOn: "both", 
													buttonImage: "'.url::file_loc('img').'media/img/icon-calendar.gif", 
													buttonImageOnly: true 
													});
													});
												</script>';
										}
										else
										{
											echo form::input('custom_field['.$field_id.']', $form['custom_field'][$field_id],
												' id="custom_field_'.$field_id.'" class="text custom_text"');
										}
									}
									elseif ($field_property['field_type'] == 2)
									{ // TextArea Field
										echo form::textarea('custom_field['.$field_id.']', $form['custom_field'][$field_id], ' class="custom_text" rows="3"');
									}
									echo "</div>";
								}
								?>
							</div>				

							<div class="row">
								<h4>What do you want to tell us?</h4>
                                <span><ul>
                                <li>Its really good to know if anyone else was involved and if you know their details.</li>
                                <li>Did anyone else witness this, do you know their details?</li>
                                <li>If you can put in as much detail as possible it will help confirm your report.</li>                                    
                                </ul></span>
								<?php print form::textarea('incident_description', $form['incident_description'], ' rows="12" cols="40"'); ?>
							</div>

				
							<!-- Photo Fields -->
							<div class="row link-row">
								<h4><?php echo Kohana::lang('ui_main.reports_photos');?></h4>
								<?php								
    								if ($incident != "0")
                        			{
                        				// Retrieve Media
                        				foreach($incident->media as $photo) 
                        				{
                        					if ($photo->media_type == 1)
                        					{
                        						print "<div class=\"report_thumbs\" id=\"photo_". $photo->id ."\">";

                        						$thumb = $photo->media_thumb;
                        						$photo_link = $photo->media_link;
												$prefix = url::base().Kohana::config('upload.relative_directory');
                        						print "<a class='photothumb' rel='lightbox-group1' href='$prefix/$photo_link'>";
                        						print "<img src=\"$prefix/$thumb\" >";
                        						print "</a>";

                        						print "&nbsp;&nbsp;<a href=\"#\" onClick=\"deletePhoto('".$photo->id."', 'photo_".$photo->id."'); return false;\" >".Kohana::lang('ui_main.delete')."</a>";
                        						print "</div>";
                        					}
                        				}
                        			}
			                    ?>
							</div>
							<div id="divPhoto">
								<?php
								$this_div = "divPhoto";
								$this_field = "incident_photo";
								$this_startid = "photo_id";
								$this_field_type = "file";
					
								if (empty($form[$this_field]['name'][0]))
								{
									$i = 1;
									print "<div class=\"row link-row\">";
									print form::upload($this_field . '[]', '', ' class="text long"');
									print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
									print "</div>";
								}
								else
								{
									$i = 0;
									foreach ($form[$this_field]['name'] as $value) 
									{
										print "<div ";
										if ($i != 0) {
											print "class=\"row link-row second\" id=\"" . $this_field . "_" . $i . "\">\n";
										}
										else
										{
											print "class=\"row link-row\" id=\"$i\">\n";
										}
										// print "\"<strong>" . $value . "</strong>\"" . "<BR />";
										print form::upload($this_field . '[]', $value, ' class="text long"');
										print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
										if ($i != 0)
										{
											print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#".$this_field."_".$i."\"); return false;'>remove</a>";
										}
										print "</div>\n";
										$i++;
									}
								}
								print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
								?>
							</div>
						<!--/div -->
				<?php print form::close(); ?>
				<?php
				if($id)
				{
					// Hidden Form to Perform the Delete function
					print form::open(url::site().'members/reports/', array('id' => 'reportMain', 'name' => 'reportMain'));
					$array=array('action'=>'d','incident_id[]'=>$id);
					print form::hidden($array);
					print form::close();
				}
				?>
			</div>
