<div class="container">
    <form action="<?= URL ?>/employee/create/" method="post">
        <label for="first-name">
            <span>
                First name
            </span>
            <input type="text" name="first-name" id="first-name">
        </label>

        <label for="last-name">
            <span>
                Last name
            </span>
            <input type="text" name="last-name" id="last-name">
        </label>

        <label for="email">
            <span>
                Email
            </span>
            <input type="text" name="email" id="email">
        </label>
        <button type="submit">Create</button>
    </form>
    <p class="error">
        <?= isset($error) ? $error : '' ?>
    </p>
</div>