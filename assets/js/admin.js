jQuery(document).ready(function($) {

    // displays products from the api via ajax when form is submitted
    $("#submit_keyword").click(function(e) {
        e.preventDefault();
        import_products(jQuery(this), 'keyword', null);
    });

    // attach loading div functionality
    $("#ajax_loading").bind("ajaxSend", function(){
        $('#ajax_loading_overlay').show();
        $(this).show();
    })
    .bind("ajaxComplete", function(){
        $('#ajax_loading_overlay').hide();
        $(this).hide();
    });
    
    // remove default search text on focus, put it back on blur
    $('.search_terms').live({
        focus: function() {
            if($(this).val() == 'Enter search terms') {
                $(this).val('');
            }
        },
        blur: function() {
            if($(this).val() == '') {
                $(this).val('Enter search terms');
            }
        }
    });

});

function import_products(el, method, page_data) {

  //var product_count     = page_data != null ? page_data.attr('data-product-count') : 0;
  var page_link_num     = page_data != null ? page_data.attr('data-page-number') : null;
  var keyword_terms     = jQuery("#search_term").length != 0 ? jQuery("#search_term").val() : jQuery("#search_terms_keyword").val();
  var current_page		= jQuery("#page_number").length != 0 ? jQuery("#page_number").val() : 1;
  
    jQuery.post(
      shcp_ajax.ajaxurl,
      {
        action        : "action_list",
        search_terms  : keyword_terms,
        page_number   : (page_link_num != null) ? page_link_num : current_page
      },
       function(response) {
        jQuery('#shcp_import_list').html(response);
        import_callback(this);
      }
    );

}

function import_callback() {

  // check all to import
  jQuery("#import_all").unbind('change').change(function() {
    var status = jQuery(this).is(":checked") ? true : false;
    jQuery('input[name="import_single[]"]').each(function() {
      jQuery(this).attr('checked', status);
    });
  });
  
  
  // activate save_products button
  jQuery("#save_products").unbind('click').click(function(e) {
    e.preventDefault();
    save_products();
  });

  // activate pagination links
  jQuery(".product_page_link, .product_prev_page_link, .product_next_page_link").unbind('click').click(function(e) {

    e.preventDefault();
    submit_form = jQuery('#keyword_form');
    import_products(submit_form, method, jQuery(this));
    
  });

}

function save_products() {

 var data;
 
 data = jQuery('#shcp_import_form').serialize();
 data += '&action=action_save';
 
  jQuery.ajax({
    type: 'post',
    dataType: 'html',
    url: shcp_ajax.ajaxurl,
    data: data,
    success: function(response) {
    	jQuery('#shcp_import_list').html(response);
    	import_callback(this);
    },
    error:function (xhr, ajaxOptions, thrownError){
        alert(xhr.status);
        alert(thrownError);
    }
  });
}


