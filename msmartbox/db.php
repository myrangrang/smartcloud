<?php
try{
define("DB_HOST", "localhost");
define("DB_USER", "smartbox");
define("DB_PASS", "@smartBox321");
define("DB_NAME", "dbsmartbox");
$dbp = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::ATTR_PERSISTENT => true));
}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}