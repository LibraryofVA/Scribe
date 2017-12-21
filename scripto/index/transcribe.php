<?php
$titleArray = array(__('Transcription Page'));
queue_css_file('scripto-transcribe');
$head = array('title' => html_escape(implode(' | ', $titleArray)), 'bodyid' => 'transcribePage');
echo head($head);
if (get_option('scripto_image_viewer') == 'openlayers') {
    echo js_tag('OpenLayers');
    // jQuery is enabled by default in Omeka and in most themes.
    // echo js_tag('jquery', 'javascripts/vendor');
}
?>
<script type="text/javascript">
jQuery(document).ready(function() {

    // Handle edit transcription page.
    jQuery('#scripto-transcription-page-edit').click(function() {
		jQuery.post(<?php echo js_escape(url('scripto/index/customlogin')); ?>)
		.done(function() {
			jQuery('#scripto-transcription-page-edit').prop('disabled', true).text('Saving transcription...');
			jQuery.post(
				<?php echo js_escape(url('scripto/index/page-action')); ?>, 
				{
					page_action: 'edit', 
					page: 'transcription', 
					item_id: <?php echo js_escape($this->doc->getId()); ?>, 
					file_id: <?php echo js_escape($this->doc->getPageId()); ?>, 
					wikitext: jQuery('#scripto-transcription-page-wikitext').val()
				}, 
				function(data) {
					jQuery('#scripto-transcription-page-edit').prop('disabled', false).text('Save transcription');
					jQuery('#scripto-transcription-page-html').html(data);
				}
			)
			.done(function() {
				jQuery.post(<?php echo js_escape(url('scripto/index/customlogout')); ?>);
			});
		});
    });

    <?php if ($this->scripto->isLoggedIn()): ?>

    // Handle default un/watch page.
    <?php if ($this->doc->isWatchedPage()): ?>
    jQuery('#scripto-page-watch').
        data('watch', true).
        text('<?php echo __('Unwatch page'); ?>').
        css('float', 'none');
    <?php else: ?>
    jQuery('#scripto-page-watch').
        data('watch', false).
        text('<?php echo __('Watch page'); ?>').
        css('float', 'none');
    <?php endif; ?>

    // Handle un/watch page.
    jQuery('#scripto-page-watch').click(function() {
        if (!jQuery(this).data('watch')) {
            jQuery(this).prop('disabled', true).text('<?php echo __('Watching page...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'watch',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>
                },
                function(data) {
                    jQuery('#scripto-page-watch').
                        data('watch', true).
                        prop('disabled', false).
                        text('<?php echo __('Unwatch page'); ?>');
                }
            );
        } else {
            jQuery(this).prop('disabled', true).text('<?php echo __('Unwatching page...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'unwatch',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>
                },
                function(data) {
                    jQuery('#scripto-page-watch').
                        data('watch', false).
                        prop('disabled', false).
                        text('<?php echo __('Watch page'); ?>');
                }
            );
        }
    });

    <?php endif; // end isLoggedIn() ?>

    <?php if ($this->scripto->canProtect()): ?>

    // Handle default un/protect transcription page.
    <?php if ($this->doc->isProtectedTranscriptionPage()): ?>
    jQuery('#scripto-transcription-page-protect').
        data('protect', true).
        text('<?php echo __('Unapprove page'); ?>').
        css('float', 'none');
    <?php else: ?>
    jQuery('#scripto-transcription-page-protect').
        data('protect', false).
        text('<?php echo __('Approve page'); ?>').
        css('float', 'none');
    <?php endif; ?>

    // Handle un/protect transcription page.
    jQuery('#scripto-transcription-page-protect').click(function() {
        if (!jQuery(this).data('protect')) {
            jQuery(this).prop('disabled', true).text('<?php echo __('Approving...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'protect',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>,
                    wikitext: jQuery('#scripto-transcription-page-wikitext').val()
                },
                function(data) {
                    jQuery('#scripto-transcription-page-protect').
                        data('protect', true).
                        prop('disabled', false).
                        text('<?php echo __('Unapprove page'); ?>');
					location.reload();
                }
            );
        } else {
            jQuery(this).prop('disabled', true).text('<?php echo __('Unapproving page...'); ?>');
            jQuery.post(
                <?php echo js_escape(url('scripto/index/page-action')); ?>,
                {
                    page_action: 'unprotect',
                    page: 'transcription',
                    item_id: <?php echo js_escape($this->doc->getId()); ?>,
                    file_id: <?php echo js_escape($this->doc->getPageId()); ?>
                },
                function(data) {
                    jQuery('#scripto-transcription-page-protect').
                        data('protect', false).
                        prop('disabled', false).
                        text('<?php echo __('Approve page'); ?>');
						location.reload();
                }
            );
        }
    });
    <?php endif; // end canProtect() ?>
	
	// Auto save functionality
	var myInterval = setInterval(function(){ autoSave() }, 30000);
	
	function autoSave() {
		textFromDB = <?php echo js_escape($this->doc->getTranscriptionPageWikitext()); ?>;
		if (jQuery('#scripto-transcription-page-wikitext').val() != '') {
			if (jQuery('#scripto-transcription-page-wikitext').val() != textFromDB) {
				jQuery.post(<?php echo js_escape(url('scripto/index/customlogin')); ?>)
				.done(function() {
					jQuery('#scripto-transcription-page-edit').prop('disabled', true).text('Auto saving...');
					jQuery.post(
						<?php echo js_escape(url('scripto/index/page-action')); ?>, 
						{
							page_action: 'edit', 
							page: 'transcription', 
							item_id: <?php echo js_escape($this->doc->getId()); ?>, 
							file_id: <?php echo js_escape($this->doc->getPageId()); ?>, 
							wikitext: jQuery('#scripto-transcription-page-wikitext').val()
						}, 
						function(data) {
							jQuery('#scripto-transcription-page-edit').prop('disabled', false).text('Save transcription');
							jQuery('#scripto-transcription-page-html').html(data);
						}
					)
					.done(function() {
						jQuery.post(<?php echo js_escape(url('scripto/index/customlogout')); ?>);
					});
				});
				clearInterval(myInterval);
			}
		}
	}
	// end Auto save functionality
});
</script>

<?php
    $page_id = $this->doc->getId();
    set_current_record('item',get_record_by_id('item', $page_id));
    $collection = get_collection_for_item();
    $collection_link = link_to_collection_for_item();
?>

<?php $base_Dir = basename(getcwd()); ?>

<div id="primary">
<?php echo flash(); ?>

    <ul class="breadcrumb">
        <li><a href="<?php echo WEB_ROOT; ?>">Home</a><span class="divider">/</span></li>
        <li><?php echo link_to_collection_for_item(); ?><span class="divider">/</span></li>
        <li><li><a href="<?php echo url(array('controller' => 'items', 'action' => 'show', 'id' => $this->doc->getId()), 'id'); ?>"><?php echo $this->doc->getTitle(); ?></a></li>
    </ul>
    <div id="scripto-transcribe" class="scripto">

        <h2><?php if ($this->doc->getTitle()): ?><?php echo $this->doc->getTitle(); ?><?php else: ?><?php echo __('Untitled Document'); ?><?php endif; ?></h2>

        <div>
            <div><strong><?php echo metadata($this->file, array('Dublin Core', 'Title')); ?></strong></div>
            <div>image <?php echo html_escape($this->paginationUrls['current_page_number']); ?> of <?php echo html_escape($this->paginationUrls['number_of_pages']); ?></div>
            <div><?php if ($this->doc->getIsReferencedBy()):echo "more information: <a href=\"" . $this->doc->getIsReferencedBy() . "\" target=\"_blank\">digital collection</a>"; else: endif; ?></div>
            <!-- pagination -->
			<div id="pagination">
			<?php if (isset($this->paginationUrls['previous'])): ?><a><button type="submit" class="btn btn-mini nav-btn" onClick="parent.location='<?php echo html_escape($this->paginationUrls['previous']); ?>'">prev</button></a><?php endif; ?>
			<?php if (isset($this->paginationUrls['next']) && isset($this->paginationUrls['previous'])): echo "|"; endif; ?>
			<?php if (isset($this->paginationUrls['next'])): ?> <a><button type="submit" class="btn btn-mini nav-btn" onClick="parent.location='<?php echo html_escape($this->paginationUrls['next']); ?>'">next</button></a><?php endif; ?>
			<?php if (intval(html_escape($this->paginationUrls['number_of_pages'])) > 1): ?> | <a><button type="submit" class="btn btn-mini nav-btn" onClick="parent.location='<?php echo html_escape(url(array('controller' => 'items', 'action' => 'show', 'id' => $this->doc->getId()), 'id')); ?>'">all pages</button></a><?php endif; ?>
			 | <a><button type="submit" class="btn btn-mini nav-btn" onClick="parent.location='<?php echo html_escape(url(array('item-id' => $this->doc->getId(), 'file-id' => $this->doc->getPageId(), 'namespace-index' => 0), 'scripto_history')); ?>'">history</button></a>
			</div>
       		<p>Zoom in to read each word clearly.<br />Some images may have writing in several directions. To rotate an image, hold down shift-Alt and use your mouse to spin the image so it is readable.</p>
        </div>

        <!-- document viewer -->
        <?php echo file_markup($this->file, array('imageSize' => 'fullsize')); ?>

        <div id="transcription-block" style="display: inline-block;">
			<!-- transcription -->
			<div id="scripto-transcription">
			  <div id="scripto-transcription-edit">
			<?php if ($this->doc->isProtectedTranscriptionPage()): ?>
				<div class="alert alert-error">
					<strong>This transcription is complete!</strong>
				</div><!--alert alert-error-->
				<div id="scripto-transcription-page-html"><?php echo $this->transcriptionPageHtml; ?></div>
			<?php elseif ($this->doc->canEditTranscriptionPage()): ?>
                <?php if (metadata($this->item, array('Dublin Core', 'Type')) == "photoTagging") { ?>
					<strong>Enter your descriptive words below:</strong>
					<ul class="tips">
						<li>Closely examine this photograph and add as many descriptive words as you can think of.</li>
                        <li>Separate each word with a comma.</li>
                        <li>We appreciate your help in making our online photographs more discoverable!</li>
					</ul>
					<div><?php echo $this->formTextarea('scripto-transcription-page-wikitext', $this->doc->getTranscriptionPageWikitext(), array('cols' => '76', 'rows' => '16')); ?></div>
					  <div>Want more space to type? See how to <a href="https://www.youtube.com/watch?v=pdp9jJ1uaGY" target="_blank">expand your display</a>!</div>
					<?php echo $this->formButton('scripto-transcription-page-edit', __('Save tags'), array('class' => 'btn btn-primary')); ?> 
				<?php } else { ?>
					<div><?php echo $this->formTextarea('scripto-transcription-page-wikitext', $this->doc->getTranscriptionPageWikitext(), array('cols' => '76', 'rows' => '16')); ?></div>
					<strong>Enter your transcription above:</strong>
					<ul class="tips">
						<li>Copy the text as is, including misspellings and abbreviations.</li>
						<li>No need to account for formatting (e.g. spacing, line breaks, alignment); the goal is to provide text for searching.</li>
						<li>If you can't make out a word, enter "[illegible]"; if uncertain, indicate with square brackets, e.g. "[town?]"</li>
						<li><a href="/transcribe/about#tips">View more transcription tips</a></li>
						<li>Want more space to type? See how to <a href="https://www.youtube.com/watch?v=pdp9jJ1uaGY" target="_blank">expand your display</a>!</li>
					</ul>
					<?php echo $this->formButton('scripto-transcription-page-edit', __('Save transcription'), array('class' => 'btn btn-primary')); ?> 
				<?php } ?>
			<?php else: ?>
				<p><?php echo __('You don\'t have permission to transcribe this page.'); ?></p>
				<div id="scripto-transcription-page-html"><?php echo $this->transcriptionPageHtml; ?></div>
			<?php endif; ?>
			  <?php if ($this->scripto->isLoggedIn()): ?><?php echo $this->formButton('scripto-page-watch', __('Watch Page'), array('class' => 'btn btn-primary')); ?> <?php endif; ?>
			  <?php if ($this->scripto->canProtect()): ?><?php echo $this->formButton('scripto-transcription-page-protect', __('Approve Page'), array('class' => 'btn btn-primary')); ?> <?php endif; ?>
			  </div><!-- #scripto-transcription-edit -->
			</div><!-- #scripto-transcription -->
		</div><!-- #transcription-block -->
    </div><!-- #scripto-transcribe -->
</div>
<?php echo foot(); ?>
