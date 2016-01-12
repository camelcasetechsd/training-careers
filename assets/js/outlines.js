/*
 * This script is meant to handle the following cases:
 * 1- list all courses 
 * 2- list all categories
 * 3- list outlines for specific course
 * 4- select the corresponding category for this course 
 */

$(document).ready(function () {

    //jQuery function to get value of attribute from url
    $.urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        }
        else {
            return results[1] || 0;
        }
    }
    // setting current career id to a hidden field
    $('#current_career').val($.urlParam('id'));

    // getting courses
    $.ajax({
        type: "POST",
        url: 'Handler.php',
        data: {
            'method': 'listCourses'
        },
        dataType: '',
        success: function (data) {
            var obj = $.parseJSON(data);

            $(obj).each(function () {
                $('#course_name').append('<option value="' + this.id + '" >' + this.name + '</option>')
            });
        }
    });

    // getting category names
    $.ajax({
        type: "POST",
        url: 'Handler.php',
        data: {
            'method': 'listCategories'
        },
        dataType: '',
        success: function (data) {
            var obj = $.parseJSON(data);

            $(obj).each(function () {
                $('#course_category').append('<option value="' + this.id + '" >' + this.name + '</option>')
            });
        }
    });

    // loading outlines for specific course

    $("#course_name").click(function () {

        $courseId = $(this).find('option:selected').val();
        $courseName = $(this).find('option:selected').text();

        //selecting corresponding course category
        $.ajax({
            type: "POST",
            url: 'Handler.php',
            data: {
                'method': 'getCategoryByCourse',
                'target': 'name',
                'value': $courseName
            },
            dataType: 'json',
            success: function (data) {
                $(data).each(function () {
                    $('#course_category').val(this.category_id);
                });
            }
        });

        buildOutlines('getOutlinesByCourse', $courseId);


    });

    $("#save_outline").click(function () {

        $outlineId  	 = $('#current_outline').val();
        $outlineName 	 = $('#outline_name').val();
        $outlineDuration = $('#outline_duration').val();

        //selecting corresponding course category
        $.ajax({
            type: "POST",
            url: 'Handler.php',
            data: {
                'method': 'saveOutlineData',
                'outline_id': $outlineId,
                'name': $outlineName,
                'duration': $outlineDuration
            },
            dataType: 'json',
            success: function (data) {
               
            }
        });


    });
    /*
     * Now Handling case if user choosed by Category
     */

    $('#course_category').click(function () {

        $categoryId = $(this).find('option:selected').val();

        $('#course_name').empty().append('<option value="" >__Select__</option>');

        $.ajax({
            type: "POST",
            url: 'Handler.php',
            data: {
                'method': 'getCoursesByCategory',
                'target': 'category_id',
                'value': $categoryId
            },
            dataType: 'json',
            success: function (data) {
                $(data).each(function () {
                    $('#course_name').append('<option value="' + this.id + '" >' + this.name + '</option>')
                });
            }
        });

        buildOutlines('getOutlinesByCategory', $categoryId);

    });

});


function buildOutlines(listingType, id) {
    $careerId = $('#current_career').val();
    // getting outlines for $course_id
    $.ajax({
        type: "POST",
        url: 'Handler.php',
        data: {
            'method': listingType,
            'listing_type_id': id
        },
        dataType: 'json',
        success: function (data) {
//              //removing table and its content in case make consecutive ajaxs
            $('#course_outlines_tbody').empty();
            $('#course_outlines').remove();
            // creating table head
//                $('#outlines_container').append('<table id="course_outlines"></table>');
            $('#outlines_container').append('<table id="course_outlines" class="outlinestable">\n\
            <tbody id="course_outlines_tbody"><tr><td>Category Code</td><td>Course Code</td><td>Name</td><td>D. Name</td><td>Duration(min)</td><td>D. Duration(min)</td><td>Edit</td><td>Apply</td><td>Apply All</td></tr></tbody>');
            // creating table rows
            $(data).each(function () {

                $applyStatus = "";
                $applyToAllStatus = "";
                $statusCount = 0;
                $(this.careers).each(function () {
                    if (this.career_id == $careerId) {
                        $applyStatus = "checked";
                    }
                    $statusCount++;
//                    
                });
                if ($statusCount == 10) {
                    $applyToAllStatus = "checked";
                }




                $('#course_outlines_tbody').append('<tr id =' + this.id + '><td>' + this.category_code + '</td><td>' + this.course_code + '</td><td>' + this.name + '</td><td>' + this.default_name + '</td><td>' + this.duration + '</td><td>' + this.default_duration + '</td>\n\
                <td><a href="edit-outline.php?outline_id='+ this.id +'&career_id='+ $.urlParam('id') +'">Edit</a></td><td><input type="checkbox" class="apply" ' + $applyStatus + '></td><td><input type="checkbox" class="applyToAll" ' + $applyToAllStatus + '></td></tr>');






            });

            $('#outlines_container').append('</table');



            /*
             * Preforming operations on appended checkboxes
             * 1- apply to current career
             * 2- apply to all careers
             */

            /*
             *  Handling apply to current career
             */
            $('.apply').change(function () {
                //if the user wanted to apply
                if ($(this).is(":checked")) {
                    $outlineId = $(this).closest('tr').attr('id');
                    $careerId = $('#current_career').val();
                    $.ajax({
                        type: "POST",
                        url: 'Handler.php',
                        data: {
                            'method': 'applyToCareer',
                            'outline_id': $outlineId,
                            'career_id': $careerId
                        },
                        dataType: 'json',
                        success: function (data) {
                            $("#career_duration").text(data.duration);
                        }
                    });
                }
                // if user wanted to remove this outline from this career
                else {


                    $outlineId = $(this).closest('tr').attr('id');
                    $careerId = $('#current_career').val();
                    $(this).closest('tr').find('[type=checkbox]').prop('checked', false);

                    $.ajax({
                        type: "POST",
                        url: 'Handler.php',
                        data: {
                            'method': 'removeOutlineFromCareer',
                            'outline_id': $outlineId,
                            'career_id': $careerId
                        },
                        dataType: 'json',
                        success: function (data) {
                            $("#career_duration").text(data.duration);                            
                        }
                    });


                }
//                    
            });

            /*
             * Hanling user request to apply specific outline to all
             * careers
             */

            //if the user wanted to apply a outline to all careers
            $('.applyToAll').change(function () {


                /*
                 * 2 extream cases :
                 * 1- if apply was unchecked ->check it (handled here)
                 * 2- if apply was checked -> leave it as it is (handled at backend)
                 */

                if ($(this).is(":checked")) {
                    var returnVal = confirm("Are you sure?");
                    $(this).attr('checked', returnVal);
                    // approved
                    switch (returnVal) {
                        case true:
                            // to check apply too
                            $(this).closest('tr').find('[type=checkbox]').prop('checked', true);
                            $outlineId = $(this).closest('tr').attr('id');
                            $careerId = $('#current_career').val();
                            $.ajax({
                                type: "POST",
                                url: 'Handler.php',
                                data: {
                                    'method': 'applyToAllCareers',
                                    'outline_id': $outlineId,
                                    'career_id': $careerId
                                },
                                dataType: 'json',
                                success: function (data) {
                                    $("#career_duration").text(data.duration);                                     
                                }
                            });

                            break;
                            //canceled     
                        case false:



                            break;
                    }

                }
                //if user wanted to remove this outline from all careers
                else {

                    $outlineId = $(this).closest('tr').attr('id');
                    $careerId = $('#current_career').val();
                    $(this).closest('tr').find('[type=checkbox]').prop('checked', false);

                    $.ajax({
                        type: "POST",
                        url: 'Handler.php',
                        data: {
                            'method': 'removeOutlineFromAll',
                            'outline_id': $outlineId,
                            'career_id': $careerId
                        },
                        dataType: 'json',
                        success: function (data) {
                            $("#career_duration").text(data.duration);                             
                        }
                    });


                }
            });



        }// end of success
    });
}
