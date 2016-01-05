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
            'method':'listCourses'
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
                'method':'getCategoryByCourse',
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
        // getting outlines for $course_id
        $.ajax({
            type: "POST",
            url: 'Handler.php',
            data: {
                'method':'getOutlinesByCourse',
                'course_id': $courseId
            },
            dataType: 'json',
            success: function (data) {
//              //removing table and its content in case make consecutive ajaxs
                $('#course_outlines_tbody').empty();
                $('#course_outlines').remove();
                // creating table head
//                $('#outlines_container').append('<table id="course_outlines"></table>');
                $('#outlines_container').append('<table border="3px" id="course_outlines"><th>name<th>duration/min</th><th>Admin</th>\n\
                <tbody id="course_outlines_tbody"></tbody>');
                // creating table rows
                $(data).each(function () {
                    $('#course_outlines_tbody').append('<tr id =' + this.id + '><td>' + this.name + '</td><td>' + this.duration + '</td>\n\
                    <td><input type="checkbox" class="apply">Apply &nbsp;&nbsp; <input type="checkbox" class="applyToAll">Apply to all </td></tr>');
                });

                $('#outlines_container').append('</table');
            }
        });


    });

    /*
     * Now Handling case if user choosed by Category
     */

    $('#course_category').click(function () {
        
    });



});

