<?php

$con = mysqli_connect('localhost','root','admin','datapozos');

$error = mysqli_connect_errno();
if ($error) echo 'Fallo al conectarse a MySQL '.$error;