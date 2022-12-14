@extends('admin.adminpage')
@section('body_manager')
    <div class="col-md-9 col-lg-10">
        <x-admin_header />
        @if (!$authorize)
            <h3>Bạn không có quyền quản lý danh mục</h3>
        @else
            <div class="container-md p-0">
                <div class="p-3 row row-cols-1 row-cols-md-3 sticky-top bg-light justify-content-between">
                    <div class="col-md-auto row">
                        <div class="col-md-auto">
                            <input type="radio" class="btn-check" autocomplete="off" value="Tổng đơn">
                            <label class="btn btn-outline-primary btn-sm" for="btnradio1">Tổng danh mục
                                <span class="badge bg-danger">{{ $total }}</span>
                            </label>
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-category">Thêm danh
                                mục</button>
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-info btn-sm" id="export">Xuất Excel</button>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" placeholder="Tên thể loại"
                                id="search_id">
                            <i class="fa-solid fa-magnifying-glass text-light p-2 bg-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" id="category-table">
                    @include('dynamic_layout.category_reload')
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="add-category" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel2">Thêm danh mục</h5>
                    <button type="lbutton" class="btn-close" data-dismiss="modal" aria-labe="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center justify-content-around">
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label fw-semibold">Tên</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name-category-add" value="Thương hiệu A">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputPassword" class="col-sm-2 col-form-label fw-semibold">Mô tả</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="desc-category-add" placeholder="Mô tả gì đó" style="height: 10rem"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="add-btn">Thêm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade edit-modal" id="edit-category" tabindex="-1" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Sửa danh mục</h5>
                    <button type="lbutton" class="btn-close" data-dismiss="modal" aria-labe="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center justify-content-around">
                        <div class="col-md-12">
                            <div class="row col-md-auto">
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="name-category-modal" disabled
                                            id="id-category-modal">
                                        <label for="floatingInput">Mã</label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-floating mb-3">
                                        <input name="name-category-modal" id="name-category-modal" class="form-control"
                                            value="">
                                        <label for="floatingInput">Tên</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control desc" id="description-category-modal" placeholder="Mô tả gì đó"
                                            style="height: 12rem"></textarea>
                                        <label for="floatingInput">Mô tả</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="edit-btn">Sửa</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let current_page = 1
        $("#add-btn").on('click', function() {
            $.ajax({
                url: "/admin/manage_category/add",
                method: "get",
                data: {
                    category_name: $('#name-category-add').val(),
                    category_description: $('#desc-category-add').val(),
                    page: current_page
                },
                success: function(result) {
                    $("#category-table").html(result.response)
                    $('.toast').toast('show')
                    $('.toast-body').html(result.message)
                    if (result.status == 1)
                        $('.toast').css('background-color', 'rgb(71, 201, 71)')
                    else
                        $('.toast').css('background-color', 'rgb(239, 73, 73)')
                }
            })
        })
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).attr('data-id')
            let name = $(this).parent().parent().children().eq(1).text()
            let description = $(this).parent().parent().children().eq(2).text().trim()
            $("#id-category-modal").val(id)
            $("#name-category-modal").val(name)
            $("#description-category-modal").val(description)
        })
        $("#edit-btn").on('click', function() {
            $.ajax({
                url: "/admin/manage_category/edit",
                method: "get",
                data: {
                    id: $("#id-category-modal").val(),
                    category_name: $("#name-category-modal").val(),
                    category_description: $("#description-category-modal").val(),
                    page: current_page
                },
                success: function(result) {
                    $("#category-table").html(result.response)
                    $('.toast').toast('show')
                    $('.toast-body').html(result.message)
                    if (result.status == 1)
                        $('.toast').css('background-color', 'rgb(71, 201, 71)')
                    else
                        $('.toast').css('background-color', 'rgb(239, 73, 73)')
                }
            })
        })
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).attr('data-id')
            $.ajax({
                url: "/admin/manage_category/delete",
                method: "get",
                data: {
                    id: id,
                    page: current_page
                },
                success: function(result) {
                    $("#category-table").html(result.response)
                    $('.toast').toast('show')
                    $('.toast-body').html(result.message)
                    if (result.status == 1)
                        $('.toast').css('background-color', 'rgb(71, 201, 71)')
                    else
                        $('.toast').css('background-color', 'rgb(239, 73, 73)')
                }
            })
        })
        $('#search_id').keypress(function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $.ajax({
                    url: "/admin/manage_category/search",
                    method: "get",
                    data: {
                        category_name: $(this).val(),
                        page: 1
                    },
                    success: function(result) {
                        $("#category-table").html(result)
                    }
                })
            }
        });
        $(document).on('click', '.page-item', function() {
            current_page = $(this).text()
            $.ajax({
                url: "/admin/manage_category/paginate/" + $(this).text(),
                method: "get",
                success: function(result) {
                    console.log(result);
                    $("#category-table").html(result)
                }
            })
        })
        $("#export").on('click', () => {
            $.ajax({
                url: "/admin/manage_category/export",
                method: 'get',
                success: function(result) {
                    JSONToCSVConvertor(result, "category_sheet", true)
                }
            })

        })
    </script>
@endsection
