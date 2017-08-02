<?php
class lib
{
	function __construct()
	{
		$this->db=new PDO("mysql:host=localhost;dbname=test","root","");
	}
	function insert($data,$table)
	{
		foreach($data as $key=>$val)
		{
			@$cols .=$key."='".$val."',";
		}
		$col=rtrim($cols,",");
		$sql="insert into $table set ".$col;
		$row=$this->db->prepare($sql);
		$row->execute();
		
	}
	
	function update($data,$table,$id)
	{
		foreach($data as $key=>$val)
		{
			@$cols .=$key."='".$val."',";
		}
		$col=rtrim($cols,",");
		$sql="update $table set ".$col." where id='".$id."'";
		$row=$this->db->prepare($sql);
		$row->execute();
		
	}

	function delete($table,$id)
	{
	
		$sql="delete  from $table  where id='".$id."'";
		$row=$this->db->prepare($sql);
		$row->execute();
		
	}
	
	function getData($table)
	{
		$sql="select * from $table";
		$row=$this->db->prepare($sql);
		$rw=$row->execute();
		$rdata=$row->fetchAll(PDO::FETCH_ASSOC);
		return $rdata;
	}
	
	
	function getDataByID($table,$id)
	{
		$sql="select * from $table where id='".$id."'";
		$row=$this->db->prepare($sql);
		$rw=$row->execute();
		$rdata=$row->fetch(PDO::FETCH_ASSOC);
		return $rdata;
	}


	function uploadFile($target_dir,$name)
	{
		$filename=time()."__".basename($_FILES[$name]['name']);
		$path=$target_dir.$filename;
		if(move_uploaded_file($_FILES[$name]['tmp_name'], $path))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	function multiUploadFile($target_dir,$name)
	{
		
		for($i=0;$i<count($_FILES[$name]['name']);$i++)
		{
		$filename=time()."__".basename($_FILES[$name]['name'][$i]);
		$path=$target_dir.$filename;
		move_uploaded_file($_FILES[$name]['tmp_name'][$i], $path);
		}
		

	}
	
	function drawForm($cls,$data)
	{
		echo "<div class='".$cls."'><form method='post' enctype='multipart/form-data'>";
		foreach($data as $key=>$val)
		{
			echo "<div class='form-group'><lable for=".$key.">".ucwords($key)." : </label><input class='form-control' type='".$val."' name='".$key."' /></div>";
		}
		echo "<div><input type='submit' name='submit' class='form-control' /></div>";
		echo "</form></div>";
	}

}
$lib=new lib;

 ?>