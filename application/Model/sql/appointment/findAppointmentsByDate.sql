select
  appointment_date.id,
  appointment_date.start_time,
  appointment_date.end_time,
  appointment_date.is_deleted
from appointment_date
where appointment_date.date = :app_date;