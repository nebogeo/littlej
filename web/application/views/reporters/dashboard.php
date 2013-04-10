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

<!-- user info -->
<!-- content -->
<div class="content-container">


<div class="box" style="padding:10px; margin: 0px;">
    <div style="float:left; width:50%">
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
        </div>
	</div>
</div>

<div style="clear:both;">

<!-- the map -->
<div style="float:left; width:80%;">
	<?php
	// Map and Timeline Blocks
    echo $div_map;
	?>
</div>


<div style="float:right; width:20%">
	<!-- filters box -->
	<div id="the-filters" class="map-menu-box">

		<?php
		// Action::main_sidebar_pre_filters - Add Items to the Entry Page before filters
		Event::run('ushahidi_action.main_sidebar_pre_filters');
		?>

		<!-- report category filters -->
		<div id="report-category-filter" style="background:#E7E3DA;">
			<h3>Filter by category</h3>
            <br/>
			<ul id="category_switch" class="category-filters">
			<?php
			$color_css = 'class="swatch" style="background-color:#'.$default_map_all.'"';
			$all_cat_image = '';
			if ($default_map_all_icon != NULL)
			{
				$all_cat_image = html::image(array(
					'src'=>$default_map_all_icon,
					'style'=>'float:left;padding-right:5px;'
				));
				$color_css = '';
			}
			?>
			<li>
				<a class="active" id="cat_0" href="#">
					<span <?php echo $color_css; ?>><?php echo $all_cat_image; ?></span>
					<span class="category-title"><?php echo Kohana::lang('ui_main.all_categories');?></span>
				</a>
			</li>
			<?php
				foreach ($categories as $category => $category_info)
				{
					$category_title = htmlentities($category_info[0], ENT_QUOTES, "UTF-8");
					$category_color = $category_info[1];
					$category_image = ($category_info[2] != NULL)
					    ? url::convert_uploaded_to_abs($category_info[2])
					    : NULL;
					$category_description = htmlentities(Category_Lang_Model::category_description($category), ENT_QUOTES, "UTF-8");

					$color_css = 'class="swatch" style="background-color:#'.$category_color.'"';
					if ($category_info[2] != NULL)
					{
						$category_image = html::image(array(
							'src'=>$category_image,
							'style'=>'float:left;padding-right:5px;'
							));
						$color_css = '';
					}

					echo '<li>'
					    . '<a href="#" id="cat_'. $category .'" title="'.$category_description.'">'
					    . '<span '.$color_css.'>'.$category_image.'</span>'
					    . '<span class="category-title">'.$category_title.'</span>'
					    . '</a>';

					// Get Children
					echo '<div class="hide" id="child_'. $category .'">';
					if (sizeof($category_info[3]) != 0)
					{
						echo '<ul>';
						foreach ($category_info[3] as $child => $child_info)
						{
							$child_title = htmlentities($child_info[0], ENT_QUOTES, "UTF-8");
							$child_color = $child_info[1];
							$child_image = ($child_info[2] != NULL)
							    ? url::convert_uploaded_to_abs($child_info[2])
							    : NULL;
							$child_description = htmlentities(Category_Lang_Model::category_description($child), ENT_QUOTES, "UTF-8");

							$color_css = 'class="swatch" style="background-color:#'.$child_color.'"';
							if ($child_info[2] != NULL)
							{
								$child_image = html::image(array(
									'src' => $child_image,
									'style' => 'float:left;padding-right:5px;'
								));
								$color_css = '';
							}

							echo '<li style="padding-left:20px;">'
							    . '<a href="#" id="cat_'. $child .'" title="'.$child_description.'">'
							    . '<span '.$color_css.'>'.$child_image.'</span>'
							    . '<span class="category-title">'.$child_title.'</span>'
							    . '</a>'
							    . '</li>';
						}
						echo '</ul>';
					}
					echo '</div></li>';
				}
			?>
			</ul>
			<!-- / category filters -->
		</div>

		<!-- report type filters -->
		<div id="report-type-filter" class="filters" style="background:#E7E3DA;">
			<h3>Filter by type</h3><br/>
			<ul class="category-filters">
				<li><a id="media_0" class="active" href="#"><span><?php echo Kohana::lang('ui_main.reports'); ?></span></a></li>
				<li><a id="media_4" href="#"><span><?php echo Kohana::lang('ui_main.news'); ?></span></a></li>
				<li><a id="media_1" href="#"><span><?php echo Kohana::lang('ui_main.pictures'); ?></span></a></li>
				<li><a id="media_2" href="#"><span><?php echo Kohana::lang('ui_main.video'); ?></span></a></li>
				<li><a id="media_0" href="#"><span><?php echo Kohana::lang('ui_main.all'); ?></span></a></li>
			</ul>
			<div class="floatbox">
    			<?php
	    		// Action::main_filters - Add items to the main_filters
    			Event::run('ushahidi_action.map_main_filters');
	    		?>
			</div>
		</div>
    </div>
</div>


	<!-- content blocks -->
	<div class="content-blocks clearingfix">
		<ul class="content-column">
			<?php blocks::render(); ?>
		</ul>
	</div>
	<!-- /content blocks -->

</div>
<!-- content -->
