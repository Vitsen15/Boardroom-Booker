<div class="container">
    <ul class="boardrooms">
        <? if (isset($boardrooms)): ?>
            <? foreach ($boardrooms as $boardroom): ?>
                <li>
                    <a href="<?= URL ?>/home/changeBoardroom/<?= $boardroom->id . '/' . $year . '/' . $month ?>">
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
        <a href="<?= URL ?>/home/changeCalendarMonth/<?= $year . '/' . $month . '/' . $boardroom->id . '/back' ?>"><</a>

        <?= isset($month) ? $month : 'Month is not defined'; ?>
        <?= isset($year) ? $year : 'Year is not defined'; ?>

        <a href="<?= URL ?>/home/changeCalendarMonth/<?= $year . '/' . $month . '/' . $boardroom->id . '/forward' ?>">></a>
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
                    <? foreach ($week as $day): ?>
                        <td>
                            <span>
                                <?= $day['monthDay'] ?>
                            </span>
                            <? if (isset($day['appointments'])): ?>
                                <ul class="appointments">
                                    <? foreach ($day['appointments'] as $appointment): ?>
                                        <li>
                                            <a href="">
                                                <?php if (HOURS_FORMAT === 12): ?>
                                                    <?= (new DateTime($appointment->start_time))->format('h:i A') ?>
                                                    -
                                                    <?= (new DateTime($appointment->end_time))->format('h:i A') ?>
                                                <? elseif (HOURS_FORMAT === 24): ?>
                                                    <?= (new DateTime($appointment->start_time))->format('h:i') ?>
                                                    -
                                                    <?= (new DateTime($appointment->end_time))->format('h:i') ?>
                                                <? endif; ?>

                                            </a>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            <? endif; ?>
                        </td>
                    <? endforeach; ?>
                </tr>
            <? endforeach; ?>
        <? else: ?>
            <?= 'Weeks is not defined' ?>
        <? endif; ?>
    </table>
</div>