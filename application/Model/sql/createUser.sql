insert into user
set user.username   = :username,
  user.password     = :pass,
  user.access_token = :access_token,
  user.first_name   = :first_name,
  user.last_name    = :last_name;