require([
    'jquery',
    'jquery/ui',
    'mage/url'
], function($, url) {
    "use strict";

    $(window).ready(function() {
        setTimeout(function() {
            // To remove Product Name field from Stock section.
            $("input[name='product_name']").parents(".admin__field").css("display", "none");

            var allocatedProductId = $.cookie("allocate_product_id");
            if (allocatedProductId) {
                $('select[name="product_id"] option[value="' + allocatedProductId + '"]').attr("selected", "selected").trigger("change");
            }
        }, 5000);
    });

    $(document).on("change", "select[name='product_id']", function(event) {
        var currentProductId = $("select[name='product_id']").val();
        
        // Creating controller url to execute and get attributes value.
        var string = window.location.href;
        var baseUrl = string.substring(string.indexOf('http'), string.lastIndexOf('admin'));
        var controllerUrl = baseUrl + "admin/adtones_stock/product/attributevalue";

        // if product is selected in selected list.
        if (currentProductId) {
            $.ajax({
                type: "POST",
                url: controllerUrl,
                data: { 'currentProductId': currentProductId },
                dataType: 'json',
                showLoader: true,
                success: function(responseData) {
                    $("input[name='artist']").val(responseData['artist']);
                    $("input[name='work']").val(responseData['work']);
                    $("input[name='product_name']").val(responseData['product_name']);
                    $("input[name='sku']").val(responseData['sku']);
                    $("input[name='artist']").trigger('change');
                    $("input[name='work']").trigger('change');
                    $("input[name='product_name']").trigger('change');
                    $("input[name='sku']").trigger('change');
                },
                error: function(responseData) {
                    console.log(responseData, "Error Report!!!");
                }
            });
        }
    });
});