<?php
include_once 'Handler.php';
$main = new Main(array('outline_id' => $_GET['outline_id']), $config);
$outline = $main->getOutlineById();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Outline</title>
        <link href="../assets/style/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div>
            <div><a style="color:blue" href="../application/outlines.php?id=<?php echo $_GET['career_id']?>" >Back</a></div>
            <table class="outlinestable">
			<tr><td><?php echo $outline[0]['name'];?></td></tr>
            <tr><td>Name: <input id="outline_name" name="outline_name" type="text" value="<?php echo $outline[0]['name'];?>"></td></tr>
			<tr><td>Default Name:<span id="outline_default_name"> <strong><?php echo $outline[0]['default_name']?></strong></span></td></tr>
            <tr><td>Duration: <input  id="outline_duration" name="outline_duration" type="text" value="<?php echo $outline[0]['duration'];?>"></td></tr>
			<tr><td>Default Duration:<span id="outline_default_duration"> <strong><?php echo $outline[0]['default_duration']?></strong></span></td></tr>
            <input id="current_outline" type="hidden" value="<?php echo $outline[0]['id']?>"/>
			<tr><td><input type="button" id="save_outline" name="save_outline" value="Save"></td></tr>
			</table>
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