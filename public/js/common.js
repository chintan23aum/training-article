
function searchArticle(){
    var value = $('#top_search').val();

    console.log(value);
    (function($) {
        // Your jQuery code here

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {'searchVal': value},
                success: function (response) {
                    console.log(response);
                    // Handle the response
                    $("#mainbody").html(response.html);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

    })(jQuery);
}

$(document).ready(function() {
    //
    // $("#top_search").on('keyup',function(){
    //     searchArticle();
    // });

    $("#top_search_button").on('click', function (e) {
        e.preventDefault();
        searchArticle();
    });
});
