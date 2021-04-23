requirejs(['jquery'],function($){
   selectedValue = [];
   var databaseText;
   var saveAtDatabase = $("#slide_slider_specific_display_page_product");
   $(document).ready(function($){

      
   //   To Push Data From Database into TextArea n In Goball Array->selectedValue
      databaseText = jQuery("#slide_slider_specific_display_page_product").text();
      if(databaseText !== null && databaseText !== '' && databaseText !== '[""]'  ){
         selectedValue = $.parseJSON(databaseText);
         saveAtDatabase.empty().append(selectedValue+',');
      }else if(databaseText == '[""]'){
         saveAtDatabase.empty().append();
      }
      $("#slide_slider_specific_display_page_product").text();
      $("#slide_product_sku").focusin(function(){
      $("#slide_slider_specific_display_page_product_name").parents('div:eq(1)').show();
      });
      // To Selected Products For Sider
      var display = $("#display_custom_data");
      document.getElementById("slide_slider_specific_display_page_product_name").onchange = function(){
         $.each($("#slide_slider_specific_display_page_product_name option:selected"),function(){
            var productVal = $(this).val();
            var productText = $(this).text();
            if(selectedValue.length >0){
               $(".no-Product").hide();
            }
            if(selectedValue.length == 0 || $.inArray(productVal,selectedValue )== -1 ){
               selectedValue.push(productVal);
               display.append('<li class="list" value='+ productVal +'>'+productText+'<span class="closing-tag">&#10008;</span></li>');
               saveAtDatabase.empty().append(selectedValue+',');
            } 
            if(selectedValue.length > 0){
               $(".no-Product,.add-products").hide();
            }
         });
      }
      // To Remove Selected Products
      $( "#selected_products" ).on( "click", ".closing-tag", function() {
         var productVal = $(this).parent().val();
         selectedValue = jQuery.grep(selectedValue, function(value) {
            return value != productVal;
         });
         $(this).parent().remove();
         saveAtDatabase.empty().append(selectedValue+',');
         var checkToDeselectOption = jQuery('#slide_slider_specific_display_page_product_name option:selected');
         if(productVal == checkToDeselectOption.val()){
            $("select option[value='"+productVal+"']").prop("selected", false);
         }
         if(selectedValue.length == 0){
            $(".no-Product,.add-products").show();
         }

      });
   });
   
});