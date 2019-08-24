		</div><br><br>
		<div class="col-md-12 text-center">&copy;Copyright 2017-2019 Boutique</div>
		<script>
			function detailsmodal(id) {  
				var data = {"id":id};
				jQuery.ajax({
					url :'/ecom/includes/detailsmodal.php',
					method: "post",
					data : data ,
					success: function(data) {
						jQuery('body').append(data);
						jQuery('#details-modal').modal('toggle');
					},
					error : function() {
						alert ("something went wrong");
					}
				});
	 		}

	 		function update_cart(mode,edit_id,edit_size) {
	 			var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
	 			jQuery.ajax({
	 				url : '/ecom/admin/parsers/update_cart.php',
	 				method : "post",
	 				data : data,
	 				success : function(){ location.reload(); },
	 				error : function(){ alert("Something went wrong");}
	 	
	 			});

	 		}

	 		function add_to_cart(){
	 			jQuery('#modal_errors').html("");
	 			var size = jQuery('#size').val();
	 			var quantity = jQuery('#quantity').val();
	 			var available = jQuery('#available').val();
	 			var errors = '';
	 			var data = jQuery('#add_product_form').serialize();
	 			if(size == '' || quantity == ''|| quantity == 0){
	 				errors +='<p class="text-danger text-center">You must choose a size and quantity.</p>';
	 				jQuery('#modal_errors').html(errors);
	 				return;
	 			}else if(quantity > available){
	 				errors +='<p class="text-danger text-center">There are only '+available+' available.</p>';
	 				jQuery('#modal_errors').html(errors);
	 				return;
	 			}else {
	 				jQuery.ajax({
	 					url : '/ecom/admin/parsers/add_cart.php',
	 					method : 'post',
	 					data : data,
	 					success : function(){
	 						location.reload();
	 					},
	 					error : function(){alert("Something went wrong");},
	 				});

	 			} 
	 		}

		</script>
    </body>
</html>