<?php
    $authorisedPartner = array('42');

    if (isset($_GET['userId']) && $_GET['userId'] != '' && 
        isset($_GET['partner']) && in_array($_GET['partner'], $authorisedPartner))
    {
        $ids = null;
        if (isset($_COOKIE['Ids']))
            $ids = @unserialize($_COOKIE['Ids']);

        if (!is_array($ids))
            $ids = array();

        $ids[$_GET['partner']] = $_GET['userId'];

        setcookie("Ids", serialize($ids), time()+(3600*24*30)); // 30 days
    }

    header('Content-Type: image/png');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
?>