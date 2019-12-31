<?php
include_once 'Handler.php';
$main = new Main(array('career_id' => $_GET['id']), $config);
$career = $main->getCareer();
$content = <<<CONTENT

        <div>         
            <div class="page-header">
                <p><button type="button" class="btn btn-link" onclick="window.location.href='../application/'">&laquo; Back to careers</button></p>
                <p>
                    <h3><span class="label label-primary" id="career_name"><strong>{$career[0]['name']}</strong></span>, total duration <span id="career_duration"><strong>{$career[0]['total_duration_formatted']}</strong></span></h3>
                </p>
            </div>
            <input id="current_career" type="hidden" value="{$_GET['id']}" />
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Category</h3></div>
                    <div class="panel-body">
                        <p>
                            <select id="course_category" name="course_category" class="form-control">
                                <option value="" >--Select--</option>
                            </select>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Course</h3></div>
                    <div class="panel-body">
                        <p>
                            <select id="course_name" name="course_name" class="form-control">
                                <option value="" >--Select--</option>
                            </select>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Skill</h3></div>
                    <div class="panel-body">
                        <p>
                            <select id="skill_name" name="skill_name" class="form-control">
                                <option value="" >--Select--</option>
                            </select>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div >
            <div id="outlines_container">
                <div id="indicator" class="text-center"><img src="{$config['basePath']}img/progress.gif" width="100"></div>
            </div>
        </div>

        <script type="text/javascript" src="{$config['basePath']}js/outlines.js"></script>

CONTENT;

    include '../../../lib/Include/layout.php';
