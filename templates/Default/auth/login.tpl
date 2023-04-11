<form class="row" style="max-width: 800px;">
    <div class="row">
        <div class="col s12">
            <h4>Login</h4>
        </div>
    </div>
    <div class="red-text center-align error-message"></div>
    <div class="input-field col s12">
        <input id="email" type="text" name="email" class="validate" required aria-required="true">
        <label for="email">Email</label>
    </div>
    <div class="input-field col s12">
        <input id="password" type="password" name="password" class="validate" required aria-required="true">
        <label for="password">Password</label>
    </div>
    <div class="row margin-0">
        <div class="input-field col s6">
            <button class="btn waves-effect waves-block waves-light center-block cancel" name="cancel" type="button"
                    style="display: none">
                Cancel
                <i class="material-icons right">cancel</i>
            </button>
        </div>
        <div class="input-field col s6">
            <button class="btn waves-effect waves-block waves-light center-block submit" type="submit" name="create">
                Login
                <i class="material-icons right">check</i>
            </button>
        </div>
    </div>
</form>

