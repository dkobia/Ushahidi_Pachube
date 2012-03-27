<div class="bg">
	<h2>
		<?php admin::manage_subtabs("pachube"); ?>
	</h2>
	<?php
	if ($form_error) {
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

	if ($form_saved) {
	?>
		<!-- green-box -->
		<div class="green-box">
			<h3><?php echo Kohana::lang('ui_main.feed_has_been');?> <?php echo $form_action; ?>!</h3>
		</div>
	<?php
	}
	?>
	
	<!-- tabs -->
	<div class="tabs">
		<!-- tabset -->
		<a name="add"></a>
		<ul class="tabset">
			<li><a href="<?php echo URL::site().'admin/pachube'; ?>" class="active"><?php echo Kohana::lang('ui_main.add_edit');?></a></li>
			<li><a href="<?php echo URL::site().'admin/pachube/discover'; ?>">Discover Feeds</a></li>
		</ul>
		<!-- tab -->
		<div class="tab" id="addedit">
			<?php print form::open(NULL,array('id' => 'pachubeFormMain',
			 	'name' => 'pachubeFormMain')); ?>
			<input type="hidden" name="action" id="action" value="a"/>
			<input type="hidden" id="feed_id" name="feed_id" value="" />
			<div class="tab_form_item">
				<strong>Pachube Feed ID:</strong><br />
				<span class="feed_label">http://www.pachube.com/feed/</span><?php print form::input('feed', $form['feed'], ' class="text"'); ?>
			</div>			
			<div class="tab_form_item">
				&nbsp;<br />
				<input type="image" src="<?php echo url::file_loc('img'); ?>media/img/admin/btn-save.gif" class="save-rep-btn" />
			</div>
			<?php print form::close(); ?>			
		</div>
	</div>
					
	<!-- report-table -->
	<div class="report-form">				
		<div class="table-holder">
			<table class="table" id="pachube-form">
				<thead>
					<tr>
						<th class="col-1">&nbsp;</th>
						<th class="col-2"><?php echo Kohana::lang('ui_main.feed');?></th>
						<th class="col-3">&nbsp;</th>
						<th class="col-4"><?php echo Kohana::lang('ui_main.actions');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr class="foot">
						<td colspan="4">
							<?php echo $pagination; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php if ($total_items == 0): ?>
						<tr>
							<td colspan="4" class="col">
								<h3><?php echo Kohana::lang('ui_main.no_results');?></h3>
							</td>
						</tr>
					<?php endif; ?>
					<?php foreach ($feeds as $feed): ?>
						<tr>
							<td class="col-1">&nbsp;</td>
							<td class="col-2">
								<div class="post">
									<h4><?php echo $feed->feed_name; ?>&nbsp;&nbsp;&nbsp;[<a href="javascript:showForm('feedDiv_<?php echo $feed->id; ?>')">View Triggers</a>]</h4>
									<?php if ($feed->feed_description): ?>
										<p><?php echo $feed->feed_description; ?></p>
									<?php endif; ?>								
								</div>
								<ul class="info">
									<li class="none-separator">Feed ID: 
										<strong><?php echo $feed->feed; ?></strong>
									</li>
									<li>Lon/Lat: 
										<strong><?php echo $feed->feed_lon; ?></strong>, <strong><?php echo $feed->feed_lat; ?></strong>
									</li>
									<li>Disposition: 
										<strong><?php echo $feed->feed_disposition; ?></strong>
									</li>
								</ul>
								<ul class="links">
									<li class="none-separator">Pachube Feed:
										<strong><?php echo html::anchor('https://pachube.com/feeds/'.$feed->feed);?></strong>
									</li>
								</ul>
								<?php if ($feed->feed_creator): ?>
								<ul class="links">
									<li class="none-separator">Creator:
										<strong><?php echo html::anchor($feed->feed_creator);?></strong>
									</li>
								</ul>
								<?php endif; ?>
								<?php if ($feed->feed_website): ?>
								<ul class="links">
									<li class="none-separator">Website:
										<strong><?php echo html::anchor($feed->feed_website);?></strong>
									</li>
								</ul>
								<?php endif; ?>							
							</td>
							<td>&nbsp;</td>
							<td class="col-4">
								<ul>
									<li class="none-separator"><a class="status_yes" href="javascript:feedAction('v','SHOW/HIDE','<?php echo(rawurlencode($feed->id)); ?>')"><?php if ($feed->feed_active) { echo Kohana::lang('ui_main.active'); } else { echo Kohana::lang('ui_main.inactive'); } ?></a></li>
									<li><a href="javascript:feedAction('d','DELETE','<?php echo(rawurlencode($feed->id)); ?>')" class="del"><?php echo Kohana::lang('ui_main.delete');?></a></li>
								</ul>
							</td>
						</tr>
						<tr style="margin:0;padding:0;border-width:0;">
							<td colspan="4" style="margin:0;padding:0;border-width:0;">
								<div id="feedDiv_<?php echo $feed->id; ?>" class="forms_fields">
									<strong>Current Triggers:</strong>
									<div id="feedTriggersDiv_<?php echo $feed->id; ?>" class="feed_triggers_current">
										<?php foreach($feed->pachube_trigger as $trigger): ?>
											<div id="trigger_<?php echo $trigger->id; ?>">&middot;&nbsp;When value <strong><?php echo $trigger->pachube_datastream->datastream_unit.' '.$trigger->trigger_type_long.' '.$trigger->trigger; ?></strong> post a report to <strong><?php echo $trigger->category->category_title; ?></strong> [<a href="javascript:deleteTrigger(<?php echo $trigger->id; ?>);">delete</a>]</div>
										<?php endforeach; ?>
									</div>
									<?php foreach($feed->pachube_datastream as $datastream): ?>
										<div class="feed_triggers">
											<?php print form::open(NULL,array('name' => 'pachubeFormTrigger')); ?>
												When value <strong><?php echo ($datastream->datastream_tag) ? $datastream->datastream_tag : 'stream '.$datastream->datastream_id; ?></strong>
												<select name="trigger_type_<?php echo $datastream->id; ?>" id="trigger_type_<?php echo $datastream->id; ?>">
													<option value="gt">goes &gt;</option>
													<option value="gte">goes &gt;=</option>
													<option value="lt">goes &lt;</option>
													<option value="lte">goes &lt;=</option>
													<option value="change">changes</option>
													<option value="eq">==</option>
													<option value="frozen">goes frozen</option>
													<option value="live">goes live</option>
												</select>
												<?php print form::input('trigger_value_'.$datastream->id, ''); ?> 
												post a report to 
												<select name="trigger_category_<?php echo $datastream->id; ?>" id="trigger_category_<?php echo $datastream->id; ?>">
													<?php foreach ($categories as $category): ?>
														<option value="<?php echo $category->id; ?>"><?php echo $category->category_title; ?></option>
													<?php endforeach; ?>
												</select>
												<input type="button" value="Add" id="trigger_btn_<?php echo $datastream->id; ?>" onclick="createTrigger(<?php echo $feed->id.','.$datastream->id.',\''.$datastream->datastream_id.'\''; ?>)">
											<?php print form::close(); ?>
										</div>	
									<?php endforeach; ?>								
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>