<div class="container">
    <h1>
        Boardroom Booker
    </h1>
    <form action="<?= URL ?>/booking">
        <fieldset>
            <legend>
                1. Booked for.
            </legend>
            <select name="employee" id="employees">
                <? if (isset($employees)): ?>
                    <? foreach ($employees as $employee): ?>
                        <option value="<?= $employee->id ?>">
                            <?= "{$employee->first_name} {$employee->last_name}" ?>
                        </option>
                    <? endforeach; ?>
                <? else: ?>
                    <?= 'No employees defined' ?>
                <? endif; ?>
            </select>
        </fieldset>

        <fieldset>
            <legend>
                2. I would like book this meeting.
            </legend>

            <select name="month" id="month">
                <? if (isset($months)): ?>
                    <? foreach ($months as $monthNumber => $monthName): ?>
                        <option value="<?= $monthNumber ?>">
                            <?= $monthName ?>
                        </option>
                    <? endforeach; ?>
                <? endif; ?>
            </select>

            <select name="day" id="day">
                <? if (isset($days)): ?>
                    <? foreach ($days as $day): ?>
                        <option value="<?= $day ?>">
                            <?= $day ?>
                        </option>
                    <? endforeach; ?>
                <? endif; ?>
            </select>

            <select name="year" id="year">
                <? if (isset($years)): ?>
                    <? foreach ($years as $year): ?>
                        <option value="<?= $year ?>">
                            <?= $year ?>
                        </option>
                    <? endforeach; ?>
                <? endif; ?>
            </select>
        </fieldset>

        <fieldset>
            <legend>
                3. Specify what the time and end of the meeting (This will be what people see whet they click on an
                event link.)
            </legend>

            <select name="hour" id="hour">
                <? if (isset($time)): ?>
                    <? foreach ($time['hours'] as $hour): ?>
                        <option value="<?= $hour ?>">
                            <?= $hour ?>
                        </option>
                    <? endforeach; ?>
                <? endif; ?>
            </select>

            <select name="minute" id="hour">
                <? if (isset($time)): ?>
                    <? foreach ($time['minutes'] as $minute): ?>
                        <option value="<?= $minute ?>">
                            <?= str_pad($minute, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <? endforeach; ?>
                <? endif; ?>
            </select>

            <select name="time-format" id="time-format">
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>
                4. Enter specifics for the meeting. (This will be what people see whet they click on an event link.)
            </legend>
            <textarea name="notes" id="notes" cols="30" rows="10"></textarea>
        </fieldset>

        <fieldset>
            <legend>
                6. It is going to be a recurring event?
            </legend>

            <label for="recurring-yes">
                <span>
                    Yes
                </span>
                <input type="radio" id="recurring-yes" name="recurring" value="true">
            </label>
            <label for="recurring-no">
                <span>
                    No
                </span>
                <input type="radio" id="recurring-no" name="recurring" value="false">
            </label>
        </fieldset>

        <fieldset>
            <legend>
                7. If it is recurring specify weekly, bi-weekly, monthly
            </legend>

            <label for="recurring-weekly">
            <span>
                weekly
            </span>
                <input type="radio" id="recurring-weekly" name="recurring-type" value="weekly">
            </label>
            <label for="recurring-bi-weekly">
                <span>
                    bi-weekly
                </span>
                <input type="radio" id="recurring-bi-weekly" name="recurring-type" value="bi-weekly">
            </label>
            <label for="recurring-monthly">
                <span>
                    bi-weekly
                </span>
                <input type="radio" id="recurring-monthly" name="recurring-type" value="monthly">
            </label>
        </fieldset>
    </form>
    <p class="error">
        <? if (isset($error)): ?>
            <?= $error; ?>
        <? endif; ?>
    </p>
</div>