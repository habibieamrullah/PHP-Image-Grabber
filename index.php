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
        
        <h1>PHP Image Grabber</h1>
        <p>Developed by <a href="https://www.youtube.com/@ThirteeNov/videos">ThirteeNov</a> (subscribe for more!)</p>
        
        <input id="baseurl" placeholder="Enter base url here">
        <button onclick="startGrab();">Start</button>
        
        <div id="result"></div>
        
        
        
        <script>
            var urlidx = 0;
            var baseurl = "";
            var moreurls = [];
            function startGrab(){
                baseurl = $("#baseurl").val();
                //alert("Started!");
                $.post("urlcollector.php", {
                    submit : true,
                    url : baseurl,
                }, function(data){
                    
                    processData(data);

                });
            }
            
            function processData(data){
                var data = data.split(",");
                for(var i = 0; i < data.length; i++){
                    if(data[i] != baseurl || data[i] != baseurl + "/"){
                        var urlresult;
                        urlresult = baseurl + "/" + data[i];
                        
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
                    grabImages(durl, deepscanidx);
                    deepscanidx++;
                    setTimeout(function(){
                        nextStep();
                    },3000);
                    
                }
            }
            
            function getDeeper(deeperurl){
                $.post("urlcollector.php", {
                    submit : true,
                    url : deeperurl,
                }, function(data){
                    processData(data);
                });
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