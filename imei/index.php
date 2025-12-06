
<html>
    
    
    
    
<head>
	<title>Server Limit</title>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	 
	   
	<style type="text/css">
		body {
			font-size: 15px;
			color: #343d44;
			font-family: "segoe-ui", "open-sans", tahoma, arial;
			padding: 0;
			margin: 0;
		}
		table {
			margin: auto;
			font-family: "Lucida Sans Unicode", "Lucida Grande", "Segoe Ui";
			font-size: 12px;
		}

		h1 {
			margin: 25px auto 0;
			text-align: center;
			text-transform: uppercase;
			font-size: 17px;
		}

		table td {
			transition: all .5s;
		}
		
		/* Table */
		.data-table {
			border-collapse: collapse;
			font-size: 14px;
			min-width: 537px;
		}

		.data-table th, 
		.data-table td {
			border: 1px solid #e1edff;
			padding: 7px 17px;
		}
		.data-table caption {
			margin: 7px;
		}

		/* Table Header */
		.data-table thead th {
			background-color: #508abb;
			color: #FFFFFF;
			border-color: #6ea1cc !important;
			text-transform: uppercase;
		}

		/* Table Body */
		.data-table tbody td {
			color: #353535;
		}
		.data-table tbody td:first-child,
		.data-table tbody td:nth-child(4),
		.data-table tbody td:last-child {
			text-align: right;
		}

		.data-table tbody tr:nth-child(odd) td {
			background-color: #f4fbff;
		}
		.data-table tbody tr:hover td {
			background-color: #ffffa2;
			border-color: #ffff0f;
		}

		/* Table Footer */
		.data-table tfoot th {
			background-color: #e5f5ff;
			text-align: right;
		}
		.data-table tfoot th:first-child {
			text-align: left;
		}
		.data-table tbody td:empty
		{
			background-color: #ffcccc;
		}
	</style>
</head>
<body>
    
    <center>

  <label for="fname">imei1</label><br>
  <input type="text" id="uname" name="fname" value=""><br>
  <label for="lname">imei2</label><br>
  <input type="text" id="jumlahcredit" name="lname" value=""><br><br>
  <input class="gass" type="submit" value="Submit" onclick="onclick" ><br>
    <textarea id="imei"  name="w3review" rows="4" cols="50"></textarea>
    
</center>

	








	<script type="text/javascript">
 $(document).ready( function () {

		
		
		$(".gass").click(function(){
           
        var imei1 = document.getElementById("uname").value ; 
        var imei2 = document.getElementById("jumlahcredit").value ; 
       
             $.post("https://vegito-auth.com/imei/imei.php", { 
                    imei1: imei1,
                    imei2: imei2,
                    
                }, 
                  
                function(data,status) { 
                    document.getElementById('imei').value = data;
                  //  alert(data);
                
                }); 
       
       
       
       
       
    
		});
		
		
		
		
		
		
		
		
		
   
});
	</script>
	
	
</body>
</html>