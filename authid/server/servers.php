<?php
require_once '/includes/konak.php';
session_start();
$typeadmin = $_SESSION['admin_type'];
if (!$typeadmin == "super") {
	echo "<h1><center>You Are Not Admin Super</h1><center>";
	die();
}

?>
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

		.data-table tbody td:empty {
			background-color: #ffcccc;
		}
	</style>
</head>

<body>

	<table id="serverlimit" class="data-table">
		<caption class="title">List Server</caption>
		<thead>
			<tr>
				<th>Server Id</th>
				<th>Status</th>
				<th>Limit FLASH</th>
				<th>Limit FRP</th>
				<th>Limit FDL</th>
				<th>===</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$query = mysqli_query($koneksi, "SELECT * FROM status");
			$no = 1;
			$total = 0;

			while ($row = mysqli_fetch_array($query)) {
				?>
				<tr>
					<td> <input type="text" maxlength="4" size="4" id="ids<?php echo $no; ?>" value="<?php echo $row['id']; ?>"
							name="fname" readonly></td>
					<td>

						<select class="status" name="status" id="status<?php echo $no; ?>">

							<?php if ($row['state'] == "Online") { ?>
								<option value="Online">Online</option>
								<option value="Offline">Offline</option>
							<?php } ?>


							<?php if ($row['state'] == "Offline") { ?>

								<option value="Offline">Offline</option>
								<option value="Online">Online</option>
							<?php } ?>
						</select>



					</td>
					<td> <input type="text" maxlength="4" size="4" id="limitflash<?php echo $no; ?>"
							value="<?php echo $row['limitsetflash'] ?>" name="fname"></td>
					<td> <input type="text" maxlength="4" size="4" id="limitfrp<?php echo $no; ?>"
							value="<?php echo $row['limitsetfrp'] ?>" name="fname"></td>
					<td> <input type="text" maxlength="4" size="4" id="limitfdl<?php echo $no; ?>"
							value="<?php echo $row['limitsetfdl'] ?>" name="fname"></td>
					<td><button id="<?php echo $no; ?>" class="kuy" id="simpan" type="button">Save</button></td>
				</tr>
				<?php
				$no++;
			} ?>
		</tbody>
		<tfoot>

		</tfoot>
	</table>
	<script type="text/javascript">
		$(document).ready(function () {



			var buttons = document.querySelectorAll('button');

			for (var i = 0; i < buttons.length; ++i) {
				buttons[i].addEventListener('click', clickFunc);
			}

			function clickFunc() {
				var e = document.getElementById("status" + this.id);
				var value = e.value;
				var status = e.options[e.selectedIndex].text;
				let sid = document.getElementById("ids" + this.id).value;
				let flash = document.getElementById("limitflash" + this.id).value;
				let frp = document.getElementById("limitfrp" + this.id).value;
				let fdl = document.getElementById("limitfdl" + this.id).value;

				$.post("https://xiaomibdteam.net/adm/server/savesetting.php", {
					sid: sid,
					status: status,
					flash: flash,
					frp: frp,
					fdl: fdl,


				},

					function (data, status) {

						alert(data);

					});



			}















		});
	</script>


</body>

</html>