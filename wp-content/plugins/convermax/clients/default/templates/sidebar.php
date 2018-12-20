<?php
$client = cm_get_client();
?>
<link rel='stylesheet' href='//client.convermax.com/static/the4x4guys-dev-wc/search.css' type='text/css' media='all' />
<aside class="blog-sb-widgets section-sb">
    <div class="theiaStickySidebar">
            <div class="section-filter" id="section-filter">
                <?php $client->getFacetsPanel($facets); ?>
            </div>
    </div>
</aside>