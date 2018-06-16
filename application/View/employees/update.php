<div class="container">
    <form action="<?= URL ?>/employee/update/" method="post">
        <label for="first-name">
            <span>
                First name
            </span>
            <input type="text" name="first-name" id="first-name" value="<?= $employee->first_name ?>">
        </label>

        <label for="last-name">
            <span>
                Last name
            </span>
            <input type="text" name="last-name" id="last-name" value="<?= $employee->last_name ?>">
        </label>

        <label for="email">
            <span>
                Email
            </span>
            <input type="text" name="email" id="email" value="<?= $employee->email ?>">
        </label>
        <button type="submit">Update</button>
    </form>
    <p class="error">
        <?= isset($error) ? $error : '' ?>
    </p>
</div>