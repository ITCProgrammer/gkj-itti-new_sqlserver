<?php
date_default_timezone_set('Asia/Jakarta');

$hostname="10.0.0.21";
$database = "NOWPRD";
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port="25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn1 = db2_connect($conn_string,'', '');

if($conn1) {
}
else{
    exit("DB2 Connection failed");
    }

$hostSVR221 = "10.0.0.221";
$usernameSVR221 = "sa";
$passwordSVR221 = "Ind@taichen2024";
$qc = "db_qc";

$db_qc = array("Database" => $qc, "UID" => $usernameSVR221, "PWD" => $passwordSVR221);
$con= sqlsrv_connect($hostSVR221, $db_qc);
if ($con) {
} else {
    exit("SQLSVR19 db_qc Connection failed");
}
