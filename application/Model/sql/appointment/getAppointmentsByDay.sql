select
  appointment_date.id,
  appointment_date.start_time,
  appointment_date.end_time,
  appointment_date.is_deleted,
  appointment.boardroom_id
from appointment_date
  left join appointment on appointment_date.appointment_id = appointment.id
where appointment_date.date = :app_date and appointment.boardroom_id = :boardroom_id;