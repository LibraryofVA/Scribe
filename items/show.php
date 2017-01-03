<?php
echo head(array(
    'title' => metadata('item', array('Dublin Core', 'Title')),
    'bodyclass' => 'items show',
));

$collection = get_collection_for_item();
?>
<div id="primary">
    <ul class="breadcrumb">
      <li><?php echo link_to_home_page(); ?><span class="divider">/</span></li>
      <li><?php echo link_to_collection_for_item($collection->name, array('id' => 'item-collection-link',)); ?><span class="divider">/</span></li>
      <li class="active"><?php echo metadata('item', array('Dublin Core', 'Title')); ?></li>
    </ul>

    <h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

    <!-- The following returns all of the files associated with an item. -->
    <div id="itemfiles" class="element">

        <div class="element-text">
            <?php
            require_once PLUGIN_DIR . '/Scripto/libraries/Scripto.php';
           // require_once APP_DIR . '/helpers/Media.php';
            $scripto = ScriptoPlugin::getScripto();
            // $helper = new Omeka_View_Helper_Media;
            $files =  return_files_for_item(array()); ?>

            <ul class="thumbnails">
            <?php foreach ($files as $file) :
                $status = $scripto->getPageTranscriptionStatus($file->id);

                switch ($status) {
                case 'Completed':
                    $label = "label-important";
                    break;
                case 'Needs Review':
                    $label = "label-warning";
                    break;
                case 'Not Started':
                    $label = "label-success";
                    break;
                default:
                    $label = "label-important";
                    break;
                }

                 $fileTitle = metadata($file, array('Dublin Core', 'Title'));
                 // Using monospaced font to make this work
                 if (strlen($fileTitle) <= 18) {
                    $fileTitle .= '<br /><br /><br />';
                 } elseif (strlen($fileTitle) <= 36 ) {
                     $fileTitle .= '<br /><br />';
                 }
             ?>
                <li class="span2">
                    <div class="thumbnail">
                        <a href="<?php echo url(array('action' => 'transcribe', 'item-id' => $file->item_id, 'file-id' => $file->id), 'scripto_action_item_file'); ?>">
                            <?php echo file_image('square_thumbnail', array(), $file); ?>
                        </a>
                        <h4><?php echo $fileTitle; ?></h4>
                        <span class="label <?php echo $label; ?>"><?php echo $status; ?></span>
                     </div>
                 </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php if (metadata('item', array('Dublin Core', 'Description'))): ?>
      <p class="dublin_field_title">Description</p>
      <p class="dublin_field_detail"><?php echo metadata('item', array('Dublin Core', 'Description')); ?></p>
    <?php endif; ?>
    <?php if (metadata('item', array('Dublin Core', 'Date'))): ?>
      <p class="dublin_field_title">Date</p>
      <p class="dublin_field_detail"><?php echo metadata('item', array('Dublin Core', 'Date')); ?></p>
    <?php endif; ?>
    <?php if (metadata('item', array('Dublin Core', 'Abstract'))): ?>
      <p class="dublin_field_title">Abstract</p>
      <p class="dublin_field_detail"><?php echo metadata('item', array('Dublin Core', 'Abstract')); ?></p>
    <?php endif; ?>
    <?php if (metadata('item', array('Dublin Core', 'Bibliographic Citation'))): ?>
      <p class="dublin_field_title">Bibliographic Citation</p>
      <p class="dublin_field_detail"><?php echo metadata('item', array('Dublin Core', 'Bibliographic Citation')); ?></p>
    <?php endif; ?>
    <?php if (metadata('item', array('Dublin Core', 'Provenance'))): ?>
      <p class="dublin_field_title">Provenance</p>
      <p class="dublin_field_detail"><?php echo metadata('item', array('Dublin Core', 'Provenance')); ?></p>
    <?php endif; ?>
<?php echo foot(); ?>
