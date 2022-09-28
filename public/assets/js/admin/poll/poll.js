$(document).ready(function () {

    let deleteBtnId = '#delete-poll',
        table = $('#poll-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: routes.indexUrl,
            order: ['3', 'asc'],
            columns: [{
                    data: 'id',
                    name: 'id',
                    'visible': false
                },
                {
                    data: 'title',
                    name: 'title',
                    className: 'text-capitalize align-middle'
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    className: 'text-capitalize align-middle'
                },
                {
                    data: 'start_datetime',
                    name: 'start_datetime',
                    className: 'align-middle'
                },
                {
                    data: 'end_datetime',
                    name: 'end_datetime',
                    className: 'align-middle'
                },
                {
                    data: null,
                    name: 'action',
                    render: function (data, type, full, meta) {
                        let html = '<h4 class="m-0">' +
                            '<a href="javascript:void(0)" class="text-primary m-1 mt-0 mr-4"  onclick="pollViewRedirect(\'' + data.slug + '\')" id="view-poll" data-pollid="' + data.id + '"><i class="icon-eye"></i></a>' +
                            '<a href="javascript:void(0)" class="text-info m-1 mt-0 mr-4" onclick="pollEditRedirect(' + data.id + ')" id="edit-poll" data-pollid="' + data.id + '"><i class="ti-pencil-alt"></i></a>' +
                            '<a href="javascript:void(0)" class="text-danger m-1 mt-0" data-pollid="' +
                            data.id + '" id="delete-poll"><i class="ti-trash"></i></a>' +
                            '</h4>';
                        return html;
                    },
                    className: 'align-middle',
                    width: '12%',
                    orderable: false,
                    searchable: false
                },
            ]
        })

    //Delete Poll
    $(document).on('click', deleteBtnId, function (e) {
        e.preventDefault();
        let pollid = $(this).data('pollid');
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this poll data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function () {
                $.ajax({
                    url: routes.deleteUrl,
                    type: "DELETE",
                    data: {
                        id: pollid
                    },
                    success: function (data) {
                        swal.close();
                        showMessage('success', data.message)
                        table.draw();
                    },
                    error: function (error) {
                        swal.close();
                        if (error.responseJSON.error == 'notvalid') {
                            showMessage('error', error.responseJSON.message)
                        }
                        console.log('Error:', error);
                    }
                });
            }
        );
    })
});
