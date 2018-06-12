<div class="container">
    <ul class="boardrooms">
        <? if (isset($boardrooms)): ?>
            <? foreach ($boardrooms as $boardroom): ?>
                <li>
                    <a href="<?= URL ?>/home/changeBoardroom/<?= $boardroom->id ?>">
                        <?= $boardroom->name ?>
                    </a>
                </li>
            <? endforeach; ?>
        <? else: ?>
            <?= 'No boardrooms defined' ?>
        <? endif; ?>
    </ul>
    <h1>Boardroom Booker</h1>
    <h2><?= isset($boardroomName) ? $boardroomName : 'Boardroom is not defined!' ?></h2>
    <h3 class="calendar-pagination">
        <a href="<?= URL ?>/home/changeCalendarMonth/<?= isset($year) && isset($month) ? $year . '/' . $month . '/back' : '#' ?>"><</a>

        <?= isset($month) ? $month : 'Month is not defined'; ?>
        <?= isset($year) ? $year : 'Year is not defined'; ?>

        <a href="<?= URL ?>/home/changeCalendarMonth/<?= isset($year) && isset($month) ? $year . '/' . $month . '/forward' : '#' ?>">></a>
    </h3>

    <table class="calendar">
        <thead class="week-days-names">
        <? if (isset($weekDayNames)): ?>
            <? foreach ($weekDayNames as $weekDayName): ?>
                <th>
                    <?= $weekDayName ?>
                </th>
            <? endforeach; ?>
        <? else: ?>
            <?= 'Week days is not defined' ?>
        <? endif; ?>
        </thead>
        <? if (isset($weeks)): ?>
            <? foreach ($weeks as $week): ?>
                <tr class="week">
                    <?= html_entity_decode($week); ?>

                    <!--                        --><? // foreach ($weekDays as $weekDay): ?>
                    <!--                        --><? //= $weekDay ?>
                    <!--                    --><? // endforeach; ?>
                </tr>
            <? endforeach; ?>
        <? else: ?>
            <?= 'Weeks is not defined' ?>
        <? endif; ?>
        <!--        --><? //= $weeks ?>
    </table>
</div>