<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Progress TracKer</title>
        <link rel="icon" href="logo.png" type="image/gif" sizes="16x16">
        <style>
            @import url('https://fonts.googleapis.com/css?family=Raleway');
            
            html {
                overflow-y: scroll;
            }
            
            body{
                display:flex;
                justify-content:center;
                text-align:center;
                min-width:300px;
                max-width:1000px;
                margin-right:auto;
                margin-left:auto;
                margin-top:0px;
                background-color:#443693;
                color:White;
                font-family: 'Raleway', sans-serif;
            }
            
            #loading-box {
                display:none;
                position:absolute;
                z-index:1;
                text-align:center;
                width:100%;
                height:100%;
            }
            
            #loading-box {
                z-index:2;
                background-color:rgba(0,0,0,0.5);
                position:fixed;
            }
            
            #loading-content {
                margin-top:100px;
                width:200px;
                margin-right:auto;
                margin-left:auto;
            }
            
            .container{
                width:100%;
            }
            
            .card {
                border: solid black 1px;
                border-radius: 5px;
                display: inline-block;
                text-align:center;
                padding: 0px 0px 5px 0px;
                margin: 2px;
                background-color:rgb(200, 200, 200);
                color:Black;
            }
            
            @keyframes slide {
               0% { transform: translateY(0px); }
               10% { transform: translateY(-10px); }
               20% { transform: translateY(0px); }
               100% { transform: translateY(0px); }
            }
            
            h2,p {
                padding: 0px;
                margin: 2px 0px;
            }
            
            .card form {
                width:140px;
            }
            
            .card form input {
                display:block;
                text-align:center;
                padding: 0px;
                margin-right:auto;
                margin-left:auto;
            }
            
            input {
                border: black solid 1px;
                border-radius:5px;
                height:20px;
                width:110px;
                margin:2px 0px;
                background-color: rgb(240, 240, 240);
            }
            
            input[type="submit"] {
                border-radius:5px;
                margin-left:auto;
            }
            
            input[type="submit"]:hover {
                background-color: rgb(120, 120, 120);
                color:ghostwhite;
            }
            
            input[type="image"]{
                border:none;
                max-width:20px;
                opacity: 0.5;
                position: relative;
                left: 58px;
                top:1.25px;
                padding: 0px;
                margin: 0px;
                background-color:rgba(0,0,0,0);
                transition: opacity, transform 0.25s;
            }
            
            input[type="image"]:hover {
                opacity: 0.7;
                transform:scale(1.2);
            }
            
            .update-form{
                display:flex;
                justify-content:center;
            }
            
            .update-form p{
                position:relative;
                margin-left:auto;
                top:2px;
                left:5px;
            }
            
            .update-form input{
               position:relative;
               max-width:50px;
               right:5px;
            }
            
            .x-button{
                height:0px;
            }
            
            fieldset{
                margin-bottom: 20px;
                border: solid white 2px;
                
            }
            
            fieldset legend {
                font-size:28px;
                cursor:pointer;
            }
            
            strong {
                font-size:28px;
            }
            
            header {
                display:flex;
                justify-content:center;
                padding: 0px 10px;
            }
            
            header h1 {
                margin-right: 1px;
                margin-top:auto;
                font-size:40px;
            }
            
            header h2 {
                margin-left:auto;
                margin-top:auto;
            }
            
            button {
                font-size:32px;
                cursor: pointer;
                margin: 0px 10px;
                
            }
            button:hover {
                transform: scale(1.1);
            }
            
            #progress-inside, #goal-inside, #settings-inside {
                display:none;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            
            function toggleSlide(elem,elemName){
                $(elem+"-inside").delay(200).slideToggle();
                if ($(elem).text() == "Show " + elemName) {
                        $(elem).text("Hide " + elemName);
                }
                else {
                    $(elem).text("Show " + elemName);
                }
            }
            
            function upSlideImm(elem, elemName){
                $(elem+"-inside").slideUp();
                $(elem).text("Show " + elemName);
            }
            
            function downSlideImm(elem, elemName){
                $(elem+"-inside").slideDown();
                $(elem).text("Hide " + elemName);
            }
            
            function getData(){
                $("#loading-box").fadeIn();
                params = "id=" + 0;
		        xmlhttp = new XMLHttpRequest();
		        xmlhttp.onreadystatechange = function() {
    			    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
    			        //console.log('Response Received');
    			        xml_response = xmlhttp.responseXML;
    			        //console.log('XML Doc:', xml_response);
    			        
    			        xml_goal = xml_response.getElementsByTagName("goal")[0].childNodes[0];
    			        //console.log('Goal:', xml_goal);
    			        document.getElementById("goal-inside").innerHTML = xml_goal.wholeText;
    			        
    			        xml_progress = xml_response.getElementsByTagName("progress")[0].childNodes[0];
    			        //console.log('Progress:', xml_progress);
    			        document.getElementById("progress-inside").innerHTML = xml_progress.wholeText;
    			        
    			        xml_settings = xml_response.getElementsByTagName("settings")[0].childNodes[0];
    			        document.getElementById("settings-inside").innerHTML = xml_settings.wholeText;
    			        
    			        
    			        initializeForms();
    		    	}
		        };
		        xmlhttp.open("POST","get_data.php",true);
		        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		        xmlhttp.send(params);
		        //xmlhttp.send();
            }
            
            function initializeForms(){
                //console.log('Initializing Forms: Progress');
                
                // Access the form element...
                var forms = document.getElementsByClassName("progressForm");
                //console.log(forms[0])
                
                for (var i = 0; i < forms.length; i++) {
                    //console.log(forms[i]);
                    //var tempForm = forms[i];
                    forms[i].addEventListener("submit", function (event) {
                        //console.log('EVENT CALLED')
                        //console.log(event);
                        event.preventDefault();
                        //console.log(event.target);
                        sendData(event.target, "update_daily.php");
                    });
                }
                
                
                //console.log('Initializing Forms: Settings');
                var forms = document.getElementsByClassName("settingForm");
                //console.log(forms[0])
                
                for (var i = 0; i < forms.length; i++) {
                    //console.log(forms[i]);
                    var tempForm = forms[i];
                    forms[i].addEventListener("submit", function (event) {
                        //console.log('EVENT CALLED')
                        //console.log(event);
                        event.preventDefault();
                        //console.log(event.target);
                        sendData(event.target, "update_parameter.php");
                    });
                }
                $("#loading-box").fadeOut();
            }
            
            function sendData(form, url) {
                $("#loading-box").fadeIn();
                var XHR = new XMLHttpRequest();
                
                //console.log(form);
            
                // Bind the FormData object and the form element
                var FD = new FormData(form);
            
                // Define what happens on successful data submission
                XHR.addEventListener("load", function(event) {
                  //alert(event.target.responseText);
                  getData();
                });
            
                // Define what happens in case of error
                XHR.addEventListener("error", function(event) {
                  alert('Oops! Something went wrong.');
                });
            
                // Set up our request
                XHR.open("POST", url);
            
                // The data sent is what the user provided in the form
                XHR.send(FD);
            }
            
            $(document).ready(function(){
                downSlideImm("#goal","Goal");
                getData();
                
                
                $("#projects").click(function(){
                    toggleSlide("#projects", "Projects");
                });
                
                $("#goal").click(function(){
                    toggleSlide("#goal","Goal");
                });
                
                $("#progress").click(function(){
                    toggleSlide("#progress", "Progress");
                });
                
                $("#settings").click(function(){
                    toggleSlide("#settings", "Settings");
                });
            });
        </script>
    </head>
    
    <body>
        <div id="loading-box">
            <div id="loading-content">
                <img width="100%" src="loading.gif">
            </div>
        </div>
        
        <div class="container">
            <header>
                <img src="logo.png" height="20px">
                <h1>Progress TracKer</h1>
            </header>
            
            <fieldset>
                <legend id="goal">Show Goal</legend>
                <div id="goal-inside">
                </div>
            </fieldset>
            
            <fieldset>
                <legend id="progress">Show Progress</legend>
                <div id="progress-inside">
                </div>
            </fieldset>
        
            <fieldset>
                <legend id="settings">Show Settings</legend>
                <div id="settings-inside">
                </div>
            </fieldset>
        </div>
    </body>
</html>