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
	    if(!str_contains($result, "http") && !str_contains($result, "www")){ //i dont want external urls that usually has http or https or www on them
    	    $result = $lnk->href;
    	    echo $result . ",";
	    }
	}
	
	$url->clear(); 
    unset($url);
}