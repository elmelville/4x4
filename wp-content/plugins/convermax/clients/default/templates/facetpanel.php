<?php
$fields = $this->getFieldsFromUrl();
$current_url = $this->getUrl($fields);
foreach ($fields as $field_name => $field_val) {
    $remove_url = $this->getRemoveUrl($fields, $field_name);
    $filter_items[] = '<li class="cmTemplate_Chips"><a title="Remove filter" href="'.$remove_url.'">'.$field_val.'</a></li>';
}
if (!empty($filter_items)) {
    $filter_items = implode("\r\n", $filter_items);
?>
    <div class="cm_FilterChips">
        <h6 class="cm_DisplayName">Current Search</h6>
        <ul class="cmRepeater_Chips">
            <?php echo $filter_items; ?>
        </ul>
    </div>
<?php } ?>
<div class="cm_leftPanel cm_loading-relative">
    <div class="cm_FacetsPanel">
    <?php
    $symbol = strpos($current_url, '?') ? '&' : '?';
    foreach ($facets as $facet) { ?>
        <div class="cm_flex-grow cm_DisplayName"><?php echo $facet->DisplayName; ?><span>1</span></div>
        <div class="cm_overflow_hidden">
        <?php foreach ($facet->Values as $value) { ?>
            <a href="<?php echo $current_url.$symbol.urlencode('cm_'.$facet->FieldName) .'='.urlencode($value->Value); ?>" class="cm_flex">
                <span class="cm_flex-grow"><?php echo $value->Term; ?></span><span><?php echo $value->HitCount; ?></span>
            </a>
        <?php } ?>
       </div>
    <?php } ?>
    </div>
</div>