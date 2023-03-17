<?php 
$my_conn = new mysqli("localhost", "root", "", "city_list");

$res = $my_conn->query("select username from users where id = 1");

$row = $res->fetch_assoc();

echo $row['username'];