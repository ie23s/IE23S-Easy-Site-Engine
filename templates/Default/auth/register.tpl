<form class="row" style="max-width: 800px;">
    <div class="row">
        <div class="col s12">
            <h4>Register</h4>
        </div>
    </div>
    <div class="red-text center-align error-message"></div>
    <div class="input-field col s12">
        <input id="email" type="email" name="email" class="validate" required aria-required="true">
        <label for="email">Email</label>
        <span class="helper-text" data-error="Email is incorrect"></span>
    </div>
    <div class="input-field col s12 m6">
        <input id="first_name" name="first_name" type="text" class="validate" required aria-required="true">
        <label for="first_name">First Name</label>
    </div>
    <div class="input-field col s12 m6">
        <input id="last_name" name="last_name" type="text" class="validate" required aria-required="true">
        <label for="last_name">Last Name</label>
    </div>
    <div class="input-field col s6">
        <input id="password" name="password" type="password" class="validate" required aria-required="true">
        <label for="password">Password</label>
        <span class="helper-text" data-error="Password must be at least 6 characters long"></span>
    </div>
    <div class="input-field col s6">
        <input id="password1" type="password" class="validate" required aria-required="true">
        <label for="password1">Repeat password</label>
        <span class="helper-text" data-error="Password not match"></span>
    </div>
    <div class="col s12">
        <p>
            <label>
                <input type="checkbox" class="filled-in" checked="checked" required aria-required="true"/>
                <span>Accept</span>
            </label>
        </p>
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
                Register
                <i class="material-icons right">check</i>
            </button>
        </div>
    </div>
</form>

