<?php
class lib
{
	function __construct()
	{
		$this->db=new PDO("mysql:host=localhost;dbname=test","root","");
		$this->base_url="http://localhost/chat/";
		$this->current_url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";;
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
	
	function query($sql)
	{
		$sql=$sql;
		$row=$this->db->prepare($sql);
		$rw=$row->execute();
		return $row;
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
	
	function editfunction($table,$id)
	{
		if(isset($_POST['edt_'.$table]))
		{
			//print_r($_POST[$table]);
			$this->update($_POST[$table],$table,$id);
		}
		echo "<table><form method='post'>";
		$row=$this->getDataByID($table,$id);
		unset($row['id']);
		foreach($row as $key=>$val)
		{
			$r=$table."[".$key."]";
			echo "<tr><td style='text-transform:capitalize;'><label>".str_replace('_','',$key)."</label></td>";
			echo "<td><input type='text' name='".$r."' value='".$val."' /></td></tr>";
		}
		echo "<tr><td></td><td><input type='submit' name='edt_".$table."' value='Update' /></td></tr>";;
		echo "</form></table>";
		
	}
	
	function showfunction($table)
	{
		echo "<table>";
		$q = $this->query("DESCRIBE $table");
		$col = $q->fetchAll(PDO::FETCH_COLUMN);
		
		foreach($col as $val)
		{
			echo "<th>".$val."</th>";
		}
		echo "<th>Edit</th>";
		echo "<th>Delete</th>";
		echo "</tr>";
		$rows=$this->getData($table);
		//echo "<pre>";print_r($rows);
		foreach($rows as $row)
		{
			echo "<tr>";
			foreach($col as $cols)
			{
				//echo $cols."<br />";
				echo "<td>".$row[$cols]."</td>";
			}
				echo "<td><a href='#'>Edit</a></td>";
				echo "<td><a href='#'>Delete</a></td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}
	
	function addfunction($table)
	{
		if(isset($_POST['add_'.$table]))
		{
			//print_r($_POST[$table]);
			$this->insert($_POST[$table],$table);
		}
		echo "<table><form method='post'>";
		$q = $this->query("DESCRIBE $table");
		$row = $q->fetchAll(PDO::FETCH_COLUMN);
		//print_r($row);
		unset($row[0]);
		foreach($row as $key)
		{
			$r=$table."[".$key."]";
			echo "<tr><td style='text-transform:capitalize;'><label>".str_replace('_','',$key)."</label></td>";
			echo "<td><input type='text' name='".$r."' /></td></tr>";
		}
		echo "<tr><td></td><td><input type='submit' name='add_".$table."' value='Add' /></td></tr>";;
		echo "</form></table>";
		
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