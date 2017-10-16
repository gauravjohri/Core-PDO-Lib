<?php ob_start();
class lib
{
	function __construct()
	{
		$this->db=new PDO("mysql:host=localhost;dbname=test","root","");
		$this->base_url="http://localhost/csv/";
		$this->current_url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";;
	}
	function insert($data,$table)
	{
		foreach($data as $key=>$val)
		{
			@$cols .=$key."='".str_replace("'","",$val)."',";
		}
		$col=rtrim($cols,",");
		$sql="insert into $table set ".$col;
		$row=$this->db->prepare($sql);
		$row->execute();
		//echo "<pre>";print_r($row->errorInfo());
		
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
	
	function updatewhere($data,$table,$arr)
	{
		foreach($data as $key=>$val)
		{
			@$cols .=$key."='".$val."',";
		}
		$col=rtrim($cols,",");
		$coll=array_keys($arr);
		//print_r($col);
		$sql="update $table set ".$col." where ".$coll[0]."='".$arr[$coll[0]]."'";
		$row=$this->db->prepare($sql);
		$row->execute();
		//echo "<pre>";print_r($row->errorInfo());
		
	}
	
	function delete($table,$id)
	{
	
		$sql="delete  from $table  where id='".$id."'";
		$row=$this->db->prepare($sql);
		$row->execute();
		
	}
	
	function getData($table)
	{
		$sql="select * from $table order by id desc";
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
	
	function editfunction($table,$id,$url)
	{
		if(isset($_POST['edt_'.$table]))
		{
			//print_r($_POST[$table]);
			$this->update($_POST[$table],$table,$id);
			header("location:".$url);
		}
		echo "<div class='p-row'>
		<br>
		<br>
		<div class='container'>
		<div class='row'>
		<div class='col-md-5 col-md-offset-3'>
		<table class='table table-bordered'><form method='post'>";
		$row=$this->getDataByID($table,$id);
		unset($row['id']);
		foreach($row as $key=>$val)
		{
			$r=$table."[".$key."]";
			echo "<tr><td style='text-transform:capitalize;'><label>".str_replace('_','',$key)."</label></td>";
			echo "<td><input type='text' name='".$r."' value='".$val."' /></td></tr>";
		}
		echo "<tr><td></td><td><input type='submit' class='btn btn-primary' name='edt_".$table."' value='Update' /></td></tr>";;
		echo "</form></table></div></div></div></div>";
		
		
	}
	
	
	function paginate($reload, $page, $tpages) {
    $adjacents = 2;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $out = "";
    // previous
    if ($page == 1) {
        $out.= "<span>" . $prevlabel . "</span>\n";
    } elseif ($page == 2) {
        $out.= "<li><a  href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
    } else {
        $out.= "<li><a  href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
    }

    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "<li  class=\"active\"><a href=''>" . $i . "</a></li>\n";
        } elseif ($i == 1) {
            $out.= "<li><a  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
        } else {
            $out.= "<li><a  href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a>\n</li>";
        }
    }

    if ($page < ($tpages - $adjacents)) {
        $out.= "<li><a style='font-size:11px' href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $tpages . "</a></li>\n";
    }
    // next
    if ($page < $tpages) {
        $out.= "<li><a  href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
    } else {
        $out.= "<span style='font-size:11px'>" . $nextlabel . "</span>\n";
    }
    $out.= "";
    return $out;
}
	
	function showfunction($table,$edit,$del)
	{
		echo "<table class='table table-bordered table-responsive'>";
		$q = $this->query("DESCRIBE $table");
		$col = $q->fetchAll(PDO::FETCH_COLUMN);
		
		foreach($col as $val)
		{
			if($val=="id")
			{
				echo "<th>".str_replace("_"," ","Sr No.")."</th>";
			}
			else 
			{
				echo "<th>".str_replace("_"," ",$val)."</th>";
				@$cll[]=$val;
			}
		}
		echo "<th>Edit</th>";
		echo "<th>Delete</th>";
		echo "</tr>";
		$sql="SELECT  * FROM $table ";
		//print_r($cll);
		if(@$_GET['keyword']!=""){
		$sql .=" where ";
		foreach($cll as $cl)
		{
			$sqlc .=$cl." like '%".$_GET['keyword']."%' or ";
		}
			$sql .=rtrim($sqlc," or ");
			
		}
		//echo $sql;
		$page=isset($_GET['page'])?$_GET['page']:1;
		$limit=50;
		if($page)
		{
			$start = ($page - 1) * $limit; 	
		}
		else
		{
			$start=0;
		}
		
		
		
		
		$row=$this->query($sql);
		$total_rows=$row->rowCount();
		
		$total_pages=ceil($total_rows/$limit);
		
		$sql .="order by id asc limit $start,$limit";
		
		$row=$this->query($sql);
		$rows=$row->fetchAll(PDO::FETCH_ASSOC);
		
		$i=1;
		foreach($rows as $row)
		{
			
			echo "<tr>";
			foreach($col as $cols)
			{
				
				if($cols=="id")
				{
					echo "<td>".$i."</td>";
				}
				else{
					echo "<td>".$row[$cols]."</td>";
				}
				
				
			}
				echo "<td><a href='$edit&id=".$row['id']."'><i class='fa fa-pencil'></i></a></td>";
				echo "<td><a href='$del&id=".$row['id']."'><i class='fa fa-trash-o'></i></a></td>";
			echo "</tr>";
			$i++;
		}
		
		echo "</table>";
		if ($total_pages > 1) {
		 echo '<div class="col-md-12 text-center"><ul class="pagination pagination-large">';
		echo $this->paginate($this->base_url."?", $_GET['page'], $total_pages);
		 echo "</ul></div>";
		}
		
	}
	
	function addfunction($table,$url)
	{
		if(isset($_POST['add_'.$table]))
		{
			//print_r($_POST[$table]);
			$this->insert($_POST[$table],$table);
			header("location:".$url);
		}
		echo "<div class='p-row'>
		<br>
		<br>
		<div class='container'>
		<div class='row'>
		<div class='col-md-5 col-md-offset-3'>
		<table class='table table-bordered'><form method='post'>";
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
		echo "<tr><td></td><td><input type='submit' class='btn btn-primary' name='add_".$table."' value='Add' /></td></tr>";;
		echo "</form></table></div></div></div></div>";
		
		
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
	
	function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
	$arw=array("domain_name","host","uname","password","userid","start_date","end_date");
	fputcsv($f, $arw, $delimiter); 
    foreach ($array as $line) { 
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter); 
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
}

}
$lib=new lib;

 ?>