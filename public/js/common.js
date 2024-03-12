$(document).ready(function() {
    $("#top_search_button").on('click', function (e) {
        e.preventDefault();
        var value = $(this).val();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {'searchVal': value},
            success: function (response) {
                // Handle the response
                $("#mainbody").html(response.html);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
