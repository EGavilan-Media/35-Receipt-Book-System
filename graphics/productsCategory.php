<?php
include "../connection.php";

	$sql="SELECT cat.category_name, COUNT(prod.category_id)
	FROM tbl_product AS prod 
	INNER JOIN tbl_category AS cat
	ON prod.category_id=cat.category_id
	GROUP BY cat.category_name";

	$result = mysqli_query($conn, $sql);

	while ($row = mysqli_fetch_row($result)){
		$valuesX[] = $row[0];
		$valuesY[] = $row[1];
	}

	$valuesX = json_encode($valuesX);
	$valuesY = json_encode($valuesY);

?>

<script>

	function pieProductsCategory(json){
		var parsed = JSON.parse(json);
		var data = [];
		for (var x in parsed){
			data.push(parsed[x]);
		}
		return data; 
	}
	
	valuesX = pieProductsCategory('<?php echo $valuesX ?>');
	valuesY = pieProductsCategory('<?php echo $valuesY ?>');

	var data = [{
		values: valuesY,
		labels: valuesX,
		type: 'pie'
	}];

	var layout = {
  		height: 450,
  		width: 900
	};

	Plotly.newPlot('productsCategoryGraphic', data, layout);

</script>
