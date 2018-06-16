update appointment_date
set appointment_date.is_deleted = true,
  appointment_date.deleted_at = now()
where appointment_date.id = :id