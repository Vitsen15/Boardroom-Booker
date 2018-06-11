<div class="content">
    <h1>Please register</h1>
    <form name="register" action="<?= URL ?>/register" method="post">
        <label for="username">
            <span>Username</span>
            <input id="username" name="username" type="text">
        </label>
        <label for="first-name">
            <span>First name</span>
            <input id="first-name" name="first-name" type="text">
        </label>
        <label for="last-name">
            <span>Last name</span>
            <input id="last-name" name="last-name" type="text">
        </label>
        <label for="password">
            <span>Password</span>
            <input id="password" name="password" type="password">
        </label>
        <label for="repeat-password">
            <span>Repeat password</span>
            <input id="repeat-password" name="repeat-password" type="password">
        </label>
        <button type="submit">Register</button>
    </form>
    <p class="error">
        <? if (isset($errorMessage)): ?>
            <?= $errorMessage; ?>
            <? $errorMessage = null; ?>
        <? endif; ?>
    </p>
</div>