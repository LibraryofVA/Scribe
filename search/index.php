<?php
$pageTitle = __('Search') . ' ' . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
?>
<h1><?php echo $pageTitle; ?></h1>
<?php if ($total_results): ?>
<?php echo pagination_links(); ?>
<ul class="thumbnails">
        <?php foreach (loop('search_texts') as $searchText): ?>
        <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
        <?php $recordType = $searchText['record_type']; ?>
        <?php set_current_record($recordType, $record); ?>
        <?php
			$progress_needs_review = metadata($record, array('Scripto', 'Percent Needs Review'));
			$progress_percent_completed = metadata($record, array('Scripto', 'Percent Completed'));
			$progress_status = $progress_needs_review + $progress_percent_completed;
								if ($progress_status == null) {
									 $progress_status = 0;
								}
								$progress_not_started = 100 - $progress_status;
								
								// set status messages
								if ($progress_percent_completed == 100) {
									$status_message = 'Completed';
								} elseif ($progress_status == 100) {
									$status_message = 'Needs Review';
								} elseif ($progress_status != 0 and $progress_status != 100) {
									$status_message = $progress_status . '% ' . 'Started';
								} else {
									$status_message = 'Not Started';
								}
        ?>  
            <li>
               <div id="col-images" class="thumbnail right-caption span4">
				 <a href="<?php echo record_url($record, 'show'); ?>"><?php echo(record_image($recordType, 'square_thumbnail', array('class' => 'span2', 'alt' => $searchText['title']))); ?></a>
                 <div class="caption">
                   <a href="<?php echo record_url($record, 'show'); ?>" class="permalink"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a><br><br>
                   <?php echo $status_message . '<br />
								  <div class="progress">
								  <div title="'.$progress_percent_completed.'% Completed" class="bar bar-danger" style="width:'.$progress_percent_completed .'%;"></div>
								  <div title="'.$progress_needs_review.'% Needs Review" class="bar bar-warning" style="width:'.$progress_needs_review .'%;"></div>
								  </div>'; ?>
				 </div><!-- end caption -->
			   </div>
			</li>
        <?php endforeach; ?>
</ul>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>
<?php echo foot(); ?>
