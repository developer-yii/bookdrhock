$(document).ready(function () {

    $(document).ajaxComplete(function () {
        if ($(".bt-switch").length > 0) {
            $(".bt-switch").bootstrapSwitch();
        }
        if ($(".bt-switch-disable").length > 0) {
            $(".bt-switch-disable").bootstrapSwitch({
                disabled: true
            });
        }
    })

    let formId = '#user-form',
        formErrorSpanClass = '.error-span',
        modalId = '#user-modal',
        userFormActionInput = '#user_form_action',
        modalCancleBtnId = '#model-cancle-btn',
        addFormBtnId = '#create-user',
        addOrUpdateBtnId = '#addorupdate-user',
        editFormBtnId = '#edit-user',
        deleteBtnId = '#delete-user',
        updateuserStatusSwitchId = '#update-status-user',
        passwordFormRowId = '#password-form-row',
        changePasswordFormBtnId = '#change-password-user',
        table = $('#user-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: routes.indexUrl,
            columns: [{
                    data: 'id',
                    name: 'id',
                    'visible': false
                },
                {
                    data: 'first_name',
                    name: 'first_name',
                    className: 'text-capitalize align-middle'
                },
                {
                    data: 'last_name',
                    name: 'last_name',
                    className: 'text-capitalize align-middle'
                },
                {
                    data: 'email',
                    name: 'email',
                    className: 'align-middle'
                },
                {
                    data: 'phone',
                    name: 'phone',
                    className: 'align-middle'
                },
                {
                    data: 'user_role_id',
                    name: 'user_role_id',
                    'visible': false
                },
                {
                    data: 'role',
                    name: 'role',
                    className: 'text-capitalize align-middle'
                },
                {
                    data: null,
                    name: 'status',
                    render: function (data, type, full, meta) {
                        let html =
                            '<input class="' + ((routes.loginUserId != data.id) ? 'bt-switch' : 'bt-switch-disable') + '" type="checkbox" ' + ((data.status) ? 'checked' : '') + ' data-on-color="success" id="update-status-user" data-off-color="danger" data-id="' + data.id + '">';
                        return html;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: null,
                    name: 'action',
                    render: function (data, type, full, meta) {
                        let html = '<h4 class="m-0">';
                        if (data.user_role_id == 1) {
                            html += '<a href="' + routes.userProfilePasswordChangeUrl + '" class="text-primary m-1 mt-0 mr-4"><i class="fa fa-key"></i></a>';
                        } else {
                            html += '<a href="javascript:void(0)" class="text-primary m-1 mt-0 mr-4" data-userid="' +
                                data.id + '" id="change-password-user"><i class="fa fa-key"></i></a>';
                        }
                        if (data.user_role_id == 1) {
                            html += '<a href="' + routes.userProfileUrl + '" class="text-info m-1 mt-0 mr-4"><i class="ti-pencil-alt"></i></a>';
                        } else {
                            html += '<a href="javascript:void(0)" class="text-info m-1 mt-0 mr-4" id="edit-user" data-userid="' +
                                data.id +
                                '"><i class="ti-pencil-alt"></i></a>';
                        }
                        if (routes.loginUserId != data.id) {
                            html += '<a href="javascript:void(0)" class="text-danger m-1 mt-0" data-userid="' +
                                data.id + '" id="delete-user"><i class="ti-trash"></i></a>';
                        }
                        html += '</h4>';
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

    // user status update
    $(document).on('switchChange.bootstrapSwitch', updateuserStatusSwitchId, function (e) {
        e.preventDefault();
        $.ajax({
            url: routes.updateuserStatusUrl,
            method: 'POST',
            data: {
                id: $(this).data('id')
            },
            success: function (response) {
                showMessage('success', response.message);
            },
            error: function (error) {
                if (error.responseJSON.error == 'emptyid') {
                    showMessage('error', error.responseJSON.message);
                } else {
                    console.log('Error', error)
                }
            }
        })
    })

    //Modal cancle
    $(modalCancleBtnId).on('click', function (e) {
        e.preventDefault();
        $(formId)[0].reset();
        $(formId).find('#id').val('');
        $(formId).find('#user_profile_id').val('');
        $(modalId).modal('hide');
        $(formErrorSpanClass).text('');
        $(userFormActionInput).val('add');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $(formId).find('select').parents('.form-group').removeClass('has-error');
    })

    // Add Form User
    $(addFormBtnId).on('click', function (e) {
        e.preventDefault();
        $(formId)[0].reset();
        $(formId).find('#id').val('');
        $(formId).find('#user_profile_id').val('');
        $(modalId).find('.modal-title').text('Add New User');
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $(formId).find('select').parents('.form-group').removeClass('has-error');
        $(addOrUpdateBtnId).text('Add User');
        $(userFormActionInput).val('add');
        $(modalId).modal('show');
        $(modalId).modal('handleUpdate')
        $(passwordFormRowId).show();
        $(formId).find('.row').show();
        if ($(passwordFormRowId).find('.password-col').hasClass('col-md-12')) {
            $(passwordFormRowId).find('.password-col').addClass('col-md-6').removeClass('col-md-12')
        }
    })

    // Edit form User
    $(document).on('click', editFormBtnId, function (e) {
        e.preventDefault();
        $(modalId).find('.modal-title').text('Edit User');
        $(addOrUpdateBtnId).text('Edit User');
        $(formErrorSpanClass).text('');
        $(userFormActionInput).val('edit');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $(formId).find('select').parents('.form-group').removeClass('has-error');
        $.each(table.row($(this).parents('tr')).data(), function (key, value) {
            if (key == 'user_role_id') {
                $(formId).find('#role').val(value);
            } else {
                $(formId).find('#' + key).val(value);
            }
        })
        $(modalId).modal('show');
        $(modalId).modal('handleUpdate')
        $(passwordFormRowId).hide();
        $(formId).find('.row').not(passwordFormRowId).show();
    })

    // change password
    $(document).on('click', changePasswordFormBtnId, function (e) {
        e.preventDefault();
        $(modalId).find('.modal-title').text('Change Password');
        $(addOrUpdateBtnId).text('Change Password');
        $(userFormActionInput).val('changepassword');
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $(formId).find('select').parents('.form-group').removeClass('has-error');
        $.each(table.row($(this).parents('tr')).data(), function (key, value) {
            if (key == 'user_role_id') {
                $(formId).find('#role').val(value);
            } else {
                $(formId).find('#' + key).val(value);
            }
        })
        $(modalId).modal('show');
        $(modalId).modal('handleUpdate')
        $(passwordFormRowId).show();
        $(passwordFormRowId).find();
        $(formId).find('.row').not(passwordFormRowId).hide();
        if ($(passwordFormRowId).find('.password-col').hasClass('col-md-6')) {
            $(passwordFormRowId).find('.password-col').addClass('col-md-12').removeClass('col-md-6')
        }
    })

    // Add or Update User
    $(addOrUpdateBtnId).on('click', function (e) {
        e.preventDefault();
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $(formId).find('select').parents('.form-group').removeClass('has-error');
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

    //Delete User
    $(document).on('click', deleteBtnId, function (e) {
        e.preventDefault();
        let userid = $(this).data('userid');
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this user data!",
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
                        id: userid
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
