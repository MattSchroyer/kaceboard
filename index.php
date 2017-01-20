<?php

	$xAxis="";
	$closedAxis="";
	$openedAxis="";
	$i = 0;
	
	global $mainQueueID;
	
	## mainQueueID is the queue.
	if(isset($_GET["queueID"])){
		$mainQueueID = $_GET["queueID"];
	}
	else{
		$mainQueueID = 25;
	}
	
	## maxDays is the range in the graph.
	if(isset($_GET["graphRange"])){
		$maxDays = $_GET["graphRange"];
	}
	else{
		$maxDays = 30;
	}
	include_once("config.php");
	include_once("mysqliresult.php");

	$seriesOpened = array();
	$seriesClosed = array();
	/* Prep open/closed */
	$m = strftime('%m');
	$y = strftime('%Y');
	$d = strftime('%d');
	for ($i = 0; $i < $maxDays; $i++)
	{
		$key = date("n/d", mktime(0, 0, 0, $m, $d-$i, $y));
		$seriesOpened[$key] = 0;
		$seriesClosed[$key] = 0;
		$xAxis.="'$key',";
	}
	$xAxis=substr($xAxis,0,-1);

	//***************************************
	// Closed Tickets (were closed during time period)
	//***************************************
	$query="
	SELECT
		COUNT(HD_TICKET.ID) as total,
		MONTH(TIME_CLOSED) as month,
		DAY(TIME_CLOSED) as day,
		YEAR(TIME_CLOSED) as year
	FROM
		HD_TICKET INNER JOIN
		HD_STATUS ON HD_TICKET.HD_STATUS_ID = HD_STATUS.ID
	WHERE
		(HD_TICKET.HD_QUEUE_ID = $mainQueueID)
		AND (HD_STATUS.STATE LIKE '%Closed%')
		AND (
			(HD_STATUS.NAME NOT LIKE '%spam%')
			AND (HD_STATUS.NAME NOT LIKE '%Server Status Report%')
		)
		AND (TIME_CLOSED >= ( CURDATE() - INTERVAL $maxDays DAY ))
	GROUP BY
		DATE(TIME_CLOSED)
	";


	$result = mysqli_query($dbh, $query);
	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}

	while( ($row = mysqli_fetch_assoc($result)) )
	{
		$total = $row['total'];
		$month = $row['month'];
		$day = $row['day'];
		$key = sprintf("%d/%02d",$month,$day);
		if ( isset($seriesClosed[$key]) ) // SQL time wraps to +1 days
			$seriesClosed[$key] = $total;
	}

	foreach($seriesClosed as $value)
	{
		$closedAxis .= "$value,";
	}
	$closedAxis = substr($closedAxis,0,-1);


	//***************************************
	// Opened Tickets (were created during time period)
	//***************************************
	$query1 = "
	SELECT
		COUNT(HD_TICKET.ID) as total,
		MONTH(CREATED) as month,
		DAY(CREATED) as day,
		YEAR(CREATED) as year
	FROM
		HD_TICKET INNER JOIN
		HD_STATUS ON HD_TICKET.HD_STATUS_ID = HD_STATUS.ID
	WHERE
		(HD_TICKET.HD_QUEUE_ID = $mainQueueID)
		AND (HD_STATUS.STATE LIKE '%Closed%')
		AND (
			(HD_STATUS.NAME NOT LIKE '%spam%')
			AND (HD_STATUS.NAME NOT LIKE '%Server Status Report%')
		)
		AND (CREATED >= ( CURDATE() - INTERVAL $maxDays DAY ))
	GROUP BY
		DATE(CREATED)
	";

	$result = mysqli_query($dbh, $query1);
	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		return;
	}

	while( ($row = mysqli_fetch_assoc($result)) )
	{
		$total = $row['total'];
		$month = $row['month'];
		$day = $row['day'];
		$key = sprintf("%d/%02d",$month,$day);
		if ( isset($seriesOpened[$key]) ) // SQL time wraps to +1 days
			$seriesOpened[$key] = $total;
	}

	foreach($seriesOpened as $value)
	{
		$openedAxis .= "$value,";
	}
	$openedAxis = substr($openedAxis,0,-1);
?>

<?php

	$query1 = "
	SELECT HD_TICKET.ID as ID, 
	HD_TICKET.TITLE as Title, 
	HD_STATUS.NAME AS Status, 
	HD_PRIORITY.NAME AS Priority, 
	HD_TICKET.CREATED as Created, 
	HD_TICKET.MODIFIED as Modified, 
	S.FULL_NAME  as Submitter, 
	O.FULL_NAME  as Owner, 
	HD_TICKET.CUSTOM_FIELD_VALUE0 as Type  
	FROM HD_TICKET  
	JOIN HD_STATUS ON (HD_STATUS.ID = HD_TICKET.HD_STATUS_ID) 
	JOIN HD_PRIORITY ON (HD_PRIORITY.ID = HD_TICKET.HD_PRIORITY_ID) 
	LEFT JOIN USER S ON (S.ID = HD_TICKET.SUBMITTER_ID) 
	LEFT JOIN USER O ON (O.ID = HD_TICKET.OWNER_ID) 
	WHERE (HD_TICKET.HD_QUEUE_ID = $mainQueueID) AND 
	(HD_STATUS.STATE not like '%Closed%')  
	ORDER BY Owner, Created DESC
	";

	$result1 = mysqli_query($dbh, $query1);
	$num = mysqli_num_rows($result1);
	
?>
	
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="refresh" content="300">
		<title>KACE Ticket Dashboard</title>
		<link href="includes/css/kaceboard.css" rel="stylesheet">
		<script type="text/javascript" src="includes/js/jquery.min.js"></script>
			
		<script type="text/javascript">
		// move this to a js file of its own?
			
			$(function () {
				var chart;
				$(document).ready(function() {
					chart = new Highcharts.Chart({
						chart: {
							renderTo: 'conReportByClosed',
							type: 'line',
							marginRight: 130,
							marginBottom: 25
						},
						title: {
							text: 'Tickets Closed, last <?php echo $maxDays; ?> days',
							x: -20 //center
						},
						subtitle: {
							text: 'Source: Kace',
							x: -20
						},
						xAxis: {
							categories: [<?php echo $xAxis ?>]
						},
						yAxis: {
							title: {
								text: 'Tickets Closed'
							},
							plotLines: [{
								value: 0,
								width: 1,
								color: '#808080'
							}]
						},
						tooltip: {
							formatter: function() {
									return '<b>'+ this.series.name +'</b><br/>'+
									this.x +': '+ this.y + (this.series.name=='Tickets Opened'?' Opened':' Closed');
							}
						},
						legend: {
							layout: 'vertical',
							align: 'right',
							verticalAlign: 'top',
							x: -10,
							y: 100,
							borderWidth: 0
						},
						series: [{
							name: 'Tickets Closed',
							data: [<?php echo $closedAxis ?>],
							lineWidth: 5
						}, {
							name: 'Tickets Opened',
							data: [<?php echo $openedAxis ?>],
							lineWidth: 1
						}]
					});
				});
				
			});
		</script>
		
		<script>
		var thisQueue = <?php echo $mainQueueID; ?>;
		var thisRange = <?php echo $maxDays; ?>;
		</script>
	</head>
	<body>
	
	<div id="menuBar">
		Service: 
		<select id="selectService" onchange="changeQueue(value)" >
			<option disabled>Pick a service</option>
			<option value="25">Service 25</option>
			<option value="12">Service 12</option>
			<option value="21">Service 21</option>
			<option value="19">Service 19</option>
		</select>
		Graph Range: 
		<select id="graphRange" onchange="changeRange(value)">
			<option value="7">7</option>
			<option value="14">14</option>
			<option value="30">30</option>
			<option value="60">60</option>
			<option value="90">90</option>
		</select>
		<div id="timestamp"></div>
	</div>
	
		<div id="numOpenTitle">
			Tickets Open
		</div>
		<div id="numOpen">	
			<?php printf("%d",$num); ?>
		</div>
	
	<div id="conReportByClosed"></div>
	<br>
	<br>
	<table class="ticketTable">
		<thead>
			<tr>
			  <th class=span1>Ticket ID</th>
			  <th class=span2>Title</th>
			  <th class=span2>Submitter</th>
			  <th class=span2>Owner</th>
			  <th class=span1>Created</th>
			  <th class=span1>Modified</th>
			</tr>
		</thead>
		<tbody>
	
		<?php

			$i = 0;
			while ($i < $num)
			{
				$ID = mysqli_result($result1,$i,"ID");
				$Title = mysqli_result($result1,$i,"Title");
				$Status = mysqli_result($result1,$i,"Status");        
				$Type = mysqli_result($result1,$i,"Type");
				$Created = mysqli_result($result1,$i,"Created");
				$Modified = mysqli_result($result1,$i,"Modified");
				$Priority = mysqli_result($result1,$i,"Priority");
				$Owner = mysqli_result($result1,$i,"Owner");	
				$Submitter = mysqli_result($result1,$i,"Submitter");

				$ID = stripslashes($ID);
				$Title = stripslashes($Title);
				$Status = stripslashes($Status);
				$Type = stripslashes($Type);
				$Created = stripslashes($Created);	
				$Modified = stripslashes($Modified);
				$Priority = stripslashes($Priority);
				$Owner = stripslashes($Owner);
				$Submitter = stripslashes($Submitter);


				$StatusSpan="";
				if ($Status=="Stalled")
				{
					$StatusSpan="<span class='label label-warning'>$Status</span>";
				}

				$PriortySpan="";
				if ($Priority=="High")
				{
					$PriortySpan="<span class='label label-important'><i class='icon-exclamation-sign icon-white'></i>High</span>";
				}

				if ($Priority=="Low")
				{
					$PriortySpan="<span class='label'>Low</span>";
				}

				echo "<tr><td><a href='http://$KaceBoxDNS/adminui/ticket.php?ID=$ID' target='_blank'>$ID</a> $StatusSpan $PriortySpan</td> \n";
				echo "<td>$Title</td> \n";
				echo "<td>$Submitter</td> \n";
				echo "<td>$Owner</td> \n";
				echo "<td>$Created</td> \n";
				echo "<td>$Modified</td> \n";
				echo "</tr> \n";
				$i++;
			}

			echo "</tbody></table> \n";
		?>
	
	<script src="includes/js/highcharts.js"></script>
	<script src="includes/js/exporting.js"></script>
	<script src="includes/js/menuBar.js"></script>

	</body>
</html>
