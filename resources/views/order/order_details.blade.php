<!DOCTYPE html>
<html lang="en">

<head>
    <x-head_tag />
    <title>Chi tiết đơn hàng</title>
</head>

<body class="bg-light">
    <nav class="d-flex align-items-center justify-content-between bg-white">
        <div style="margin-left: 1rem">
            <h4 class="m-0 font-weight-bold">Đơn hàng #{{ $Order->id }}</h4>
            <p class="m-0">{{ date('d-m-Y h:i:s', strtotime($Order->order_date)) }}</p>
        </div>
        <div>
            <button class="btn btn-sm bg-info text-light" style="margin-right: 1rem" onclick="window.print()">Xuất hóa
                đơn</button>
        </div>
    </nav>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-9 col-sm-12">
                <div class="col-md-12 bg-light rounded shadow p-3">
                    <div>
                        <h5 class="">Sản phẩm đã đặt</h5>
                    </div>
                    <hr>
                    @foreach ($Order_details as $detail)
                        <div class="row mb-3">
                            <div class="col-sm-2 d-flex align-items-center">
                                <img src="{{ url('/image/' . $detail->image) }}" width="70" height="70" />
                            </div>
                            <div class="col-sm-5 d-flex flex-column" style="justify-content:center">
                                <h5>{{ $detail->product_name }}</h5>
                            </div>
                            <div class="col-sm-1 d-flex justify-content-center align-items-center">
                                <span class="" style="">x{{ $detail->quantity }}</span>
                            </div>
                            <div class="col-sm-2 d-flex justify-content-center flex-column align-items-center">
                                <?php $totalperproduct = 0; ?>
                                @if ($detail->percent != null && $detail->percent > 0)
                                    <span
                                        class="product-discount-price text-decoration-line-through text-secondary mr-1">{{ number_format($detail->price, 0, '.') }}
                                        VND</span>
                                    <?php $discount = doubleval($detail->price) * (1 - doubleval($detail->percent / 100));
                                    $totalperproduct = $discount * $detail->quantity; ?>
                                    <h6 class="m-0 text-info">{{ number_format($discount, 0, '.') }} VND</h6>
                                @else
                                    <?php $totalperproduct = $detail->quantity * $detail->price; ?>
                                    <span
                                        class="product-discount-price text-secondary mr-1">{{ number_format($detail->price, 0, '.') }}
                                        VND</span>
                                @endif
                            </div>
                            <div class="col-sm-2 d-flex justify-content-center flex-column align-items-center">
                                <h6 class="m-0">{{ number_format($totalperproduct, 0, '.') }} VND</h6>
                            </div>
                        </div>
                    @endforeach
                    <hr>
                    <div class="row">
                        <div class="col-sm-7"></div>
                        <div class="col-sm-5 d-flex justify-content-center flex-column">
                            <div class="row">
                                <span class="col-sm-6">Tổng tiền</span>
                                <h6 class="col-sm-6" style="text-align: end;">
                                    {{ number_format($Order->total_price) }} VND</h6>
                            </div>
                            <div class="row">
                                <span class="col-sm-6">Phí vận chuyển</span>
                                <h6 class="col-sm-6" style="text-align: end;">0 VND</h6>
                            </div>
                            <hr class="m-1" />
                            <div class="row">
                                <span class="col-sm-6">Giá cuối</span>
                                <h6 class="col-sm-6" style="text-align: end;">
                                    {{ number_format($Order->total_price) }} VND</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="col-md-12 bg-light rounded shadow p-3">
                    <div>
                        <h5 class="">Trạng thái đơn hàng</h5>
                    </div>
                    <hr>
                    <div class="row d-flex justify-content-start align-items-center">
                        <div class="col-md-4 d-flex justify-content-center">
                            <img <?php if ($Order->status == 0 || $Order->status == 1 || $Order->status == 3 || $Order->status == 4) {
                                echo 'class="border-primary rounded-circle border"';
                            } else {
                                echo 'class="border-secondary rounded-circle border"';
                            } ?> style="object-fit: contain" width="50" height="50"
                                src="{{ url('/icons/order-pending.png') }}" />
                        </div>
                        <div class="col-md-8">
                            <h6 class="m-0">
                                Chờ xác nhận
                                @if ($Order->status == 0 || $Order->status == 1 || $Order->status == 3 || $Order->status == 4)
                                    <i class="fa-solid fa-check text-success"></i>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-center col-md-4">
                            <span style="height: 2rem; border-left: 2px dotted" <?php if ($Order->status == 0 || $Order->status == 1 || $Order->status == 3 || $Order->status == 4) {
                                echo 'class="border-primary"';
                            } else {
                                echo 'class="border-secondary"';
                            } ?>></span>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-start align-items-center">
                        <div class="col-md-4 d-flex justify-content-center">
                            <img <?php if ($Order->status == 1 || $Order->status == 3 || $Order->status == 4) {
                                echo 'class="border-primary rounded-circle border"';
                            } else {
                                echo 'class="border-secondary rounded-circle border"';
                            } ?> style="object-fit: contain" width="50" height="50"
                                src="{{ url('/icons/order-approved.png') }}" />
                        </div>
                        <div class="col-md-8">
                            <h6 class="m-0">
                                Đã xác nhận
                                @if ($Order->status == 1 || $Order->status == 3 || $Order->status == 4)
                                    <i class="fa-solid fa-check text-success"></i>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-center col-md-4">
                            <span style="height: 2rem; border-left: 2px dotted" <?php if ($Order->status == 1 || $Order->status == 3 || $Order->status == 4) {
                                echo 'class="border-primary"';
                            } else {
                                echo 'class="border-secondary"';
                            } ?>></span>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-start align-items-center">
                        <div class="col-md-4 d-flex justify-content-center">
                            <img <?php if ($Order->status == 3 || $Order->status == 4) {
                                echo 'class="border-primary rounded-circle border"';
                            } else {
                                echo 'class="border-secondary rounded-circle border"';
                            } ?> style="object-fit: contain" width="50" height="50"
                                src="{{ url('/icons/shipping.png') }}" />
                        </div>
                        <div class="col-md-8">
                            <h6 class="m-0">
                                Đang giao
                                @if ($Order->status == 3 || $Order->status == 4)
                                    <i class="fa-solid fa-check text-success"></i>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-center col-md-4">
                            <span style="height: 2rem; border-left: 2px dotted" <?php if ($Order->status == 3 || $Order->status == 4) {
                                echo 'class="border-primary"';
                            } else {
                                echo 'class="border-secondary"';
                            } ?>></span>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-start align-items-center">
                        <div class="col-md-4 d-flex justify-content-center">
                            <img class="rounded-circle border {!! $Order->status == 4 ? 'border-primary' : 'border-secondary' !!}" style="object-fit: contain"
                                width="50" height="50" src="{{ url('/icons/delivered.png') }}" />
                        </div>
                        <div class="col-md-8">
                            <h6 class="m-0">
                                Đã giao
                                @if ($Order->status == 4)
                                    <i class="fa-solid fa-check text-success"></i>
                                @endif
                            </h6>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-start align-items-center mt-4">
                        <div class="col-md-4 d-flex justify-content-center">
                            <img class="rounded-circle border {!! $Order->status == 2 ? 'border-danger' : 'border-secondary' !!}" style="object-fit: contain"
                                width="50" height="50" src="{{ url('/icons/order-canceled.png') }}" />
                        </div>
                        <div class="col-md-8">
                            <h6 class="m-0">
                                Đã hủy
                                @if ($Order->status == 2)
                                    <i class="fa-solid fa-xmark text-danger rounded-circle"></i>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="col-md-12 bg-light p-3 rounded shadow mb-3">
                        <div>
                            <h5 class="">Chi tiết khách hàng</h5>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <i class="fa-regular fa-user"></i>
                                <span>{{ $Order->fullname }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <i class="fa-solid fa-envelope"></i>
                                <span>{{ $Order->email }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <i class="fa-solid fa-phone-flip"></i>
                                <span>{{ $Order->phone_number }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ $Order->address }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
