 <?php
 //session_start();
 //$_SESSION['id']="1";
 //$id=$_SESSION['id'];
 $name = date('YmdHis');
 $newname="robots/".$name.".jpg";
 //$file = file_put_contents( $newname, file_get_contents('php://input') );


$data = file_get_contents("php://input");

//Debug-answer
//echo 'Before img '.strlen($data).' <img src="'.$data.'"/> After img';

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

$file = file_put_contents($newname, $data);

if (!$file) {
	print "Error occured here";
	exit();
} else {
	//$sql="insert into image (id,name,images) values ('','$id'.'$newname')";
	//print sql;
	//$result=mysqli_query($con,$sql);
	//$value=mysqli_insert_id($con);
	//$value = 1;
	//$_SESSION["myvalue"]=$value;
	echo $newname;
}

//$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $newname;
//print "$url\n";
?>
