select appointment_date.id
from appointment_date
where appointment_date.start_time between :start_time and :end_time or
      appointment_date.end_time between :start_time and :end_time;