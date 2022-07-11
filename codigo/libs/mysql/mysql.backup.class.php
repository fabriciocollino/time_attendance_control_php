<?php

	function excelEncode(&$value, $key){$value = iconv('UTF-8', 'Windows-1251', $value);}
	
	class mySQLBackup{
		public $driver="";
		public $host="";
		public $username="";
		public $password="";
		public $database="";
		public $db=false; 
		
		
		// connecting
		public static function init ($params){
			$back = new mySQLBackup();
			$back->driver 	= $params["driver"];
			$back->host 	= $params['host'];
			$back->username	= $params['username'];
			$back->password	= $params['password'];
			$back->database	= $params['database'];
			$back->temp = isset($params["temp"])?$params["temp"]:dirname($_SERVER['REQUEST_URI'])."/";
			$back->errors = array();
			switch($back->driver){
				case 'mysql':
					if ($back->db =  mysql_connect($back->host, $back->username, $back->password)){
						if (!mysql_select_db($back->database, $back->db)){
							$back->errors[] = "Can't select database";
						}
					}else{
						$back->errors[] = "Connection error";
					}
					break;
				case 'mysqli':
					if ($back->db =  mysqli_connect($back->host, $back->username, $back->password, $back->database)){
					}else{
						$back->errors[] = "Connection error";
					}
					break;
				case 'pdo':					
					try {
						$back->db = new PDO('mysql:host='.$back->host.';dbname='.$back->database, $back->username, $back->password);
					}catch(PDOException $e){
						$back->errors[] = "Pdo connection error: ". $e->getMessage();
					}
					break;
			}
			return $back;
		}
		
		// form sql file
		public function as_sql(){
			if (count ($this->errors)>0){ return $this;}
			try{
				$this->filename = $this->database.".sql";
				$f = fopen($this->temp.$this->filename, "w+");
				if (!$f){
					$this->errors[] = "As Sql function error: Can't open file ".$this->temp.$this->filename; return $this;
				}
				$tables = $this->getTables();
				$tables_query = $this->formSqlTables($tables);
				fwrite($f, $tables_query);
				foreach ($tables as $name=>$rows){
					$count_query = "SELECT COUNT(*) as mc FROM `".$name."`";
					$count = $this->execQuery($count_query);
					$steps = ceil($count[0]["mc"]/100);
					for ($i=0; $i<$steps; $i++)
					{
						$query_values = "SELECT * FROM `".$name."` LIMIT ".($i*100).", 100";
						$values = $this->execQuery($query_values);
						$query = "";
						if (count($values>0)){
							$query = "INSERT INTO ".$name." VALUES ";
							$val_query ="";	
							for ($n=0; $n<count($values); $n++){
								// Одна строка
								$val_query.= "(";
								foreach ($values[$n] as $key=>$value){
									$val_query .= '"'.addslashes($value).'", ';
								}
								$val_query = substr($val_query, 0, strlen($val_query)-2); 
								$val_query .= "),".chr(10);
							}
							$val_query = substr($val_query, 0, strlen($val_query)-2); 
							$query.= $val_query.";".chr(10);
							fwrite($f, $query);
						}
					}
				}
				fclose($f);
			}catch(Exception $e){
				$this->errors = "Sql function error: ".$e->getMessage();
			}	
			return $this;
			
		}
		
		private function getTables(){
			$result = array();
			$tables = $this->execQuery("SHOW TABLE STATUS");
			foreach ($tables as $table)	{
				$name = $table["Name"];
				$result[$name]["table"] = $this->execQuery("DESCRIBE `".$name."`");
				$result[$name]["create"] = $this->execQuery("SHOW CREATE TABLE `".$name."`");
				$result[$name]["Engine"] = $table["Engine"];
				$result[$name]["Auto_increment"] = $table["Auto_increment"];
				$result[$name]["Collation"] = $table["Collation"];
			}
			return $result;
		}
		
		private function formSqlTables($tables)	{
			$query = "";
			$query_post = "";
			foreach ($tables as $name=>$rows){
				$query.=$rows["create"][0]["Create Table"].";".chr(10);
			}
			return $query;
		}
		
	
		public function as_csv($delimeter=";"){
			if (count ($this->errors)>0){ return $this;}
			try{
				$tables = $this->getTables();
				$numb = 0;
				foreach ($tables as $name=>$rows){
					$this->filename[$numb] = $name.".csv";
					$f = fopen($this->temp.$this->filename[$numb], "w+");
					
					$header = array();
					foreach($rows['table'] as $row)
					{
						$header[] = $row["Field"];
					}
					array_walk($header, 'excelEncode');
					fputcsv($f, $header,$delimeter);
					$count_query = "SELECT COUNT(*) as mc FROM `".$name."`";
					$count = $this->execQuery($count_query);
					$steps = ceil($count[0]["mc"]/100);
					for ($i=0; $i<$steps; $i++)
					{
						$query_values = "SELECT * FROM `".$name."` LIMIT ".($i*100).", 100";
						$values = $this->execQuery($query_values);
						foreach ($values as $value)
						{
							array_walk($value, 'excelEncode');
							fputcsv($f, $value,$delimeter);
						}
					}
					fclose($f);
					$numb++;
				}
			}catch(Exception $e){
				$this->errors[] = "Csv function error".$e->getMessage();
			}
			return $this;
		}
		private function writeLevel($f, $level, $str)
		{
			$string = str_repeat(chr(9),$level).$str.chr(10);
			fwrite($f, $string);
		}
		
		public function as_xml(	$params = array(
									"root"=>"mysql", 
									"db_tag"=>"database", 
									"db_prop"=>"name", 
									"table_tag"=>"table", 
									"table_prop"=>"name", 
									"row_tag"=>"row", 
									"field_tag"=>"field",
									"field_prop"=>"name")
								){
			if (count ($this->errors)>0){ return $this;}
			try{
				$this->filename = $this->database.".xml";
				$f = fopen($this->temp.$this->filename, "w+");
				$level=0;
				$this->writeLevel($f, $level,'<?xml version="1.0"?>');
				$level++;
				// Root
				if (isset($params["root"])){
					$this->writeLevel($f, $level,"<".$params["root"].">");
					$level++;
				}
				// Database
				if (isset($params["db_prop"])){
					$this->writeLevel($f, $level,'<'.$params["db_tag"].' '.$params["db_prop"].'="'.$this->database.'">');
					$level++;
				}
				elseif(isset($params["db_tag"])){
					if ($params["db_tag"]==""){
						$this->writeLevel($f, $level,'<'.$this->database.'>');
					}else{
						$this->writeLevel($f, $level,'<'.$params["db_tag"].'>');
					}
					$level++;
				}
				
				$tables = $this->getTables();
				foreach ($tables as $name=>$rows){
					// Table open
					if (isset($params["table_prop"])){
						$this->writeLevel($f, $level,'<'.$params["table_tag"].' '.$params["table_prop"].'="'.$name.'">');
						$level++;
					}elseif(isset($params["table_tag"])){
						if ($params["table_tag"]==""){
							$this->writeLevel($f, $level,'<'.$name.'>');
						}else{
							$this->writeLevel($f, $level,'<'.$params["table_tag"].'>');
						}
						$level++;
					}	
					$count_query = "SELECT COUNT(*) as mc FROM `".$name."`";
					$count = $this->execQuery($count_query);
					$steps = ceil($count[0]["mc"]/100);
					// hundreds of rows
					for ($i=0; $i<$steps; $i++)
					{
						$query_values = "SELECT * FROM `".$name."` LIMIT ".($i*100).", 100";
						$values = $this->execQuery($query_values);
						$query_100 = "";
						// steps by rows
						for ($n=0; $n<count($values); $n++){
							$str ="";
							if (isset($params["row_tag"])){
								$str.=str_repeat(chr(9),$level).'<'.$params["row_tag"].'>'.chr(10);
								$level++;
							}
							foreach ($values[$n] as $key=>$value){
								// Steps by fields
								if (isset($params["field_prop"])){
									$str.=str_repeat(chr(9),$level).'<'.$params["field_tag"].' '.$params["field_prop"].'="'.$key.'">'.$value.'</'.$params["field_tag"].'>'.chr(10);
								}
								elseif (isset($params["field_tag"])){
									if ($params["field_tag"]==""){
										$str.=str_repeat(chr(9),$level).'<'.$key.'>'.$value.'</'.$key.'>'.chr(10);
									}else{
										$str.=str_repeat(chr(9),$level).'<'.$params["field_tag"].'>'.$value.'</'.$params["field_tag"].'>'.chr(10);
									}
								}
							}
							// rows close
							if (isset($params["row_tag"])){
								$level--;
								$str.=str_repeat(chr(9),$level).'</'.$params["row_tag"].'>'.chr(10);
							}
							fwrite($f, $str);
						}
					}
					
					// Table close
					if(isset($params["table_tag"]))	{
						$level--;
						if ($params["table_tag"]==""){
							$this->writeLevel($f, $level,'</'.$name.'>');
						}else{
							$this->writeLevel($f, $level,'</'.$params["table_tag"].'>');
						}
					}
				}
				// Database close
				if(isset($params["db_tag"])){
					$level--;
					if ($params["db_tag"]==""){
						$this->writeLevel($f, $level,'</'.$this->database.'>');
					}else{
						$this->writeLevel($f, $level,'</'.$params["db_tag"].'>');
					}
				}
				// Root close
				if (isset($params["root"])){
					$level--;
					$this->writeLevel($f, $level,"</".$params["root"].">");
				}
				fclose($f);
			}catch(Exception $e){
				$this->errors[] = "Xml function error: ".$e->getMessage();
			}
			return $this;
		}
		
		public function mail($email, $emailFrom=null){
			if (count ($this->errors)>0){ return $this;}
			$this->sendFile($email,$this->filename, $emailFrom);
			return $this;
		}
		
		private function sendFile($email, $files, $emailFrom=null){
			try{
				
				$from = "Base backuper <noreply@mailer.com>"; 
				if ($emailFrom!=null){
					$from = "Base backuper <".$emailFrom.">"; 
				}
				$subject = "Backup by ".date("d.M H:i"); 
				$message = "Backup by ".date("Y.m.d H:i:s")."\n ".count($files)." attachments";
				$headers = "From: $from";
				$semi_rand = md5(time()); 
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
				$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
				$message = "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
				"Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
				for($i=0;$i<count($files);$i++){
					if(is_file($files[$i])){
						$message .= "--{$mime_boundary}\n";
						$fp =    @fopen($this->temp.$files[$i],"rb");
					$data =    @fread($fp,filesize($this->temp.$files[$i]));
								@fclose($fp);
						$data = chunk_split(base64_encode($data));
						$message .= "Content-Type: application/octet-stream; name=\"".basename($files[$i])."\"\n" . 
						"Content-Description: ".basename($files[$i])."\n" .
						"Content-Disposition: attachment;\n" . " filename=\"".basename($files[$i])."\"; size=".filesize($files[$i]).";\n" . 
						"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
						}
				}
				$message .= "--{$mime_boundary}--";
				$returnpath = "-f noreply@mailer.com";
				if ($emailFrom!=null){
					$returnpath = "-f ".$emailFrom;
				}
				$ok = @mail($email, $subject, $message, $headers, $returnpath);
			}catch (Exception $e){
				$this->errors[] = "Email function error: ".$e->getMessage();
			}
			if($ok){ 
				return true; 
			}else { 
				$this->errors[] = "Email sending error";
				return false; 
			}
		}
		
		public function http($params){
			if (count ($this->errors)>0){ return $this;}
			if (is_array($this->filename)){
				foreach($this->filename as $filename){
					if (!$this->sendHttp($params, $filename)){
						return $this;
					}					
				}
			}else{
				$this->sendHttp($params, $this->filename);
			}
			return $this;
		}
		
		private function sendHttp($params, $filename){
			$handle = curl_init ($params["server"]);
			if ($handle)
            {
				// specify custom header
                $customHeader = array(
                    "Content-type: application/text"
                );
				$fh = fopen($this->temp.$filename, 'r');
                $curlOptArr = array(
                    CURLOPT_PUT => TRUE,
                    CURLOPT_HEADER => TRUE,
                    CURLOPT_HTTPHEADER => $customHeader,
                    CURLOPT_INFILESIZE => filesize($this->temp.$filename),
                    CURLOPT_INFILE => $fh,
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD => $params["user"]. ':' .$params["pass"],
                    CURLOPT_RETURNTRANSFER => TRUE
                );
                curl_setopt_array($handle, $curlOptArr);
               if (curl_exec($ch)==false){
					$this->errors[] = curl_error($ch);
					return false;
				}
                curl_close($ch);
				return true;
			}
		}
		
		public function save($path){
			if (count ($this->errors)>0){ return $this;}
			if (is_array($this->filename)){
				if (count($this->filename)>0)
				foreach($this->filename as $filename){
					if (!copy($this->temp.$filename, $path.$filename)){
						$this->errors[] = "Copy failed. Wrong path: ".$path.$filename;
						return $this;
					}
				}
			}else{
				if (!copy($this->filename, $path.$this->filename)){
					$this->errors[] = "Copy failed. Wrong path: ".$path.$this->filename;
				}
			}
			return $this;
		}
		
		public function ftp($params){
			if (count ($this->errors)>0){ return $this;}
			if (is_array($this->filename)){
				foreach($this->filename as $filename){
					if (!$this->sendFtp($params,$filename)){
						return $this;
					}
				}
			}else{
				$this->sendFtp($params,$this->filename);
			}
			return $this;
		}
		
		private function sendFtp($params, $filename){
			if ($connection = ftp_connect($params['server'],$params['port'],20)){
				$login = ftp_login($connection, $params['user'], $params['pass']);
				if (!$connection || !$login) { 
					$this->errors[] = 'FTP Connection attempt failed!'; 
				}else{
					$dest = isset($params["dest"])?$params["dest"]:"";
					if (ftp_pasv($connection, true)== false){
						$this->errors[] = "FTP Password error";
					}
					else{
						$upload = ftp_put($connection, $dest."/".$filename, $this->temp.$filename, FTP_BINARY);
						if (!$upload) { 
							$this->errors[] ="FTP upload failed!"; 
						}
						ftp_close($connection);
					}
				}
			}else{
				//$this->errors[] = "Ftp connection error<br>".$connection."data:".print_r($params).$filename;
					$this->errors[] = "Ftp connection error";
			}
			if (count($this->errors)>0){ 
				return false;
			}
			return true;
		}
		
		public function webdav($params){
			if (count ($this->errors)>0){ return $this;}
			
			if (is_array($this->filename)){
				foreach($this->filename as $filename){
					$this->sendWebdav($params,$filename);
				}
			}else{
				$this->sendWebdav($params,$this->filename);
			}
			return $this;
		}
		
		private function sendWebdav($params, $filename){
			$filesize = filesize($this->temp.$filename);
			$fh = fopen($this->temp.$filename, 'r');
			$ch = curl_init($params["server"].$filename);
			curl_setopt($ch, CURLOPT_USERPWD, $params["user"].":".(string)$params["pass"]);
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_INFILE, $fh);
			curl_setopt($ch, CURLOPT_INFILESIZE, $filesize);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			if (curl_exec($ch)===false){
				$this->errors[] = "Webdav curl Error: ".curl_error($ch);
				fclose($fh);
				return false;
			}
			curl_close($ch);
			return true;
			
		}
		
		public function zip($dateFlag = false){
			if (count ($this->errors)>0){ return $this;}
			try{
				if (extension_loaded("zip"))
				{
					$date = ($dateFlag)?("_".date("Y.m.d_H.i.s")):"_".date("Y.m.d");
					$zip = new ZipArchive();
					$zipname = $this->database.$date.".zip";
					if (file_exists($this->temp.$zipname)){ unlink($this->temp.$zipname);}
					if ($zip->open($this->temp.$zipname, ZipArchive::CREATE)!==TRUE) {
						$this->errors[] = "Can't create file";
					}
					if (is_array($this->filename)){
						foreach($this->filename as $filename){
							$zip->addFile($this->temp.$filename, $filename);
						}
						$zip->close();
						foreach($this->filename as $filename){	unlink($this->temp.$filename);}
						
					}else{
						$zip->addFile($this->temp.$this->filename, $this->filename);
						$zip->close();
						unlink($this->temp.$this->filename);					
					}
					$this->filename = $zipname;
				}else{
					$this->errors[] = "php_zip.dll is not loaded";
				}
			}catch(Exception $e){
				$this->errors[] = "Zipping error";
			}
			return $this;
			
		}
		
		private function execQuery($query){
			switch($this->driver){
			case "mysql":
					$result = array();
					if ($res = mysql_query($query, $this->db)){
						while($row = mysql_fetch_assoc($res)) {
							$result[] = $row;
						}
					}else{
						$this->errors[] = "Mysql query error: ".mysql_error()." QUERY: ".$query;
					}
				return $result;
				break;
			case "mysqli":
				$result = array();
				if ($res = mysqli_query($this->db, $query)){
					while($row = mysqli_fetch_array($res)) {
						$result[] = $row;
					}
				}else{
					$this->errors[] = "Mysqli query error: ".mysql_error()." QUERY: ".$query;
				}
				return $result;
				break;
			case "pdo":
					$stmt=$this->db->prepare($query);
					$stmt->execute();
					while($row = $stmt->fetch()) {
						$result[] = $row;
					}
				return $result;
			}
			return false;
		}
		
		public function remove(){
			if (count ($this->errors)>0){ return $this;}
			try{
				if (is_array($this->filename)){
					foreach($this->filename as $filename){
						unlink ($this->temp.$filename);
					}
				}else{
					unlink ($this->temp.$this->filename);
				}
			}catch(Exception $e){
				$this->errors[] = "Can't delete files ".$e->getMessage();
			}
			return $this;
		}
		
		public function showErrors(){
			if (count($this->errors)>0){
				foreach($this->errors as $error){
					echo $error."<br />\n";
				}
			}
		}
		
	}
?>