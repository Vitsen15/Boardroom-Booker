select
  user.username,
  user.password,
  user.access_token
from user
where user.username = :username and user.password = :pass;