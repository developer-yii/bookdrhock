$(document).ready(function () {

    let deleteBtnId = '#delete-poll',
        modalId = '#poll-modal',
        modalCancleBtnId = '#model-cancle-btn',
        viewBtnId = '#view-poll',
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
                            '<a href="javascript:void(0)" class="text-primary m-1 mt-0 mr-4"  id="view-poll" data-pollid="' + data.id + '"><i class="icon-eye"></i></a>' +
                            '<a href="javascript:void(0)" class="text-success m-1 mt-0 mr-4"  onclick="pollViewRedirect(\'' + data.slug + '\')" id="redirect-poll" data-pollid="' + data.id + '"><i class="ti-link"></i></a>' +
                            '<a href="javascript:void(0)" class="text-info m-1 mt-0 mr-4" onclick="pollEditRedirect(' + data.id + ')" id="edit-poll" data-pollid="' + data.id + '"><i class="ti-pencil-alt"></i></a>' +
                            '<a href="javascript:void(0)" class="text-danger m-1 mt-0" data-pollid="' +
                            data.id + '" id="delete-poll"><i class="ti-trash"></i></a>' +
                            '</h4>';
                        return html;
                    },
                    className: 'align-middle',
                    width: '18%',
                    orderable: false,
                    searchable: false
                },
            ]
        });

    //Modal cancle
    $(modalCancleBtnId).on('click', function (e) {
        e.preventDefault();
        $('#poll-modal #poll_id').val('');
        $(modalId).modal('hide');
    })

    var pollOptionDatatable = "";

    function viewPollOptionDatatable(pollId = '') {
        if (pollOptionDatatable) {
            pollOptionDatatable.destroy();
        }
        pollOptionDatatable = $('#poll-option-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: routes.getPollOptionsUrl,
                type: 'POST',
                data: function (d) {
                    // d.search = $('input[type="search"]').val();
                    d.poll_id = pollId;
                },
            },
            order: ['3', 'desc'],
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false
                },
                {
                    data: 'image',
                    name: 'image',
                    render: function (data, type, full, meta) {
                        if (data != '-') {
                            return '<img src="' + data + '" alt="' + full.title + '" width="50px" height="50px">';
                        } else {
                            return '-';
                        }
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'votes',
                    name: 'votes'
                }
            ]
        });
    }

    // View Poll
    $(document).on('click', viewBtnId, function (e) {
        e.preventDefault();
        $(modalId).find('.modal-title').text('View Poll');
        console.log(table.row($(this).parents('tr')).data());
        $.each(table.row($(this).parents('tr')).data(), function (key, value) {
            if (key == 'id') {
                $('#poll-modal #poll_id').val(value);
            } else {
                $('.poll-information .poll-info-table').find('.' + key).text(value);
            }
        })
        $(modalId).modal('show');
        $(modalId).modal('handleUpdate')
        viewPollOptionDatatable(table.row($(this).parents('tr')).data().id);
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
