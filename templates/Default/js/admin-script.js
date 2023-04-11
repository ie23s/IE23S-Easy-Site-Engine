let IE23S_A;
$(function () {
    IE23S_A = {
        modal: M.Modal.init(document.querySelector('#adm-modal-product'),
            {dismissible: false}),
        modalElement: $('#adm-modal-product'),
        modalForm: $('#adm-modal-product').find("form"),
        productsTable: $('#adm-product-list'),
        ajaxSearching: null,
        dropzone_local: null,
        isEdit: false,
        block: function () {
            this.modalElement.find(".progress").show();
            this.modalForm.find('input').prop('disabled', true);
            this.modalForm.find('button').prop('disabled', true);
            this.modalForm.find('textarea').prop('disabled', true);
        },
        unblock: function () {

            $('#adm-modal-product').find(".progress").hide();
            this.modalForm.find('input').prop('disabled', false);
            this.modalForm.find('button').prop('disabled', false);
            this.modalForm.find('textarea').prop('disabled', false);
        },
        openForm: function () {
            this.modalForm.trigger("reset");
            this.modalElement.find(".error-message").hide();
            this.unblock()
            this.modal.open();
        },
        successAdded: function () {
            IE23S_A.modal.close();
            M.toast({html: 'New product was added!'});
            IE23S_A.runSearch('');
        },
        successEdited: function () {
            IE23S_A.modal.close();
            M.toast({html: 'New product was edited!'});
            IE23S_A.runSearch('');
        },
        failed: function (jqXHR) {

            let errorMessage = 'There was a problem with the request, please try again';
            if (jqXHR.responseJSON && jqXHR.responseJSON.text) {
                errorMessage = jqXHR.responseJSON.text;
            }

            IE23S_A.modalElement.find(".error-message").show();
            IE23S_A.modalElement.find(".error-message").html(errorMessage);

            IE23S_A.unblock();
        },
        successSearch: function (result) {

            IE23S_A.isSearching = false;

            $.each(result, function (key, value) {
                let tr = $('#adm-product-template').find('tbody').html();

                let replace = {
                    id: value.id,
                    display_name: value.display_name,
                    category: value.category_name,
                    cost: value.cost,
                    description: value.description,
                    art: value.art,
                    code: value.code,
                    sold: value.sold,
                    balance: value.balance
                }
                Object.keys(replace).forEach(function (key) {

                    tr = tr.replaceAll('\{' + key + '\}', replace[key]);

                });
                IE23S_A.productsTable.find('tbody').append(tr);
            });
            IE23S_A.initEdit($('.product-edit'));

            IE23S_A.initRemove($('.product-remove'));
            IE23S_A.productsTable.find('.preloader').hide();
        },
        runSearch: function (q) {
            if (this.ajaxSearching != null)
                this.ajaxSearching.abort();
            this.productsTable.find('.preloader').show();
            this.productsTable.find('tbody').html('');

            this.ajaxSearching = $.ajax({
                type: 'GET',
                url: '/api/products',
                dataType: 'json',
                data: 'q=' + q,
                success: this.successSearch,
                error: function () {
                    IE23S_A.isSearching = false;
                }

            });

        },
        search: function (e) {
            e.on("input", function () {
                IE23S_A.runSearch($(this).val());
            });
        },
        onAdd: function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/api/product',
                dataType: 'json',
                beforeSend: () => this.block(),
                data: this.modalForm.serialize(),
                success: this.successAdded,
                error: this.failed

            });
        },
        onEdit: function (event) {
            event.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '/api/product',
                dataType: 'json',
                beforeSend: () => this.block(),
                data: this.modalForm.serialize(),
                success: this.successEdited,
                error: this.failed

            });
        },
        productEditFormLoaded: function (result) {
            IE23S_A.unblock();
            IE23S_A.modalForm.find(':input').each(function () {
                let name = $(this).attr('name');
                if (name && result[name]) {
                    $(this).val(result[name]);
                }
            })
            M.updateTextFields();
            IE23S_A.createDropZone(IE23S_A.modalForm.find('.photos'))
            $('select').formSelect();
        },
        dropzone_array: [],
        updateFilesInput: (input) => {
            input.val(JSON.stringify(IE23S_A.dropzone_array));
        },
        createDropZone: (input) => {

            try {
                IE23S_A.dropzone_local.destroy();
                $("div#mydropzone").html('');
                $("div#mydropzone").removeClass('dz-started');
            } catch (e) {
            }
            IE23S_A.dropzone_local = new Dropzone("div#mydropzone", {
                method: "POST",
                url: "/api/uploadfiles",
                addRemoveLinks: true,
                init: function () {
                    this.on("success", function (file, serverResponse) {
                        IE23S_A.dropzone_array = $.parseJSON(input.val());
                        if (IE23S_A.dropzone_array === '')
                            IE23S_A.dropzone_array = [];
                        // Called after the file successfully uploaded.
                        serverResponse = $.parseJSON(serverResponse);
                        IE23S_A.dropzone_array.push(serverResponse.data.filename);
                        file.serverFilename = serverResponse.data.filename;
                        this.createThumbnailFromUrl(file, '/uploads/' + serverResponse.data.filename + '.jpg');
                        IE23S_A.updateFilesInput(input)
                    });

                    this.on("removedfile", file => {
                        IE23S_A.dropzone_array = jQuery.grep(IE23S_A.dropzone_array, function (value) {
                            return value !== file.serverFilename;
                        });
                        IE23S_A.updateFilesInput(input)
                    });


                }
            });
            $.each($.parseJSON(input.val()), function (key, value) {
                let mockFile = {name: "Photo " + key, size: 0, serverFilename: value};
                IE23S_A.dropzone_local.displayExistingFile(mockFile, '/uploads/' + value + '.jpg');
            });
        },

        editLoad: function (id) {
            $.ajax({
                type: 'GET',
                url: '/api/product',
                dataType: 'json',
                beforeSend: () => this.block(),
                data: 'id=' + id,
                success: this.productEditFormLoaded,
                error: this.failed

            });
        },
        productRemove: function (id) {
            $.ajax({
                type: 'DELETE',
                url: '/api/product',
                dataType: 'json',
                beforeSend: () => this.block(),
                data: 'id=' + id,
                success: function () {
                    M.toast({html: 'Product removed!'});
                    IE23S_A.runSearch('');
                },
                error: function (e) {
                    let errorMessage = 'There was a problem with the request, please try again';
                    if (e.responseJSON && e.responseJSON.text) {
                        errorMessage = e.responseJSON.text;
                    }
                    M.toast(errorMessage)
                }

            });
        },
        change_button: function (type) {
            if (type === 'create') {
                this.modalForm.find('button[type="submit"]').html('Create');
            } else {
                this.modalForm.find('button[type="submit"]').html('Edit');
            }
        },
        product_add: function () {
            this.isEdit = false;
            this.change_button('create');
            this.openForm();
            this.modalElement.find('button[name="cancel"]').click(() => this.modal.close());
        },
        productEditForm: function (e) {
            this.isEdit = true;
            this.change_button('edit');
            this.openForm();
            this.block();
            this.editLoad(e);
            this.modalElement.find('button[name="cancel"]').click(() => this.modal.close());
        },
        initCreate: function (e) {
            e.click(() => IE23S_A.product_add());
            this.modalForm.submit(function (e) {
                e.preventDefault();

                if (IE23S_A.isEdit) {
                    IE23S_A.onEdit(e)
                } else {

                    IE23S_A.onAdd(e);
                }
            });
        },
        initEdit: function (e) {
            e.click(function () {
                IE23S_A.productEditForm($(this).attr('data-id'));
            });
        },
        initRemove: function (e) {
            e.click(function () {
                IE23S_A.productRemove($(this).attr('data-id'));
            });
        }
    }
    IE23S_A.initCreate($('.create-product'));
    IE23S_A.initEdit($('.product-edit'));
    IE23S_A.initRemove($('.product-remove'));
    IE23S_A.search($('#adm-products-search').find('input'));
});
