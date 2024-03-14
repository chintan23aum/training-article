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
});
