jQuery(document).ready(function($) {


// load categories on widget form submit
$('#sortCategories').submit(function(event) {
    event.preventDefault();
    get_subcategories();
});

// widget reset button handler
$('#sortCategories .reset-catfilter').click(function(event) {
    var load_defauld_categories = function(){
       get_subcategories();
    };
    setTimeout(load_defauld_categories, 200);
});



function getSelectedCategories(select_number){
    /*
        get selected category value from <select> field
    */

    var parent_category =  $(".sort-categories-form .sort-select-" + select_number + " option:selected").val();
    if (parent_category) {
        return parent_category; 
    } else{
        return 'none';
    }
}

function getSearchFieldValue(){
    /*
        get keyword search field value
    */
    var search_value =  $(".sort-categories-form .cat-search-field").val();
    return search_value; 
}

	
function get_subcategories(){
    /*
        ajax request with filtered data
    */
	var ajaxurl = GlimpsesAjax.ajaxurl;

	$.ajax({
        type: 'GET',
        url: ajaxurl,
        data: {
            action: 'load_subcategories',
            parent_category: getSelectedCategories('1'),
            parent_category_2: getSelectedCategories('2'),
            parent_category_3: getSelectedCategories('3'),
            search_field_value: getSearchFieldValue()
        },
        beforeSend: function (){
            $('.load-categories-cover').css('display', 'block');
        },
        success: function(data){   
            $('.load-categories-cover').css('display', 'none');

            $('.no-categories-loaded').remove()
            $('.site-main .square').remove();

            $('.site-main').append(data);
        },
        error: function(){
        	console.log('error');
        }
    });
}


// theme corrections
$('.category-38 .content-area .article-card:eq( 1 )').removeClass('double');
$('.category-38 .content-area .article-card').last().addClass('double');




}) //document Ready;



