// Document is ready
$(document).ready(function () {
    // Validate Products
    $("#product_check").hide();
    let productError = true;
    $("#product_name").keyup(function () {
        validateProducts();
    });

    function validateProducts() {
        let productValue = $("#product_name").val();
        if (productValue.length == "") {
        $("#product_check").addClass('invalid-feedback').show();
        productError = false;
        return false;
        } else if (productValue.length < 3) {
        $("#product_check").show();
        $("#product_check").html("&nbsp;product name should contain atlest 3 characters").addClass('invalid-feedback');
        productError = false;
        return false;
        } else {
        $("#product_check").hide();
        }
    }

    // Validate Quantity
    $("#quantity_check").hide();
    let quantityError = true;
    $("#quantity").keyup(function () {
        validateQuantity();
    });

    function validateQuantity() {
        let quantityValue = $("#quantity").val();
        if (quantityValue.length == "") {
        $("#quantity_check").addClass('invalid-feedback').show();
        quantityError = false;
        return false;
        } else if (quantityValue.length < 1) {
        $("#quantity_check").show();
        $("#quantity_check").html("&nbsp;quantity should contain atlest 1 number").addClass('invalid-feedback');
        quantityError = false;
        return false;
        } else {
        $("#quantity_check").hide();
        }
    }


    // Validate Price
    $("#price_check").hide();
    let priceError = true;
    $("#price").keyup(function () {
        validatePrice();
    });

    function validatePrice() {
        let priceValue = $("#quantity").val();
        if (priceValue.length == "") {
        $("#price_check").addClass('invalid-feedback').show();
        priceError = false;
        return false;
        } else if (priceValue.length < 1) {
        $("#price_check").show();
        $("#price_check").html("&nbsp;price should contain atlest 1 number").addClass('invalid-feedback');
        priceError = false;
        return false;
        } else {
        $("#price_check").hide();
        }
    }


    //Calulation of quantity and price
    $("#productForm").keyup(function () {
        total_amount = $('#quantity').val() * $('#price').val();
        $('#total_amount').val(total_amount);
    });

    // Submit button
    $("#saveBtn").click(function () {
        validateProducts();
        validateQuantity();
        validatePrice();

        if (
        productError == true &&
        quantityError == true &&
        priceError == true
        ) {
        return true;
        } else {
        return false;
        }
    });
});

$(function () {
    /*------------------------------------------
    --------------------------------------------
    Pass Header Token
    --------------------------------------------
    --------------------------------------------*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*------------------------------------------
    --------------------------------------------
    Render DataTable
    --------------------------------------------
    --------------------------------------------*/
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'product_id', name: 'product_id'},
            {data: 'product_name', name: 'product_name'},
            {data: 'price', name: 'price'},
            {data: 'quantity', name: 'quantity'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });


    /*------------------------------------------
    --------------------------------------------
    Click to Button
    --------------------------------------------
    --------------------------------------------*/
    $('#createNewProduct').click(function () {
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("Add Item");
        $('#ajaxModel').modal('show');
    });


    /*------------------------------------------
    --------------------------------------------
    Click to Edit Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.editProduct', function () {
    var product_id = $(this).data('id');
    $.get("{{ route('products.index') }}" +'/' + product_id +'/edit', function (data) {
        $('#modelHeading').html("Edit Product");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal('show');
        $('#id').val(data.id);
        $('#product_id').val(data.product_id);
        $('#product_name').val(data.product_name);
        $('#quantity').val(data.quantity);
        $('#price').val(data.price);
        $('#total_amount').val(data.total_amount);
    })
    });

    /*------------------------------------------
    --------------------------------------------
    Create Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        // $(this).html('Sending..');

        // if($('#product_name').val() === ''){
        //     $('#product_name').addClass('is-invalid');
        // }

        $.ajax({
            data: $('#productForm').serialize(),
            url: "{{ route('products.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {

                $('#productForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                Swal.fire(
                    'Product Added Successfully!',
                    '',
                    'success'
                )
                table.draw();

            },
            error: function (data) {
                console.log('Error:', data);
                // Swal.fire(
                //     'Product Added Failure!',
                //     '',
                //     'warning'
                // )
                $('#saveBtn').html('Save Changes');
            }
        });
    });


    /*------------------------------------------
    --------------------------------------------
    Delete Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.deleteProduct', function () {
        var product_id = $(this).data("id");

        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('products.store') }}"+'/'+product_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });

});
