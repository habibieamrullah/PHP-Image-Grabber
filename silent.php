<?php
$replimit = 100;
$inc = 0;
$links;
function doit(){
	global $replimit;
	global $inc;
	global $links;
	echo "Doing it..";
	if($inc < count($links)){
		
		echo "inc = " . $inc . " replimit = " . $replimit;
		phpWriteFile("grabber.txt", "Job#: " . $inc . " of " . count($links));
		
		
		if (!file_exists('downloads')) {
			mkdir('downloads', 0777, true);
		}
		include('simple_html_dom.php'); //using PHP Simple HTML DOM Parser to get element in your link
		if(isset($_POST['submit'])){	//Because I want to make it visible to get multiple links, so I have designed a simple form to input your link. If you click on button, it will do job inside brackets, else do nothing. To make sure it work perfectly, you should check either $_POST['Url']. 
			$opts = array(
			  'http'=>array(
				'header'=>"User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53\r\n"
			  )
			);
			$context = stream_context_create($opts);
			
			$url=file_get_html($links[$inc], false, $context); //Here I get parameter at textfield to get URL
			$image = $url->find("img"); // Find all <img> tag in your link 
			foreach($image as $img) //Reach to every single line <img> in the destination link
			{
				$s=$img->src; //Get link of image
				$img_name = 'downloads/'.uniqid()."-".basename($s); //The important step in here. If you want to get file name of image and parse it to fuction save it to your disk (or host, of course!), you have to get file name of it, not a link.
				file_put_contents($img_name, file_get_contents($s)); //Catch image and store it into place that specified before.
			}
			
			$url->clear(); 
			unset($url);
		}
		
		
		
		
		$inc++;
		sleep(3);
		doit();
	}
}

//A function to write a text file. $wtw is "What to Write" :D.
function phpWriteFile($filename, $wtw){
    $myfile = fopen($filename, 'w') or die("Unable to open file!");
	echo "Trying to write " . $wtw . " to " . $filename;
    fwrite($myfile, $wtw);
    fclose($myfile);
    //echo "<br>" . $wtw . " has been written.";
}
?>

<!DOCTYPE html>
<html>

	<head>
		<title>sGrabber</title>
	</head>
	<body>
		<?php

		if(isset($_POST["submit"])){
			
			global $links;
			$links = explode(",", $_POST["links"]);
			
			doit();
			echo "Started!";
			
		}else{
			?>
			<form method="post">
				<textarea name="links" placeholder="Links separated by comma"></textarea>
				<input name="submit" type="submit" value="Start">
			</form>
			<?php
		}

		?>
	</body>
	

</html>

