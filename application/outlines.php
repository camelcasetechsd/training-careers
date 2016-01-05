<!DOCTYPE html>
<html>
    <head>
        <title>OutLines</title>
    </head>
    <body>
        <div>
            
            <input id="current_career" type="text" />
            
            <select id="course_category" name="course_category">
                <option value="" >__Select__</option>
            </select>

            <select  id="course_name" name="course_name">
                <option value="" >__Select__</option>
                <!--<option value="A">A</option>-->
            </select>
        </div>

        <div style="width: 100%;">
            <div  style=" float:left; width: 70%">
                <table id="career_outlines">
                    <tr>
                        
                    </tr>
                </table>
            </div>
            <div id="outlines_container"style=" float:right;">
<!--                <table id="course_outlines" border="3px">

                </table>-->
            </div>
        </div>


        <script src="../assets/js/jquery.js" type="text/javascript"></script>
        <script src="../assets/js/outlines.js" type="text/javascript"></script>
    </body>
</html>