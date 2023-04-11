let IE23S_REGISTER;

IE23S_REGISTER = {
    modal: null,
    modalContainer: null,
    loadForm: function () {

        IE23S_REGISTER.modalContainer.find('.content').html('');
        $('.progress').show();
        this.modal.open();
        $.ajax({
            type: 'GET',
            url: '/register/tonly',
            success: this.loadedForm,
            error: this.loadForm
        });
    },
    validPassword: () => {
        let p = IE23S_REGISTER.modalContainer.find('.content').find('#password');
        if (p.val().length < 6) {
            p.removeClass("valid").addClass("invalid");
        } else {
            p.removeClass("invalid").addClass("valid");
        }
    },
    validPassword1: () => {
        let p1 = IE23S_REGISTER.modalContainer.find('.content').find('#password1');
        if (p1.val() ===
            IE23S_REGISTER.modalContainer.find('.content').find('#password').val()) {
            p1.removeClass("invalid").addClass("valid");
        } else {
            p1.removeClass("valid").addClass("invalid");
        }
    },
    loadedForm: function (res) {
        $('.progress').hide();
        IE23S_REGISTER.modalContainer.find('.content').html(res);
        IE23S_REGISTER.modalContainer.find('.content').find('#password')
            .on("focusout", () => {
                IE23S_REGISTER.validPassword();
                IE23S_REGISTER.validPassword1();
            });
        IE23S_REGISTER.modalContainer.find('.content').find('#password1')
            .on("focusout", IE23S_REGISTER.validPassword1);
        IE23S_REGISTER.modalContainer.find('.content').find('.cancel').show();
        IE23S_REGISTER.modalContainer.find('.content').find('.cancel').on('click', () => IE23S_REGISTER.modal.close());

        IE23S_REGISTER.form = IE23S_LOGIN.modalContainer.find('.content').find('form');
        IE23S_REGISTER.form.submit(function (e) {
            e.preventDefault();
            IE23S_REGISTER.register();
        });
    },
    modalInit: function (e) {
        this.modal = M.Modal.init(document.querySelector(e));
        this.modalContainer = $(e);
    },
    registerInit: function (e) {
        $(e).on("click", function (event) {

            event.preventDefault();
            IE23S_REGISTER.loadForm();
        })
    },
    block: function () {
        $('body').find(".progress").show();
        this.form.find('input').prop('disabled', true);
        this.form.find('button').prop('disabled', true);
        this.form.find('textarea').prop('disabled', true);
    },
    unblock: function () {

        $('body').find(".progress").hide();
        this.form.find('input').prop('disabled', false);
        this.form.find('button').prop('disabled', false);
        this.form.find('textarea').prop('disabled', false);
    },
    successAuth: function () {
        IE23S_REGISTER.unblock();
        $('.unAuth').hide();
        $('.authOnly').show();
        IE23S_REGISTER.modal.close();
    },
    failedAuth: function (jqXHR) {

        let errorMessage = 'There was a problem with the request, please try again';

        if (jqXHR.responseJSON && jqXHR.responseJSON.text) {
            errorMessage = jqXHR.responseJSON.text;
        }


        IE23S_REGISTER.modalContainer.find(".error-message").show();
        IE23S_REGISTER.modalContainer.find(".error-message").html(errorMessage);
        IE23S_REGISTER.unblock();
    },
    register: function () {
        $.ajax({
            type: 'POST',
            url: '/api/register',
            dataType: 'json',
            beforeSend: () => IE23S_REGISTER.block(),
            data: IE23S_REGISTER.form.serialize(),
            success: IE23S_REGISTER.successAuth,
            error: IE23S_REGISTER.failedAuth

        });
    }
}

IE23S_LOGIN = {
    modal: null,
    modalContainer: null,
    currentForm: null,
    loadForm: function () {

        IE23S_LOGIN.modalContainer.find('.content').html('');
        $('.progress').show();
        this.modal.open();
        $.ajax({
            type: 'GET',
            url: '/login/tonly',
            success: this.loadedForm,
            error: this.loadForm
        });
    },
    loadedForm: function (res) {
        $('.progress').hide();
        IE23S_LOGIN.modalContainer.find('.content').html(res);
        IE23S_LOGIN.modalContainer.find('.content').find('.cancel').show();
        IE23S_LOGIN.modalContainer.find('.content').find('.cancel').on('click', () => IE23S_LOGIN.modal.close());
        IE23S_LOGIN.form = IE23S_LOGIN.modalContainer.find('.content').find('form');
        IE23S_LOGIN.form.submit(function (e) {
            e.preventDefault();
            IE23S_LOGIN.auth();
        });

    },
    block: function () {
        $('body').find(".progress").show();
        this.form.find('input').prop('disabled', true);
        this.form.find('button').prop('disabled', true);
        this.form.find('textarea').prop('disabled', true);
    },
    unblock: function () {

        $('body').find(".progress").hide();
        this.form.find('input').prop('disabled', false);
        this.form.find('button').prop('disabled', false);
        this.form.find('textarea').prop('disabled', false);
    },
    successAuth: function () {
        IE23S_LOGIN.unblock();
        $('.unAuth').hide();
        $('.authOnly').show();
        IE23S_LOGIN.modal.close();
    },
    failedAuth: function (jqXHR) {

        let errorMessage = 'There was a problem with the request, please try again';

        if (jqXHR.responseJSON && jqXHR.responseJSON.text) {
            errorMessage = jqXHR.responseJSON.text;
        }


        IE23S_LOGIN.modalContainer.find(".error-message").show();
        IE23S_LOGIN.modalContainer.find(".error-message").html(errorMessage);
        IE23S_LOGIN.unblock();
    },
    auth: function () {
        $.ajax({
            type: 'POST',
            url: '/api/auth',
            dataType: 'json',
            beforeSend: () => IE23S_LOGIN.block(),
            data: IE23S_LOGIN.form.serialize(),
            success: IE23S_LOGIN.successAuth,
            error: IE23S_LOGIN.failedAuth

        });
    },
    modalInit: function (e) {
        this.modal = M.Modal.init(document.querySelector(e));
        this.modalContainer = $(e);
    },
    loginInit: function (e) {
        $(e).on("click", function (event) {

            event.preventDefault();
            IE23S_LOGIN.loadForm();
        })
    },
}

let IE23S_LOAD = {
    first: function () {
        $('select').formSelect();
        $('.sidenav').sidenav();

        M.updateTextFields();

        IE23S_REGISTER.registerInit('.register');
        IE23S_REGISTER.modalInit('#auth-div');
        IE23S_LOGIN.loginInit('.login');
        IE23S_LOGIN.modalInit('#auth-div');
        this.loadedPart();
    },
    carousel: function () {
        let carousel = $('.carousel');
        carousel.carousel({
            fullWidth: true,
            numVisible: 0

        });
        $('.previous-image').click(() => carousel.carousel('prev'));
        $('.next-image').click(() => carousel.carousel('next'));
    },
    loadedPart: function () {
        this.carousel();
        $('.dropdown-trigger').dropdown();

        let instance = M.Tabs.init(document.querySelectorAll('.tabs'), {
            swipeable: false
        });
        $('.progress').hide();

        $('a[data-reload="false"]').click(function(e) {
                e.preventDefault();
                $(this).attr('data-reload', true);
                IE23S_LOAD.loadPart( $(this).attr('href'));
        })
    },
    insertPart: function (r) {
        $('#loaded-content').html(r.content);
        IE23S_LOAD.loadedPart();
        document.title=r.title;
    },
    changeURL: function (url)
    {
        window.history.pushState("data","Title",url);
    },
    loadPart: function (href) {

        IE23S_LOAD.changeURL(href);
        if(href === '/')
            href = '';
        $('.progress').show();
        $.ajax({
            type: 'GET',
            url: href + '/noreload',
            success: IE23S_LOAD.insertPart,
        });
    }
}

$(document).ready(function () {

    IE23S_LOAD.first();

});
Dropzone.autoDiscover = false;
