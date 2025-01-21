<!DOCTYPE html>
<html>
    <head>
        <title>PHP Image Grabber</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        
        <style>
            body{
                font-size: 10px;
            }
        </style>
        
    </head>
    <body>
        
        <h1><a href="index.php">PHP Image Grabber</a></h1>
        <p>Developed by <a href="https://www.youtube.com/@ThirteeNov/videos">ThirteeNov</a> (subscribe for more!)</p>
        
        <h3>Auto scan sub urls</h3>
        <input id="baseurl" placeholder="Enter base url here"><input id="startingurl" placeholder="Enter starting url here">
        <button onclick="startGrab();">Start</button><button onclick="stopAutoScan()">Stop URL Scan</button>
        <a href="#" onclick="scanLinksOnly()">Click Here To Scan Links Only</a>
		
        <h3>Or, enter manually multiple urls separated by comma</h3>
        <textarea id="urls"></textarea>
        <button onclick="startMultipleUrls()">Start</button>
        
        <p>Use below input to get links with same class from a url:</p>
        <h3>Find links on a url with class</h3>
        <input id="findlinksfromurl" placeholder="URL"><input id="classname" placeholder="Class">
        <button onclick="findLinks()">Find Links</button>
        
        <h3>URL and Image Grab Result Log</h3>
        <div id="result"></div>
        
        
        
        <script>
		
			var sonly = false;
			function scanLinksOnly(){
				sonly = true;
				startGrab();
			}

            function findLinks(){
                var url = $("#findlinksfromurl").val();
                var classname = $("#classname").val();
                $.post("urlcollector.php", {
                    submit : true,
                    url : url,
                    wclass : classname,
                }, function(data){
                    
                    $("#urls").val($("#urls").val() + data);

                });
            }
            
            var moreurls = [];
            var manualidx = 0;
            function startMultipleUrls(){
                moreurls = $("#urls").val().split(",");
                for(var i = 0; i < moreurls.length; i++){
                    $("#result").prepend("<div class='urllist' id='urlidx"+i+"'>"+moreurls[i]+"</div>");
                }
                startManualGrab();
            }
            
            function startManualGrab(){
                if(manualidx < moreurls.length){
                    grabImages(moreurls[manualidx], manualidx);
                    manualidx++;
                    setTimeout(function(){
                        startManualGrab();
                    },3000);
                }else{
                    alert("Manual Grab Done!");
                }
            }
            
        
            var canscan = false;
            var urlidx = 0;
            var baseurl = "";
            var startingurl = "";
            function startGrab(){
                canscan = true;
                baseurl = $("#baseurl").val();
                startingurl = $("#startingurl").val();
                if(startingurl == ""){
                    startingurl = baseurl;
                }
                $.post("urlcollector.php", {
                    submit : true,
                    url : startingurl,
                }, function(data){
                    
                    processData(data);

                });
            }
            
            
            function stopAutoScan(){
                canscan = false;
            }
            
            function processData(data){
                var data = data.split(",");
                for(var i = 0; i < data.length; i++){
                    var dt = data[i];

					var urlresult = dt;
					if(urlresult.indexOf("http") > -1){
						if(urlresult.indexOf(baseurl) > -1){
							
						}else{
							urlresult = "";
						}
					}else{
						urlresult = baseurl + "/" + dt;
					}
					
					if(urlresult != ""){
						//to avouid duplicated urls
						var alreadyexists = false;
						
						for(var z = 0; z < $(".urllist").length; z++){
							if($(".urllist").eq(z).html() == urlresult){
								alreadyexists = true;
							}
						}
						
						if(!alreadyexists){
							$("#result").prepend("<div class='urllist' id='urlidx"+urlidx+"'>"+urlresult+"</div>");
							urlidx++;
						}
					}

                }
                
                if(!deepscanstarted){
                    console.log("Initializing deepscan...");
                    setTimeout(function(){
                        nextStep();    
                    },3000)
                }
            }
            
            var deepscanidx = 0;
            var deepscanstarted = false;
            function nextStep(){
                
                deepscanstarted = true;
                if(deepscanidx < urlidx){
                    var durl = $("#urlidx"+deepscanidx).html();
                    console.log("Start deeper on deepscanidx" + deepscanidx + " url: " + durl + " of max urlidx " + urlidx);
                    //scan deeper
                    getDeeper(durl);
                    
					//collect images
					if(!sonly){
						grabImages(durl, deepscanidx);
					}
					deepscanidx++;
                    setTimeout(function(){
                        nextStep();
                    },3000);
                    
                }

            }
            
            function getDeeper(deeperurl){
                if(canscan){
                    $.post("urlcollector.php", {
                        submit : true,
                        url : deeperurl,
                    }, function(data){
                        processData(data);
                    });
                }
            }
            
            function grabImages(url, idx){
                
                $.post("grabber.php", {
                    submit : true,
                    url : url,
                }, function(data){
                    
                    $("#urlidx"+idx).css({ "color" : "green" })

                });
            }
        </script>
        
    </body>
</html>