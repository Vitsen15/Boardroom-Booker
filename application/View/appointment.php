<div class="container">
    <h1>B.B Details</h1>
    <p class="error">
        <?= isset($error) ? $error : '' ?>
    </p>
    <form action="<?= URL ?>/appointment/change/" method="post">
        <fieldset name="when">
            <legend>When</legend>

            <input type="text" name="start-time" value="<?= isset($startTime) ? $startTime : '' ?>">
            -
            <input type="text" name="end-time" value="<?= isset($endTime) ? $endTime : '' ?>">
        </fieldset>

        <label for="notes">
            <span>Notes</span>

            <input type="text" name="notes" value="<?= isset($notes) ? $notes : '' ?>" id="notes">
        </label>

        <br>

        <label for="employee">
            <span>
                Who
            </span>

            <select name="employee-id" id="employee">
                <? foreach ($employees as $employee): ?>
                    <option value="<?= $employee->id ?>">
                        <?= $employee->first_name . ' ' . $employee->last_name ?>
                    </option>
                <? endforeach; ?>
            </select>
        </label>

        <p>
            <span>Submitted</span>
            <span>
                <?= isset($creationTime) ? $creationTime : 'Null' ?>
            </span>
        </p>

        <? if (isset($recurring)): ?>
            <label for="apply-for-all">
            <span>
                Apply for all occurrences?
            </span>
                <input type="checkbox" name="apply-for-all" id="apply-for-all">
            </label>
        <? endif; ?>

        <br>

        <input type="submit" name="action" value="update">
        <input type="submit" name="action" value="delete">
    </form>
</div>