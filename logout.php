<?php
///logout.php

include("session_auth.php");

$truc2=$_GET[variable];
logout();
	
	//go to the login page
if ($truc2=="instru")
	Header("Location: login.php?variable=instru");


if ($truc2=="projet")
	Header("Location: login.php?variable=projet");
//if ($truc2=="pret")
	//Header("Location: login.php?variable=pret");

?>