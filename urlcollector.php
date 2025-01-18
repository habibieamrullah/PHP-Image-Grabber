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
	
	if($_POST['wclass'] != ""){
	    $link = $url->find(".content_click");
    	foreach($link as $lnk)
    	{
    	    $result = $lnk->href;
        	echo $result . ",";
    	}
	}else{
	    $link = $url->find("a");
    	foreach($link as $lnk)
    	{
    	    $result = $lnk->href;
        	echo $result . ",";
    	}
	}
	
	
	$url->clear(); 
    unset($url);
}