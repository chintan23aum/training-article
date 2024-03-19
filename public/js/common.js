$(document).ready(function() {

    $("#top_search_button").on('click', function (e) {
        e.preventDefault();
        var value = $('#top_search').val();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {'searchVal': value},
            success: function (response) {
                // Handle the response
                console.log(response.html);
                $("#articleDisplay").html(response.html);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    //advance search
    $("#top_advance_search_button").on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: advanceSearchURL,
            type: 'POST',
            data: $("#advanceSearch").serialize(),
            success: function (response) {
                // Handle the response
                console.log(response.html);
                $("#body_content").html(response.html);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $("#top_advance_search_reset").on('click', function (e) {
        $('#advanceSearch')[0].reset();
    });


    // Article sub category define
    $(document).off('change');
    $(document).on('change', '.article_category', function(e) {
        e.preventDefault();
        var main_option =$(this).hasClass('main_category');
        var categoryId = $(this).val();
        if (categoryId) {
            if(main_option){
                $('#subcategories').empty();
            }
            getCategoryById(categoryId);
        } else {
            if(main_option){
                $('#subcategories').empty();
            }
        }
    });


    //new display change to load article through ajax
    $(document).off('ajaxLoadArticle');
    $(document).on('click','.ajaxLoadArticle',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#advanceSearch')[0].reset();
        getLoadArticle(url);
    });
});

function getLoadArticle(url){
    $.ajax({
        url: url,
        type: 'POST',
        success: function (response) {
            console.log(response.html);
            $("#body_content").html(response.html);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function getCategoryById(parent_id,categoryId){
    var selected="";
    $.ajax({
        url: '/get-categories/' + parent_id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(parent_id);
            console.log(response);
            if (response.length > 0) {
                var options = '<div class="form-group"><label for="subcategory">Select Subcategory:</label><select name="subcategory[]" class="form-control article_category subcategory">';
                options += '<option value="">Select a subcategory</option>';
                $.each(response, function(index, category) {
                    selected="";
                    if(categoryId==category.id){
                        selected="selected='selected'";
                    }
                    options += '<option value="' + category.id + '" '+ selected +'>' + category.name + '</option>';
                });
                options += '</select></div>';
                $('#subcategories').append(options);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching categories:', error);
        }
    });
}


