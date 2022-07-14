<?php 
    session_start();
	require "controlUserData.php";
	require "excelWritter.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Data Checker</title>
	<link type="text/css" rel="stylesheet" href="stylist.css">
	<link href = "images/logo.png" rel="icon" type = "image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="stylist.css">
	<link rel="stylesheet" href="btnStyle.css">
</head>
<body>
    
    <div id="preloader"></div>
	<form action="index.php" method="post">
		<div id="leftdiv">
				<textarea  name="checker1" id="dc1" placeholder="Paste your text here"></textarea> <!-- oninput="auto_grow(this)" -->
				<button class="btnsub" type="submit" name="submit" ><span>Check</span></button>
				<p id="rawInput"><?php
						if($_SERVER["REQUEST_METHOD"] == "POST") {
	                        if(isset($_POST['checker1'])){
    							$firstNames = nl2br($_POST['checker1']);
    							
                        		$arr_temp = rtrim($firstNames);
                        
                        		$arr = array_map('strval', preg_split('/\n/', $arr_temp, -1, PREG_SPLIT_NO_EMPTY));
                        		for($i=0;$i<count($arr);$i++)
                				{
                					if ($remarks[$i] == "Has Numeric Value") 
                					{
                						?>
                							<mark style="background-color: red;"> 
                								<?php
                									echo $arr[$i];
                								?>
                							</mark>
                						<?php
                					}
                
                					elseif ($remarks[$i] == "Has Special Character") 
                					{
                						?> 
                							<mark style="background-color: yellow;"> 
                								<?php
                									echo $arr[$i];
                								?>
                							</mark>
                						<?php
                					}
                
                					elseif ($remarks[$i] == "No Remarks") 
                					{
                						?> 
                							<span style="color: black;"> 
                								<?php
                									echo $arr[$i];
                								?>
                							</span>
                						<?php
                					}
                				}
						    }
						    if(isset($_FILES['excel']['tmp_name'])){
    							
    							
                        		for($i=0;$i<count($columnArray);$i++)
                				{
                				    if (preg_match('/[\'^£$%&*()}{@#~?!;,|=_+¬]/', $columnArray[$i]))
                        			{
                        				?>
                							<mark style="background-color: red;"> 
                								<?php
                									echo $columnArray[$i];
                								?>
                							</mark>
                						<?php
                						echo "<br/>";
                        			}
                        			else if(preg_match('~[0-9]+~', $columnArray[$i])){
                        				?>
                							<mark style="background-color: yellow;"> 
                								<?php
                									echo $columnArray[$i];
                								?>
                							</mark>
                						<?php
                						echo "<br/>";
                        			}
                        			else
                        			{
                        				?> 
                							<span style="color: black;"> 
                								<?php
                									echo $columnArray[$i];
                								?>
                							</span>
                						<?php
                						echo "<br/>";
                        			}
                				}
						    }
						}
					?></p>
		</div>
	</form>

	<div id="midDiv">
		<h2 style="color: #32a87b;text-align:center;">Corrected</h2>
		
		<p id="dc2">
			<?php
			if($_SERVER["REQUEST_METHOD"] == "POST") {
	           if(isset($_POST['checker1'])){
				$count=sizeof($remarks);

				for($counter=0;$counter<$count;$counter++)
				{
					if ($remarks[$counter] == "Has Numeric Value") 
					{
						?> 
							<mark style="background-color: red;"> 
								<?php
									print_r($correctedArr[$counter]);
								?>
							</mark>
						<?php
						echo "<br/>";
					}

					elseif ($remarks[$counter] == "Has Special Character") 
					{
						?> 
							<mark style="background-color: yellow;"> 
								<?php
									print_r($correctedArr[$counter]);
								?>
							</mark>
						<?php
						echo "<br/>";
					}

					elseif ($remarks[$counter] == "No Remarks") 
					{
						?> 
							<span style="color: black;"> 
								<?php
									print_r($correctedArr[$counter]);
								?>
							</span>
						<?php
						echo "<br/>";
					}
				}
	           }
	           if(isset($_FILES['excel']['tmp_name'])){
				$count=sizeof($correctedXlsArr);

				for($i = 0;$i < $count; $i++)
				{
					if (preg_match('/[\'^£$%&*()}{@#~?!;,|=_+¬]/', $columnArray[$i]))
        			{
        				?>
							<mark style="background-color: red;"> 
								<?php
									echo $correctedXlsArr[$i];
								?>
							</mark>
						<?php
						echo "<br/>";
        			}
        			else if(preg_match('~[0-9]+~', $correctedXlsArr[$i])){
        				?>
							<mark style="background-color: yellow;"> 
								<?php
									echo $columnArray[$i];
								?>
							</mark>
						<?php
						echo "<br/>";
        			}
        			else
        			{
        				?> 
							<span style="color: black;"> 
								<?php
									echo $correctedXlsArr[$i];
								?>
							</span>
						<?php
						echo "<br/>";
        			}
				}
	           }
			}
				
			?>
		</p>
		<button class="btn btn-light" onclick="copyElementText('dc2'); copy();" style="position:absolute;bottom:4%;right:8%;">Copy text</button>
		<span id="msgCtt" style="
            display: none;
            position:absolute;
            bottom:4%;right:27%;
            padding: 5px 12px;
            background-color: #000000df;
            border-radius: 4px;
            color: #fff;">copied!</span>
	</div>

	<div id="rightDiv">
		<h2 style="color: #7a00b3;text-align:center;">Remarks</h2>
		<p id="dc3">
			<?php
			if($_SERVER["REQUEST_METHOD"] == "POST") {
	           if(isset($_POST['checker1'])){
				echo $dispRemarks . "<br/> <br/>";
				//print_r($remarks);
	           }
	           if(isset($_FILES['excel']['tmp_name'])){
    	            for ($i=0; $i < sizeof($columnArray); $i++) 
                    { 
                        	if (preg_match('/[\'^£$%&*()}{@#~?!;,|=_+¬]/', $columnArray[$i]))
                        	{
                        		echo "Has Special Character" . "<br/>";
                        	}
                        	else if(preg_match('~[0-9]+~', $columnArray[$i])){
                        		echo "Has Numeric Value" . "<br/>";
                        	}
                        	else
                        	{
                        		echo "No Remarks" . "<br/>";
                        	}
                    }
	           }
			}
			?>
		</p>
	</div>
	<div class="jumbotron text-center " style="position: absolute;top: 0%;width: 100%; height: 90px;">
		
	    <h3 style="position: absolute; top: 0%;right: 10%;">Upload Excel(.xls) file here</h3>
	</div>
	<div class="container" style="position: absolute;top: 0%;height: 90px;width: 100%;">
	    <form action="" method="post" enctype="multipart/form-data" >
	        <h1>Data Checker</h1> 
	           <div style="position: absolute;top: 50px; right: 19%;">
	               <div class="form-group">
	                   <input type="file" name="excel" id="excelDoc" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
	               </div>
	           </div>
	           <div style="position: absolute;top: 50px;right: 11%;">
	               <input onclick="checkAttachment()" type="submit" name="uploadBtn" id="uploadBtn" value="Start Checking" class="btn btn-success" />
	           </div>
	    </form>
	    <a href="xlsFiles/<?php echo $_SESSION['filename']?>" class="buttonDownload" onclick="checkDownloadFile()">Download</a>
	</div>
	<!--<div class="progressParent" id="progressParent">-->
 <!--   	<div class="progress">-->
 <!--         <div class="progress__fill"></div>-->
 <!--         <span class="progress__text">0%</span>-->
 <!--       </div>-->
    <!--</progressParent>-->
    <script>
        var loader = document.getElementById("preloader");
        var loaderBar = document.getElementById("progressParent");
        const pBar = document.querySelector(".progress");
        
        window.addEventListener("load",function(){
            //loaderBar.style.display = "none";
            loader.style.display = "none";
            
            // updateProgressBar(pBar,0);
        })
        
        // loaderBar.style.display = "inline";
        $(document).ready(function(){
          $(".btnsub").click(function(){
              loader.style.display = "initial";
            //   loaderBar.style.display = "initial";
            //   updateProgressBar(pBar,0);
            //   updateProgressBar(pBar,100);
            //   $("#progressParent").fadeIn(3000);
              if(document.getElementById("dc1").value == '')
                {
                    alert("Please paste your text first");
                }
                  
          });
        });
        
        
        var isSyncingLeftScroll = false;
        var isSyncingMidScroll = false;
        var isSyncingRightScroll = false;
        var leftDiv = document.getElementById('rawInput');
        var midDiv = document.getElementById('dc2');
        var rightDiv = document.getElementById('dc3');
        
        leftDiv.onscroll = function() {
          if (!isSyncingLeftScroll) {
            isSyncingRightScroll = true;
            isSyncingMidScroll = true;
            rightDiv.scrollTop = this.scrollTop;
            midDiv.scrollTop = this.scrollTop;
          }
          isSyncingLeftScroll = false;
          isSyncingMidScroll = false;
        }
        midDiv.onscroll = function() {
          if (!isSyncingMidScroll) {
            isSyncingLeftScroll = true;
            isSyncingRightScroll = true;
            leftDiv.scrollTop = this.scrollTop;
            rightDiv.scrollTop = this.scrollTop;
          }
          isSyncingRightScroll = false;
          isSyncingRightScroll = false;
        }

        rightDiv.onscroll = function() {
          if (!isSyncingRightScroll) {
            isSyncingLeftScroll = true;
            isSyncingMidScroll = true;
            leftDiv.scrollTop = this.scrollTop;
            midDiv.scrollTop = this.scrollTop;
          }
          isSyncingRightScroll = false;
          isSyncingMidScroll = false;
        }
        function copyElementText(id) {
        
        var text = document.getElementById(id).innerText;
        var elem = document.createElement("textarea");
        document.body.appendChild(elem);
        elem.value = text;
        elem.select();
        document.execCommand("copy");
        document.body.removeChild(elem);
        
        }
        function copy(){
            document.getElementById("msgCtt").style.display = "inline";
            setTimeout( function() {
                document.getElementById("msgCtt").style.display = "none";
            }, 1000);    
        }
        function checkDownloadFile(){
            if (!<?php echo isset($_SESSION['filename'])?'true':'false'; ?>) {
            alert("Please upload a file first")
            } else {
            <?php 
            unset($_SESSION['filename']);
            // print_r($_SESSION['filename']);
            ?>
            }    
        }
        function checkData(){
            setTimeout( function() {
                loaderBar.style.display = "inline";
            }, 1000);   
             
        }
        function updateProgressBar(progressBar, value) {
          value = Math.round(value);
          progressBar.querySelector(".progress__fill").style.width = `${value}%`;
          progressBar.querySelector(".progress__text").textContent = `${value}%`;
        }
        
        // const myProgressBar = document.querySelector(".progress");
        
        /* Example */
        // updateProgressBar(myProgressBar, 100);
        
    </script>
<script type="text/javascript"  src="datachecker.js"></script>
</body>
</html>