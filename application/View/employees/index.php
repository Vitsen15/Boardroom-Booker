<div class="container">
    <ul class="employees">
        <? foreach ($employees as $employee): ?>
            <li>
                <a href="<?= URL ?>/employee/update/<?= $employee->id ?>">
                    <?= $employee->first_name . ' ' . $employee->last_name ?>
                </a> ----
                <a class="delete-employee" href="<?= URL ?>/employee/delete/<?= $employee->id ?>">
                    Remove
                </a> ----
                <a href="<?= URL ?>/employee/update/<?= $employee->id ?>">
                    Edit
                </a>
            </li>
        <? endforeach; ?>
    </ul>
    <a href="<?= URL ?>/employee/create">
        Add e new employee
    </a>
</div>