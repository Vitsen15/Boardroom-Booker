insert into appointment_date
set appointment_date.appointment_id = :appointment_id,
  appointment_date.employee_id      = :employee_id,
  appointment_date.notes            = :notes,
  appointment_date.date             = :appointment_date,
  appointment_date.start_time       = :appointment_start_time,
  appointment_date.end_time         = :appointment_end_time;
