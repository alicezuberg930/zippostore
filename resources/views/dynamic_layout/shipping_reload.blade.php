<table class="table table-hover table-sm">
    <thead class="table table-striped table-sm">
        <tr>
            <th scope="col">Mã đơn</th>
            <th scope="col">Ngày đặt</th>
            <th scope="col">Tổng tiền</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody id="order-table">
        <?php date_default_timezone_set('Asia/Ho_Chi_Minh'); ?>
        @foreach ($Orders as $order)
            <tr>
                <th>{{ $order->id }}</th>
                <td>{{ date_format(new DateTime($order->order_date), 'd/m/Y h:i:s') }}</td>
                <td>{{ number_format($order->total_price) }} đ</td>
                <td>
                    <span>
                        @if ($order->status == 1)
                            Đã xác nhận
                        @elseif($order->status == 3)
                            Đang giao
                        @elseif($order->status == 4)
                            Đã giao
                        @endif
                    </span>
                </td>
                <td>
                    @if ($order->status == 1)
                        <button class="btn btn-sm btn-outline-success checked-btn" data-id="{{ $order->id }}"
                            data-status="3">Bắt đầu giao</button>
                    @elseif($order->status == 3)
                        <button class="btn btn-sm btn-outline-success checked-btn" data-id="{{ $order->id }}"
                            data-status="4">Hoàn thành</button>
                    @endif
                    <a class="btn btn-sm fa-solid fa-circle-exclamation text-primary"
                        href="/admin/manage_orders/order_details/{{ $order->id }}"></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<nav aria-label="Page navigation example" class="col-md-12 my-3">
    <ul class="pagination pagination-sm justify-content-end" id="phantrang">
        <?php $total = 0;
        if (session()->get('type') == 1) {
            $total = $Quantity['Approved'];
        } elseif (session()->get('type') == 3) {
            $total = $Quantity['Delivering'];
        } elseif (session()->get('type') == 4) {
            $total = $Quantity['Delivered'];
        } ?>
        @for ($i = 0; $i < ceil($total / 10); $i++)
            @if ($i == $currentpage - 1)
                <li class="page-item"><a class="page-link active">{{ $i + 1 }}</a></li>
            @else
                <li class="page-item"><a class="page-link">{{ $i + 1 }}</a></li>
            @endif
        @endfor
    </ul>
</nav>
