<?php
session_start();
session_regenerate_id(true);

require 'classes/database.php';
require 'classes/user.php';
require 'classes/friend.php';
require 'classes/email.php';
require 'classes/groups_all.php';
require 'classes/expenses.php';

$db_obj = new Database();
$db_connection = $db_obj->dbConnection();

$user_obj = new User($db_connection);

$friend_obj = new Friend($db_connection);

$email_obj = new Email($db_connection);

$group_obj = new Group($db_connection);

$expenses_obj = new Expenses($db_connection);