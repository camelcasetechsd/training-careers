<?php
include_once 'Handler.php';
$main = new Main(array('outline_id' => $_GET['outline_id']), $config);
$outline = $main->getOutlineById();

$content = <<<CONTENT

        <div class="page-header">
            <p><button type="button" class="btn btn-link" onclick="window.location.href='../application/outlines.php?id={$_GET['career_id']}'">&laquo; Back to outlines</button></p>
            <p>
                <h3><span class="label label-primary"><strong>{$outline[0]['name']}</strong></span></h3>
            </p>
        </div>
        <div>
            <h3>Data</h3>
            <table class="table">
                <tr>
                    <th>Default Name</th>
                    <td>
                        <span id="outline_default_name"><strong>{$outline[0]['default_name']}</strong></span>
                    </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>
                        <input id="outline_name" name="outline_name" class="form-control" type="text" value="{$outline[0]['name']}">
                    </td>
                </tr>
                <tr>
                    <th>Default Duration</th>
                    <td>
                      <span id="outline_default_duration"><strong>{$outline[0]['default_duration']}</strong></span>
                    </td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>
                        <input id="outline_duration" name="outline_duration" class="form-control" type="text" value="{$outline[0]['duration']}">
                    </td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>
                        <select id="course_category" name="course_category" class="form-control">
                            <option value="" >--Select--</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td>
                        <select id="course_name" name="course_name" class="form-control">
                            <option value="" >--Select--</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Skill</th>
                    <td>
                        <select id="skill_name" name="skill_name" class="form-control">
                            <option value="" >--Select--</option>
                        </select>
                    </td>
                </tr>
            </table>
                    <input type="hidden" id="default_category_id"  name="default_category_id" value="{$outline[0]['category_id']}"/>
            <input type="hidden" id="default_course_id" name="default_course_id" value="{$outline[0]['course_id']}"/>
            <input type="hidden" id="default_skill_id" name="default_skill_id" value="{$outline[0]['skill_id']}"/>
            <input id="current_outline" type="hidden" value="{$outline[0]['id']}"/>
            <input type="button" id="save_outline" name="save_outline" value="Save" class="btn btn-success">
CONTENT;

    if (empty($outline[0]['status'])) {

        $content .= ' <input type="button" id="activate_outline" name="activate_outline" value="Activate" class="btn btn-primary">';
    } else {

        $content .= ' <input type="button" id="deactivate_outline" name="deactivate_outline" value="Deactivate" class="btn btn-danger">';
    }
            
    $content .= <<<CONTENT
        </div>
        <script src="{$config['basePath']}js/outlines.js" type="text/javascript"></script>
CONTENT;

    include '../../../lib/Include/layout.php';
