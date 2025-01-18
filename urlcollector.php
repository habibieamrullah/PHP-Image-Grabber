<?php

include('simple_html_dom.php');
if(isset($_POST['submit'])){ 
    $opts = array(
      'http'=>array(
        'header'=>"User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53\r\n"
      )
    );
    $context = stream_context_create($opts);
    $u = $_POST['url'];
	$url = file_get_html($u, false, $context);
	$link = $url->find("a");
	foreach($link as $lnk)
	{
	    $result = $lnk->href;
	    echo $result . ",";
	    /*
	    if(str_contains($result, $u) || substr($result,0,1) == "/"){
	        if($result != "/"){
	            if(substr($result,0,1) == "/"){
    	            echo "<div>" . $u . $result . "</div>";
    	        }else{
    	            echo "<div>" . $result . "</div>";
    	        }
	        } 
	    }
		*/
	}
}