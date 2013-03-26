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
     
        <h3>Your assignments</h3><br/>
        <?php 
        $categories = Assignment_Model::get_assignments($user->id);
        if (count($categories)==0){
            echo "<div class=\"assignments-container\">No assignments yet...</div>";
        }
        else
        {
            foreach($categories as $category) 
            { 
                $joined = Assignment_Model::count_joined($category->id);
                ?>
                <div class="assignments-container">
                    <div style="float:left;">
                        <h3><?php echo $category->category_title?></h3>
                        <div style="clear:both"></div>
                        <span><?php echo $category->category_description; ?></span>
                        <div style="clear:both"></div>
                        <span><?php echo $joined?> Little J <?php if ($joined>1) { echo "'s"; }?> taking part</span>
                        <div style="clear:both"></div>
                        <span>Ends: XX/XX/XX</span>
                    </div>
                    <div style="float:right;">
                        <a href="reports/edit">Submit story</a>
                    </div>
                </div>
            <?php 
            }
        } ?>

<div style="clear:both;">
    
        <h3>Open assignments for you</h3>
        <?php
        $open_categories = Assignment_Model::get_unjoined_open_assignments($user->id);

        foreach($open_categories as $category) 
        { 
            $joined = Assignment_Model::count_joined($category->id);
            ?>
            <div class="assignments-container">
                <div style="float:left;">
                    <h3><?php echo $category->category_title?></h3>
                    <div style="clear:both"></div>
                    <span><?php echo $category->category_description; ?></span>
                    <div style="clear:both"></div>
                        <span><?php echo $joined?> Little J <?php if ($joined>1) { echo "'s"; }?> taking part</span>
                        <div style="clear:both"></div>
                    <span>Ends: XX/XX/XX</span>
                </div>
                <div style="float:right;">
                    <a href="<?php echo "assignment/join/".$category->id ?>" >Join in</a>
                </div>
            </div>
            <?php 
        } ?>
        </div>






