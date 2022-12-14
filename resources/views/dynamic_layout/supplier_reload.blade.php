@if (sizeof($Suppliers) == 0)
    <table class="table align-middle table-hover table-sm">
        <thead class="table">
            <tr>
                <th scope="col" style="text-align: center;">Không có khuyến mãi cần tìm</th>
            </tr>
        </thead>
    </table>
@else
    <table class="table align-middle table-hover table-sm">
        <thead class="table">
            <tr>
                <th scope="col">Mã</th>
                <th scope="col">Tên nhà cung cấp</th>
                <th scope="col">Địa chỉ</th>
                <th scope="col">Số điện thoại</th>
                <th scope="col">Sửa</th>
                <th scope="col">Xóa</th>
            </tr>
        </thead>
        <tbody id="show-product">
            @foreach ($Suppliers as $Supplier)
                <tr>
                    <td scope="row">{{ $Supplier->id }}</td>
                    <td scope="row">{{ $Supplier->supplier_name }}</td>
                    <td scope="row">{{ $Supplier->address }}</td>
                    <td scope="row">{{ $Supplier->phone_number }}
                    </td>
                    <td>
                        <button type="button" class="btn edit-btn text-warning" data-toggle="modal"
                            data-target="#edit-supplier" data-id="{{ $Supplier->id }}" data-page="{{ $currentpage }}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </td>
                    <td>
                        <button class="delete-btn btn btn-sm text-danger" type="button" data-id="{{ $Supplier->id }}"
                            data-page="{{ $currentpage }}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example" class="col-md-12 my-3">
        <ul class="pagination pagination-sm justify-content-end" id="phantrang">
            @for ($i = 0; $i < ceil($total / 10); $i++)
                @if ($i == $currentpage - 1)
                    <li class="page-item"><a class="page-link active">{!! $i + 1 !!}</a></li>
                @else
                    <li class="page-item"><a class="page-link">{!! $i + 1 !!}</a></li>
                @endif
            @endfor
        </ul>
    </nav>
@endif
