$(document).ready(function(){

	$('#success-alert, #failure-alert').hide();

	$('.create').click(function(){
		$.ajax({
			type: 'POST',
			url: '../app/actions/create.php',
			data:{
				Product_Name: $('#NewProduct_Name').val(),
				Cost: $('#NewCost').val()
			},
			success:function(data){
				$('table tr:last').after('<tr class="lead" data-row-id="' + JSON.parse(data).id 
					+ '"><td class="col-xs-1"><input type="hidden" id="id" value="' + JSON.parse(data).id 
					+ '">' + JSON.parse(data).id 
					+ '</td><td class="col-xs-2">' + JSON.parse(data).Product_Name 
					+ '</td><td class="col-xs-2">' + JSON.parse(data).Cost 
					+ '</td><td class="text-center"><a class="btn btn-primary update-modal" data-toggle="modal" data-target="#updateModal" data-update-id="' 
					+ JSON.parse(data).id + '" data-update-product-name="' + JSON.parse(data).Product_Name 
					+ '" data-update-cost="' + JSON.parse(data).Cost 
					+ '">Update</a>&nbsp;<a class="btn btn-primary delete-modal" data-toggle="modal" data-target="#deleteModal" data-delete-id="' 
					+ JSON.parse(data).id+'">Delete</a></td></tr>');

				$('#success-alert').text('Row created successfully').show();
				$('#failure-alert').hide();	
			},
			error: function(request, status, error){
				$('#failure-alert').text('Server error: ' + JSON.parse(request.responseText).errorMessage).show();
				$('#success-alert').hide();
			}
		});
	});

	$('#delete').click(function(){
		$.ajax({
			type: 'POST',
			url: '../app/actions/delete.php',
			data:{
				id: $('#delete-id').val()
			},
			success:function(data){
				$('[data-row-id="'+$('#delete-id').val()+'"]').remove();
				$('#success-alert').text('Row deleted successfully').show();
				$('#failure-alert').hide();
			},
			error: function(request, status, error){
				$('#failure-alert').text('Server error: ' + JSON.parse(request.responseText).errorMessage).show();
				$('#success-alert').hide();
			}
		});
	});

	$('#update').click(function(){
		$.ajax({
			type: 'POST',
			url: '../app/actions/update.php',
			data:{
				id: $('#update-id').val(),
				Product_Name: $('#update-Product_Name').val(),
				Cost: $('#update-Cost').val()
			},
			success:function(data){
				$('[data-row-id="'+JSON.parse(data).id+'"]').children(':eq(0)').html('<input type="hidden" id="id" value="'
					+ JSON.parse(data).id +'">' 
					+ JSON.parse(data).id);
				$('[data-row-id="'+JSON.parse(data).id+'"]').children(':eq(1)').html(JSON.parse(data).Product_Name);
				$('[data-row-id="'+JSON.parse(data).id+'"]').children(':eq(2)').html(JSON.parse(data).Cost);
				$('[data-row-id="'+JSON.parse(data).id+'"]').children(':eq(3)').children().data('update-id', JSON.parse(data).id);
				$('[data-row-id="'+JSON.parse(data).id+'"]').children(':eq(3)').children().data('update-product-name', JSON.parse(data).Product_Name);
				$('[data-row-id="'+JSON.parse(data).id+'"]').children(':eq(3)').children().data('update-cost', JSON.parse(data).Cost);
				$('[data-row-id="'+JSON.parse(data).id+'"]').children(':eq(3)').children().data('delete-id', JSON.parse(data).id);
				$('#success-alert').text('Row updated successfully').show();
				$('#failure-alert').hide();
			},
			error: function(request, status, error){
				$('#failure-alert').text('Server error: ' + JSON.parse(request.responseText).errorMessage).show();
				$('#success-alert').hide();
			}
		});	
	});

	$('#deleteModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var id = button.data('delete-id');
		var modal = $(this);
		modal.find('.modal-title').text('ID: '+id);
		modal.find('.modal-body input').val(id);
	});

	$('#updateModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var id = button.data('update-id');
		var Product_Name = button.data('update-product-name');
		var Cost = button.data('update-cost');
		var modal = $(this);
		modal.find('.modal-title').text('ID: '+id);
		modal.find('.modal-body input#update-id').val(id);
		modal.find('.modal-body input#update-Product_Name').val(Product_Name);
		modal.find('.modal-body input#update-Cost').val(Cost);
	});

	$('.Product_Name-validate').on("keyup", function(){
		var input = $(this).val();
		if(!(isValidProduct(input))) {
		 	$(this).next('span.invalid-productName').text('Please enter alphabets (length between 3 and 25)').parents('.modal-body').next('.modal-footer').find('.btn-primary').attr('disabled', true);		 
		} else if (isPosNumber($(this).parent().next().find('input').val())){
		 	$(this).next('span.invalid-productName').text('').parents('.modal-body').next('.modal-footer').find('.btn-primary').attr('disabled', false);
		} else {
		 	$(this).next('span.invalid-productName').text('').parents('.modal-body').next('.modal-footer').find('.btn-primary').attr('disabled', true);
		}
	});

	$('.Cost-validate').on("keyup", function(){
		var input = $(this).val();
		if(!isPosNumber(input)) {
		 	$(this).next('span.invalid-Cost').text('Please enter positive numbers only').parents('.modal-body').next('.modal-footer').find('.btn-primary').attr('disabled', true);
		} else if (isValidProduct($(this).parent().prev().find('input').val())) {
		 	$(this).next('span.invalid-Cost').text('').parents('.modal-body').next('.modal-footer').find('.btn-primary').attr('disabled', false);
		} else {
		 	$(this).next('span.invalid-Cost').text('').parents('.modal-body').next('.modal-footer').find('.btn-primary').attr('disabled', true);
		}
	});
});

function isValidProduct(input) {
	return (isWord(input) && isValidLength(input));
}

// function isExists(input) {
	// var cells = getColumn('main-table', 1);
	// cells.splice(0, 1);
	// return (cells.includes(input));
// }

function isWord(input) {
	var re = /^[a-zA-Z_]+( [a-zA-Z_]+)*$/;
	return (re.test(input));
}

function isValidLength(input) {
	return (input.length >=3 && input.length <=25);
}

function isPosNumber(input) {
	var re = /^[1-9][0-9]*$/;
	return (re.test(input));
}

function getColumn(table_id, col) {
    var tab = document.getElementById(table_id),
        n = tab.rows.length,
        arr = [],
        row;
    
    if (col < 0) {
        return arr; // Return empty Array.
    }
    for (row = 0; row < n; ++row) {
        if (tab.rows[row].cells.length > col) {
            arr.push(tab.rows[row].cells[col].innerText);
        }
    }
    return arr;
}