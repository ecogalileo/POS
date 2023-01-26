<x-app-layout>

<center>
    <a class="btn btn-primary mt-5 mb-5 btnprn" href="javascript:void(0)" id="printReceipt"> Print Receipt</a>
</center>
<div class="container" id="print">

    <div class="row justify-content-center" id="logo">
        <center>
        <div class="col">
            <img src="{{ URL::to('assets/images/logo.png') }}" class="rounded-circle " alt="Cinque Terre" width="304" height="236">
        </div>
        <div class="w-100"></div>
        <div class="col">
            <h1 class="text-bold mb-5">Convenience Store</h1>
        </div>
        </center>
    </div>

    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
            <th class="text-center" scope="col">Product ID</th>
            <th class="text-center" scope="col">Product Name</th>
            <th class="text-center" scope="col">Price</th>
            <th class="text-center" scope="col">Quantity</th>
            <th scope="col">Total Amount</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $key => $datas)
                <tr>
                    <td class="text-center" scope="row">{{ $datas->product_id }}</td>
                    <td class="text-center">{{ $datas->product_name }}</td>
                    <td class="text-center">{{ $datas->price }}</td>
                    <td class="text-center">{{ $datas->quantity }} PCS</td>
                    <td class="total-amount">{{ $datas->total_amount }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="d-flex justify-content-end">
                    <b>Grand Total: </b>
                </td>
                <td colspan="4"><b class="total"></b></td>
            </tr>
        </tbody>
    </table>

</div>

</x-app-layout>
