<?php
include_once 'Handler.php';
$main = new Main(array('career_id' => $_GET['id']), $config);
$career = $main->getCareer();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>OutLines</title>
        <link href="../assets/style/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div>
            <div><a style="color:blue" href="../application/" >Back</a></div>
            
            <div>Current Career: <span id="career_name"><strong><?php echo $career[0]['name']?></strong></span>, total duration <span id="career_duration"><strong><?php echo $career[0]['total_duration_formatted']?></strong></span></div>
            <input id="current_career" type="hidden" />
            
            <select id="course_category" name="course_category">
                <option value="" >--Select--</option>
            </select>

            <select  id="course_name" name="course_name">
                <option value="" >--Select--</option>
            </select>

            <select  id="skill_name" name="skill_name">
                <option value="" >--Select--</option>
            </select>
        </div>

        <div >
            <div id="outlines_container" style="width: 100%;
                 margin-top: 20px;">
<!--                <table id="course_outlines" border="3px">

                </table>-->
            </div>
        </div>


        <script src="../assets/js/jquery.js" type="text/javascript"></script>
        <script src="../assets/js/outlines.js" type="text/javascript"></script>
    </body>
</html>
