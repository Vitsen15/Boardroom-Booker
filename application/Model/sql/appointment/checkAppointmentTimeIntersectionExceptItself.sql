select appointment_date.id
from appointment_date
where appointment_date.appointment_id != :app_id and
      appointment_date.is_deleted != true and
      (:start_time between appointment_date.start_time and appointment_date.end_time or
       :end_time between appointment_date.start_time and appointment_date.end_time);