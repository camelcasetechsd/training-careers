$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: '../lib/Controller/Career.php',
        data: '',
        dataType: '',
        success: function (data)
        {
            var obj = $.parseJSON(data);

            $(obj).each(function () {
                // warning for exceeding 180 hours duration for a career
                this.total_duration > 180 ? $color = "#FF0000" : $color = "#FFFFFF";

                // appending div to wrap div 
                $('#wrap').append('<div class="box"><div class="innerContent" id="' + this.id + '" ><h2 class="ass">' + this.name + '</h2>\n\
                <h3 style="color:' + $color + ';" >' + this.total_duration + '</h3></div></div>');
            });

            // redirecting to outlines page
            $('.innerContent').click(function (e) {
                $innerContentId = $(this).attr('id');
                window.location.replace('outlines.php?id=' + $innerContentId);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });



});
