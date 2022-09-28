$(document).ready(function () {

    let formId = '#category-form',
        formErrorSpanClass = '.error-span',
        modalId = '#category-modal',
        categoryFormActionInput = '#category_form_action',
        modalCancleBtnId = '#model-cancle-btn',
        addFormBtnId = '#create-category',
        addOrUpdateBtnId = '#addorupdate-category',
        editFormBtnId = '#edit-category',
        deleteBtnId = '#delete-category',
        table = $('#category-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: routes.indexUrl,
            columns: [{
                    data: 'id',
                    name: 'id',
                    'visible': false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    className: 'text-capitalize align-middle'
                },
                {
                    data: 'slug',
                    name: 'slug',
                    orderable: false
                },
                {
                    data: null,
                    name: 'action',
                    render: function (data, type, full, meta) {
                        let html = '<h4 class="m-0"><a href="javascript:void(0)" class="text-info m-1 mt-0 mr-4" id="edit-category" data-categoryid="' +
                            data.id +
                            '"><i class="ti-pencil-alt"></i></a><a href="javascript:void(0)" class="text-danger m-1 mt-0" data-categoryid="' +
                            data.id + '" id="delete-category"><i class="ti-trash"></i></a></h4>';
                        return html;
                    },
                    className: 'align-middle',
                    width: '12%',
                    orderable: false,
                    searchable: false
                },
            ]
        })

    $(formId).bind("keypress", function (e) {
        if (e.keyCode == 13) {
            $(addOrUpdateBtnId).click();
            return false;
        }
    });

    // poll status update
    $(formId + " #name").on('focusout', function (e) {
        if ($(formId).find('#category_form_action').val() != 'edit' || $(formId).find('#slug').val() == '') {
            $.ajax({
                url: routes.getSlugUrl,
                method: 'POST',
                data: new FormData($(formId)['0']),
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.data) {
                        $(formId).find('#slug').val(response.data);
                    }
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }
    });

    //Modal cancle
    $(modalCancleBtnId).on('click', function (e) {
        e.preventDefault();
        $(formId)[0].reset();
        $(formId).find('#id').val('');
        $(modalId).modal('hide');
        $(formErrorSpanClass).text('');
        $(categoryFormActionInput).val('add');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
    })

    // Add Form Category
    $(addFormBtnId).on('click', function (e) {
        e.preventDefault();
        $(formId)[0].reset();
        $(formId).find('#id').val('');
        $(modalId).find('.modal-title').text('Add New Category');
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $(addOrUpdateBtnId).text('Add Category');
        $(categoryFormActionInput).val('add');
        $(modalId).modal('show');
        $(modalId).modal('handleUpdate');
    })

    // Edit form Category
    $(document).on('click', editFormBtnId, function (e) {
        e.preventDefault();
        $(modalId).find('.modal-title').text('Edit Category');
        $(addOrUpdateBtnId).text('Edit Category');
        $(formErrorSpanClass).text('');
        $(categoryFormActionInput).val('edit');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $.each(table.row($(this).parents('tr')).data(), function (key, value) {
            $(formId).find('#' + key).val(value);
        })
        $(modalId).modal('show');
        $(modalId).modal('handleUpdate')
    })

    // Add or Update Category
    $(addOrUpdateBtnId).on('click', function (e) {
        e.preventDefault();
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $.ajax({
            url: routes.addOrUpdateUrl,
            method: 'POST',
            data: new FormData($(formId)['0']),
            contentType: false,
            processData: false,
            success: function (response) {
                $(formId)[0].reset();
                table.draw();
                $(modalId).modal('hide');
                showMessage('success', response.message)
            },
            error: function (error) {
                $.each(error.responseJSON.errors, function (key, value) {
                    $('#' + key).parents('.form-group').find(formErrorSpanClass).text(value);
                    $('#' + key).parents('.form-group').addClass('has-error');
                });
            }
        })
    })

    //Delete Category
    $(document).on('click', deleteBtnId, function (e) {
        e.preventDefault();
        let categoryid = $(this).data('categoryid');
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this category data!",
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
                        id: categoryid
                    },
                    success: function (data) {
                        swal.close();
                        showMessage('success', data.message)
                        table.draw();
                    },
                    error: function (error) {
                        swal.close();
                        console.log('Error:', error);
                    }
                });
            }
        );
    })
});
