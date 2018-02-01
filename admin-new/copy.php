<?php
require_once('../api/protected/config/constant.php');
    backup_tables('localhost','devmobil_mwuser','XUS9Qf9bwJ%&','devmobil_mwmain');//host-name,user-name,password,DB name

function backup_tables($host,$user,$pass,$name,$tables = '*')
{
$return = "";
$link = mysql_pconnect("localhost", "devmobil_mwuser", "XUS9Qf9bwJ%&") or die(mysql_error());
    mysql_select_db("devmobil_mwmain", $link) or die(mysql_error());
//get all of the tables
if($tables == '*')
{
$tables = array();
$result = mysql_query('SHOW TABLES');
while($row = mysql_fetch_row($result))
{
$tables[] = $row[0];
}
}
else
{
$tables = is_array($tables) ? $tables : explode(',',$tables);
}
//cycle through
foreach($tables as $table)
{
$result = mysql_query('SELECT * FROM '.$table);
$num_fields = mysql_num_fields($result);
$return.= 'DROP TABLE '.$table.';';
$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
$return.= "\n\n".$row2[1].";\n\n";
for ($i = 0; $i < $num_fields; $i++)
{
while($row = mysql_fetch_row($result))
{
$return.= 'INSERT INTO '.$table.' VALUES(';
for($j=0; $j<$num_fields; $j++)
{
$row[$j] = addslashes($row[$j]);
$row[$j] = ereg_replace("\n","\\n",$row[$j]);
if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
if ($j<($num_fields-1)) { $return.= ','; }
}
$return.= ");\n";
}
}
$return.="\n\n\n";
}
//save file
$handle = fopen(ROOT_WEBFOLDER.'/public_html/admin-new/backup_db/db-backup-'.date('Y-m-d h:i:s').'.sql','w+');
 //echo $return;
fwrite($handle,$return);
fclose($handle);
$date = date('Y-m-d h:i:s');
$name = 'db-backup-'.date('Y-m-d h:i:s').'.sql';
$query = mysql_query("INSERT INTO db_backup (date, filename) VALUES ('$date', '$name')");
$json = array(
                'result'=> 'true',
                'response'=> 'backup'
            );

         echo json_encode($json);die();
}
?>