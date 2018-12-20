<style type="text/css">
    .section-sb {
        width: 23%;
    }
</style>
<?php
$fields = $this->getFieldsFromUrl();
foreach ($fields as $field_name => $field_val) {
    $remove_url = $this->getRemoveUrl($fields, $field_name);
    $filter_items[] = '<li class="cmTemplate_Chips"><a title="Remove filter" href="'.$remove_url.'">'.$field_val.'<i class="fa fa-times"></i></a></li>';
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
        foreach ($facets as $facet) {
            if ($this->displayFacet($facet)) {
                ?>
                <div class="cm_flex-grow cm_DisplayName"><?php echo (($facet->FieldName == 'categories' && is_product_category()) ? 'Subcategory' : $facet->DisplayName); ?><span></span></div>
                <div class="cm_overflow_hidden">
                    <?php foreach ($facet->Values as $value) {
                        $url = $this->getFacetUrl($facet->FieldName, $value);
                        ?>
                        <a href="<?php echo $url; ?>" class="cm_flex">
                            <span class="cm_flex-grow"><?php echo $value->Value; ?></span><span><?php echo $value->HitCount; ?></span>
                        </a>
                    <?php } ?>
                </div>
            <?php }
        } ?>
    </div>
</div>