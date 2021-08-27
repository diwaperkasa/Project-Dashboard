@extends('layout.index')

@include('components.libraries.datatable')
@include('components.libraries.ladda')
@include('components.libraries.swal2')

@section('title', 'Project Dashboard')

@section('style')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="card">
                <div class="card-header">Project List</div>
                <div class="card-body">
                    <div class="form-group">
                        <button id="addNew" class="btn btn-outline-primary">Add New</button>
                    </div>
                    <div class="row no-gutters mb-3 align-items-center">
                        <div class="col col-md-12">
                            <input class="form-control pr-5 pl-3" type="search" id="tableSearch" placeholder="Search">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-light text-dark border-0 rounded-pill ml-n5" type="button" id="btnTableSearch">
                                <svg class="c-icon c-icon-sm">
                                    <use xlink:href="{{ asset('assets/icons/sprites/free.svg#cil-search') }}"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="projectTable" class="table align-items-center table-flush table-hover no-footer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Desc</th>
                                    <th>Virtual Host</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <!-- Create Modal -->
    <div class="modal fade" id="createDataModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createForm" action="javascript:void(0);">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="createName">Project Name</label>
                            <input id="createName" type="text" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="createDesc">Project Description</label>
                            <textarea rows="3" id="createDesc" type="text" class="form-control" required ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="createUrl">Project Url</label>
                            <input id="createUrl" type="text" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button id="createButton" type="submit" class="btn btn-success" data-style="expand-right">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editDataModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" action="javascript:void(0);">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Project Name</label>
                            <input id="editName" type="text" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editDesc">Project Description</label>
                            <textarea rows="3" id="editDesc" type="text" class="form-control" required ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editUrl">Project Url</label>
                            <input id="editUrl" type="text" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button id="editButton" value="" type="submit" class="btn btn-success" data-style="expand-right">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@section('script')
    <script>
        (async function () {
            const table = $('#projectTable').DataTable({
                searching: false,
                responsive: true,
                serverSide: true,
                processing: true,
                rowId: 'id',
                ajax: {
                    url: `{{ url('api/task/list') }}`,
                    data: function (d) {
                        d.search.value = $('#tableSearch').val();
                    },
                    dataFilter: function (res) {
                        const json = JSON.parse(res);
                        return JSON.stringify(json.result);
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'tasks.name',
                        render: function ( data, type, row, meta ) {
                            return data.name;
                        }
                    },
                    {
                        data: null,
                        name: 'tasks.description',
                        render: function ( data, type, row, meta ) {
                            return data.description;
                        }
                    },
                    {
                        data: null,
                        name: 'tasks.url',
                        render: function ( data, type, row, meta ) {
                            let res = ``;

                            res += `<a target="_blank" href="${data.url}">${data.url}</a>`;

                            return res;
                        }
                    },
                    {
                        data: null,
                        name: 'tasks.id',
                        className: 'text-center',
                        render: function ( data, type, row, meta ) {
                            let res = ``;
                            // edit button
                            res += `<button title="Edit" data-toggle="tooltip" data-style="zoom-in" data-id="${data.id}" class="btn btn-sm btn-outline-info edit">`;
                            res += `<svg class="c-icon c-icon-sm"><use xlink:href="{{ asset('assets/icons/sprites/free.svg#cil-pencil') }}"></use></svg>`;
                            res += `</button>`;
                            // delete button
                            res += `<button title="Delete" data-toggle="tooltip" data-style="zoom-in" data-id="${data.id}" class="btn btn-sm btn-outline-danger ml-1 delete">`;
                            res += `<svg class="c-icon c-icon-sm"><use xlink:href="{{ asset('assets/icons/sprites/free.svg#cil-trash') }}"></use></svg>`;
                            res += `</button>`;

                            return res;
                        }
                    },
                ],
                drawCallback: function( settings ) {
                    document.querySelectorAll('#projectTable tbody tr td [data-toggle="tooltip"]').forEach(function (element) {
                        // eslint-disable-next-line no-new
                        new coreui.Tooltip(element);
                    });
                },
                initComplete: function () {
                    $('#projectTable tbody').on('click', 'td .edit', async function () {
                        const id = $(this).data('id');
                        const row = table.row( $('tr#' + id) ).data();

                        $('#editName').val(row.name);
                        $('#editDesc').val(row.description);
                        $('#editUrl').val(row.url);
                        $('#editButton').val(id);
                        $('#editDataModal').modal('show');
                    });

                    $('#projectTable tbody').on('click', 'td .delete', async function () {
                        const id = $(this).data('id');
                        const deleteButton = Ladda.create(this);

                        Swal.fire({
                            text: 'Apakah anda yakin ingin menghapus data ini?',
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#D50000',
                            cancelButtonColor: '#CED4DA',
                            confirmButtonText: 'Hapus',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `{{ url('api/task') }}/${id}`,
                                    method: 'DELETE',
                                    beforeSend: function () {
                                        deleteButton.start();
                                    },
                                    success: function (res) {
                                        table.draw();
                                    },
                                    complete: function () {
                                        deleteButton.stop();
                                    }
                                });
                            }
                        });
                    });
                }
            });

            $('#tableSearch').on('change', function () {
                table.draw();
            });

            $('#btnTableSearch').on('click', function () {
                table.draw();
            });

            $('#addNew').on('click', function () {
                $('#createName, #createDesc, #createUrl').val(null)
                $('#createDataModal').modal('show');
            });

            $('#createForm').submit(function (e) {
                e.preventDefault();

                const createButton = Ladda.create(document.querySelector('#createButton'));
                const body = {
                    name: $('#createName').val(),
                    description: $('#createDesc').val(),
                    url: $('#createUrl').val()
                };

                $.ajax({
                    url: `{{ url('api/task') }}`,
                    method: 'POST',
                    data: body,
                    beforeSend: function () {
                        createButton.start();
                    },
                    success: function (res) {
                        table.draw();
                        $('#createDataModal').modal('hide');
                    },
                    complete: function () {
                        createButton.stop();
                    }
                });
            });

            $('#editForm').submit(function (e) {
                e.preventDefault();

                const id = $('#editButton').val();
                const editButton = Ladda.create(document.querySelector('#editButton'));
                const body = {
                    name: $('#editName').val(),
                    description: $('#editDesc').val(),
                    url: $('#editUrl').val()
                };

                $.ajax({
                    url: `{{ url('api/task') }}/${id}`,
                    method: 'PATCH',
                    data: body,
                    beforeSend: function () {
                        editButton.start();
                    },
                    success: function (res) {
                        table.draw();
                        $('#editDataModal').modal('hide');
                    },
                    complete: function () {
                        editButton.stop();
                    }
                });
            });
        })();
    </script>
@endsection