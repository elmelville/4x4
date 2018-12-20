jQuery(document).ready(function($) {
    $("#reindex").click(function(e){
        $("#reindex").val('Indexing...');
        $("#reindex").attr('disabled','disabled');
        $.ajax({
            url: $(this).attr('data-url'),
            complete: function () {
                checkIndexingStatus();
            }
        });
    })
});

function checkIndexingStatus() {
    $.ajax({
        url: $("#reindex").attr('data-api_url') + '/indexing/status/json',
        complete: function (response) {
            var data = JSON.parse(response.responseText);
            if(data.InProgress) {
                setTimeout(checkIndexingStatus, 5000);
            } else {
                setIndexedProducts();
                $("#reindex").val('Reindex');
                $("#reindex").removeAttr('disabled');
            }
        }
    });
}

function setIndexedProducts() {
    $.ajax({
        url: $("#reindex").attr('data-api_url') + '/search/json?pagesize=0&catalog=default',
        success: function (data) {
            $("#cm_products").text(data.TotalHits);
        }
    });
}