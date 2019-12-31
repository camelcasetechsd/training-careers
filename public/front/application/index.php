<?php
   include_once 'Handler.php';

    $content = <<<CONTENT
        <div class="page-header">
            <p>
                <h3>Careers</h3>
            </p>
        </div>
        <div id="wrap"></div>
        <script type="text/javascript" src="{$config['basePath']}js/careers.js"></script>
CONTENT;
    include '../../../lib/Include/layout.php';
?>
