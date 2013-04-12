<?php blocks::open("reports");?>
<?php blocks::title(Kohana::lang('ui_main.reports_listed'));?>
<table class="table-list">
	<thead>
		<tr>
			<th scope="col"><?php echo Kohana::lang('ui_main.title'); ?></th>
			<th scope="col">Name</th>
			<th scope="col">Desks</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($incidents->count() == 0)
		{
			?>
			<tr><td colspan="3"><?php echo Kohana::lang('ui_main.no_reports'); ?></td></tr>
			<?php
		}
		foreach ($incidents as $incident)
		{
			$incident_id = $incident->id;
			$incident_title = text::limit_chars(strip_tags($incident->incident_title), 40, '...', True);
			$incident_date = $incident->incident_date;
			$incident_date = date('M j Y', strtotime($incident->incident_date));
			$incident_location = $incident->location->location_name;
			$incident_username = $incident->user->name;

            $cats = "";
            foreach($incident->category as $cat)
            {
                $cats .= $cat->category_title." ";
            }

            if ($incident_username=="")
            {
                $incident_username="anonymous";
                $person = Database::instance()->query("select * from incident_person where incident_id = ".$incident_id);
                if (count($person)>0)
                {
                    if ($person[0]->person_first!="" or $person[0]->person_last!="")
                    {
                        $incident_username=$person[0]->person_first." ".$person[0]->person_last;
                    }
                }
            }


		?>
		<tr>
			<td><a href="<?php echo url::site() . 'reports/view/' . $incident_id; ?>"> <?php echo html::specialchars($incident_title) ?></a></td>
			<td><?php echo html::specialchars($incident_username) ?></td>
			<td><?php echo $cats ?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<a class="more" href="<?php echo url::site() . 'reports/' ?>"><?php echo Kohana::lang('ui_main.view_more'); ?></a>
<div style="clear:both;"></div>
<?php blocks::close();?>