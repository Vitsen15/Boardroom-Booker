insert into appointment
set appointment.boardroom_id    = :boardroom_id,
  appointment.recurring_type_id = :recurring_type_id;
select LAST_INSERT_ID() as appointment_id;