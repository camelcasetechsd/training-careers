$(document).ready(function () {
    $.ajax({
        type: "POST",
        url: 'Handler.php',
        data: {
            'method': 'listCareers'
        },
        dataType: '',
        success: function (data)
        {   // to use it later in applay to all
            window.careers = data;
            
            var obj = $.parseJSON(data);

            $(obj).each(function () {
                // warning for exceeding 180 hours duration for a career
                if(this.total_duration > 10800) {
                    $pannel     = "panel-danger";
                    $background = "text-danger";
                    $button = "btn-danger";
                } else {
                    $pannel     = "";
                    $background = "";
                    $button = "btn-success";
                }

                // appending div to wrap div 
                $('#wrap').append('<div class="col-md-4">\n\
                                    <div class="panel panel-default '+ $pannel +' '+ $background +'" id="' + this.id + '" >\n\
                                        <div class="panel-heading"><h2 class="panel-title">' + this.name + '</h2></div>\n\
                                        <div class="panel-body"><h3>Duration ' + this.total_duration_formatted + '</h3>\n\
                                        <p><a class="btn '+ $button +' pull-right" href="outlines.php?id=' + this.id + '">Outlines »</a></p></div>\n\
                                    </div>\n\
                                    </div>');
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });



});
