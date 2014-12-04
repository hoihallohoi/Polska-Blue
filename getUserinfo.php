<?php

function getUserinfo($select) {
    $link = mysqli_connect("localhost", "root", "usbw", "klanten", "3307");
    $query = mysqli_prepare($link, "SELECT $select FROM klantgegevens WHERE id = ?");
    mysqli_stmt_bind_param($query, "i", $_SESSION["id"]);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $result);
    mysqli_stmt_fetch($query);
    mysqli_stmt_close($query);
    return $result;
}

?>
