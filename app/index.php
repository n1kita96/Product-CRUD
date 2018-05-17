<?php
$title = "Products";
require "templates/header.php";
?>
	<h1 class="text-center">Database</h1>
	<div class="alert alert-success" id="success-alert" role="alert"></div>
	<div class="alert alert-danger" id="failure-alert" role="alert"></div>
<?php
try {
	require "../dbpath.php";
	$connection = new PDO ($dsn);
	$sql = "SELECT * FROM Product";
	$statement = $connection->prepare( $sql );
	$statement->execute();
	$result = $statement->fetchAll();
} catch ( PDOException $error ) {
	echo $sql . "<br>" . $error->getMessage ();
}
?>

<div class="row">
	<h2 class="col-md-4">Product</h2>
	<button data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-lg col-md-2 col-md-offset-6">Add Product</button>
</div>
<hr>
<div class="table-responsive">
	<table class="table table-striped table-condensed table-hover" id="main-table">
		<thead>
			<tr class="lead">
				<th class="col-xs-1">#</th>
				<th>Product Name</th>
				<th>Cost</th>
				<th class="text-center">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $result as $row ) {	?>
				<tr class="lead" data-row-id="<?php echo $row["id"]; ?>">
					<td class="col-xs-1">
						<input type="hidden" id="id" value="<?php echo $row["id"]; ?>">
						<?php echo $row["id"]; ?>
					</td>
					<td class="col-xs-2">
						<?php echo $row["Product_Name"]; ?>
					</td>
					<td class="col-xs-2">
						<?php echo number_format($row["Cost"]);?>
					</td>
					<td class="text-center actions-container">
						<a class="btn btn-primary update-modal" data-toggle="modal" data-target="#updateModal" data-update-id="<?php echo $row["id"]; ?>" data-update-product-name="<?php echo $row["Product_Name"]; ?>" data-update-cost="<?php echo $row["Cost"];?>">Update</a>
						<a class="btn btn-primary delete-modal" data-toggle="modal" data-target="#deleteModal" data-delete-id="<?php echo $row["id"]; ?>">Delete</a>
					</td>
				</tr>			
			<?php } ?>
		</tbody>
	</table>
</div>
<?php require "templates/createModal.php"; ?>
<?php require "templates/updateModal.php"; ?>
<?php require "templates/deleteModal.php"; ?>
<?php require "templates/customJS.php"; ?>
<?php require "templates/footer.php"; ?>