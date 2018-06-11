<div class="content">
    <h1>Please log in</h1>
    <form action="<?= URL ?>/login" method="post">
        <label for="username">
            <span>Username</span>
            <input id="username" name="username" type="text">
        </label>
        <label for="password">
            <span>Password</span>
            <input id="password" name="password" type="password">
        </label>
        <button type="submit">Login</button>
    </form>
    <p>
        <? if (isset($errorMessage)): ?>
            <?= $errorMessage; ?>
        <? endif; ?>
    </p>
</div>