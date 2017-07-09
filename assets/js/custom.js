$(window).on('load', function(){
    $('.ajax-form').on('submit', function(e){
        e.preventDefault();
        console.log();
        goAjax(
            'POST',
            $(this).attr('action'),
            $(this).serialize()
        );
    });
});

function goAjax(method, url, data){
    $.ajax({
        type: method,
        url: url,
        data: data,
        success: function(data) {
            //var obj = JSON.parse(data);
            console.log(data);
        }
    });
}