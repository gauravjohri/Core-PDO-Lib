<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php 
include("functions.php");
//$lib->delete("news",4);
/*$data=array("title"=>"prishi","slug"=>"kapoor");
$lib->insert($data,"news");
$data=array("title"=>"prishi","slug"=>"kapoor");
$lib->update($data,"news","2");
$row=$lib->getData("news");
echo "<pre>";print_r($row);
$row=$lib->getDataByID("news",2);
echo "<pre>";print_r($row);*/
if(isset($_POST['submit']))
{
//$lib->multiUploadFile("uploads/",'files');
}
$data=array(
"fisrtname"=>"text",
"fisrtname1"=>"number",
"fisrtname2"=>"text",
"fisrtname3"=>"text",
);
$lib->drawForm("container-fluid",$data);
?>
