<?php
    if (!isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION["compt"])){
    $compt = $_SESSION["compt"];
}
else{
    $compt = 0;
}
$compt = $compt + 1;

$file = $_FILES["file"];
$target = "audio/test".$compt.".webm";
move_uploaded_file( $file[tmp_name], $target);
$_SESSION["compt"] = $compt;


?>