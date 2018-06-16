update appointment_date
set appointment_date.start_time = :start_time,
  appointment_date.end_time     = :end_time,
  appointment_date.notes        = :notes,
  appointment_date.employee_id  = :employee_id
where appointment_date.id = :id;