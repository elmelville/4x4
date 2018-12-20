<?php
$client = cm_get_client();
?>
<aside class="blog-sb-widgets section-sb">
    <div class="theiaStickySidebar">
            <div class="section-filter" id="section-filter">
                <?php $client->getFacetsPanel($facets); ?>
            </div>
    </div>
</aside>