<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="row">
            <div class="col-5">
                <a class="btn btn-success mb-5" href="javascript:void(0)" id="createNewProduct"> Add Item</a>
            </div>
            <div class="col align-self-end">
                <a class="btn btn-primary mb-5 btnPrint" href="{{ url('prntreceipt') }}" id="printReceipt"> View Receipt</a>
            </div>
        </div>

        <!-- Product Table -->
        <table class="table table-bordered data-table">
            <!-- Product Table Head -->
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>price</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>

            <!-- Product Table Data -->
            <tbody>
                <!-- AJAX will render the datatable here -->
            </tbody>
        </table>
    </div>

    <!-- Add and Edit Modal -->
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="productForm" name="productForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Product Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    id="product_name" name="product_name" placeholder="Enter Product Name"
                                    value="{{ old('product_name') }}" maxlength="50">
                                <strong id="product_check">&nbsp; This Field is required</strong>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Quantity" class="col-sm-4 control-label">Quantity</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="quantity" name="quantity"
                                    placeholder="Enter Quantity" value="" maxlength="5">
                                <strong id="quantity_check">&nbsp; This Field is required</strong>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Price" class="col-sm-4 control-label">Price</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="price" name="price"
                                    placeholder="Enter Price" value="" maxlength="6">
                                <strong id="price_check">&nbsp; This Field is required</strong>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Total Amount" class="col-sm-4 control-label">Total Amount</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="total_amount" name="total_amount"
                                    placeholder="Enter Total Amount" value="" readonly>
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary bg-primary" id="saveBtn" value="create">Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
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
                $('#saveBtn').val("edit-user").addClass('edit-product');
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
                        if ($('#saveBtn').hasClass("edit-product")) {
                            Swal.fire(
                                'Product Updated Successfully!',
                                '',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Product Added Successfully!',
                                '',
                                'success'
                            )
                        }
                        table.draw();
                        location.reload()

                    },
                    error: function (data) {
                        console.log('Error:', data);
                        Swal.fire(
                            'Product Added Failure!',
                            '',
                            'warning'
                        )
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
                // confirm("Are You sure want to delete !");

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
                })
            });

        });
    </script>
</x-app-layout>
