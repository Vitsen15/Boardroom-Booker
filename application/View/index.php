<div class="container">
    <ul class="boardrooms">
        <? if (isset($boardrooms)): ?>
            <? foreach ($boardrooms as $boardroom): ?>
                <li>
                    <a href="<?= URL ?>/calendar/changeBoardroom/<?= $boardroom->id . '/' . $year . '/' . $month ?>">
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
        <a href="<?= URL ?>/calendar/changeCalendarMonth/<?= $year . '/' . $month . '/' . $currentBoardroomID . '/back' ?>"><</a>

        <?= isset($month) ? $month : 'Month is not defined'; ?>
        <?= isset($year) ? $year : 'Year is not defined'; ?>

        <a href="<?= URL ?>/calendar/changeCalendarMonth/<?= $year . '/' . $month . '/' . $currentBoardroomID . '/forward' ?>">></a>
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
                                        <? if ($appointment->is_deleted == 1)
                                            continue;
                                        ?>
                                        <li>
                                            <a href="<?= URL ?>/appointment/index/<?= $appointment->id ?>"
                                               target="popup"
                                               onclick="window.open('<?= URL ?>/appointment/index/<?= $appointment->id ?>','popup','width=600,height=600'); return false;">
                                                <?php if (HOURS_FORMAT === 12): ?>
                                                    <?= (new DateTime($appointment->start_time))->format('h:i A') ?>
                                                    -
                                                    <?= (new DateTime($appointment->end_time))->format('h:i A') ?>
                                                <? elseif (HOURS_FORMAT === 24): ?>
                                                    <?= (new DateTime($appointment->start_time))->format('H:i') ?>
                                                    -
                                                    <?= (new DateTime($appointment->end_time))->format('H:i') ?>
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
    <a href="<?= URL ?>/booking/index/<?= $currentBoardroomID ?>">
        Book it!
    </a>
    <br>
    <a href="<?= URL ?>/employee">
        Employee list
    </a>
</div>