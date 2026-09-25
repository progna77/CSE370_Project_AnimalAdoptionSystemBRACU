<?php
session_start();require 'db.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: index.php');exit;}
$username=trim($_POST['username']??'');$password=$_POST['password']??'';
$stmt=$conn->prepare('SELECT user_id,full_name,username,password,role FROM users WHERE username=? LIMIT 1');$stmt->bind_param('s',$username);$stmt->execute();$user=$stmt->get_result()->fetch_assoc();
if($user && password_verify($password,$user['password'])){session_regenerate_id(true);$_SESSION['user_id']=$user['user_id'];$_SESSION['full_name']=$user['full_name'];$_SESSION['username']=$user['username'];$_SESSION['role']=$user['role'];header('Location: dashboard.php');exit;}
header('Location: index.php?msg='.urlencode('Invalid username or password.'));
