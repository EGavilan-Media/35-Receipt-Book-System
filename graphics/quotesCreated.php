<?php

include "../connection.php";

	$sql = "SELECT DATE_FORMAT(quote_created_datetime, '%Y-%m'), COUNT(DATE_FORMAT(quote_created_datetime, '%Y-%m'))
	FROM tbl_quote GROUP BY DATE_FORMAT(quote_created_datetime, '%m-%Y')";
	$result = mysqli_query($conn, $sql);

	while ($row = mysqli_fetch_row($result)){
		$valuesX[] = $row[0];
		$valuesY[] = $row[1];
	}

	$valuesX = json_encode($valuesX);
	$valuesY = json_encode($valuesY);

?>

<script>
	function quoteCreated(json){
		var parsed = JSON.parse(json);
		var data = [];
		for (var x in parsed){
			data.push(parsed[x]);
		}
		return data; 
	}

	valuesX=quoteCreated('<?php echo $valuesX ?>');
	valuesY=quoteCreated('<?php echo $valuesY ?>');

	var trace1 = {
		x: valuesX,
		y: valuesY,
		type: 'scatter'
	};

	var data = [trace1];

	Plotly.newPlot('quotesGraphic', data);
</script>