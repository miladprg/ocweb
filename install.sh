#!/bin/bash

if [ -n "$1" ]; then
  if [ "$1" = "--reset" ]; then
    echo "$1"
    read -p "Enter new password: " PASSWORD
    PASSWORD=$(echo -n "$PASSWORD" | sha256sum | cut -d ' ' -f1)
    echo "UPDATE ADMINS SET password='$PASSWORD' WHERE ADMINS.type like '%administrator%';" | sqlite3 *.db
    printf "\nIt's Done!"
    exit
  fi

  if [ "$1" = "--uninstall" ]; then
    rm -rf /var/www/html/OCWeb_DATABASE /var/www/html/ocweb /var/www/html/oc*
    cp /etc/sudoers.backup /etc/sudoers
    read -p "Uninstall package dependecies[y/n]: " uninstall_packages
    if [ "$uninstall_packages" = "y" ]; then
      apt autoremove apache2 php php-sqlite3 sqlite3 uuid-runtime rsync sshpass php-ssh2 gcc make autoconf libc-dev pkg-config libssh2-1-dev -y
    fi
    exit
  fi
fi

if [ ! -d "/var/www/html/OCWeb_DATABASE" ]; then
  apt update
  apt install apache2 php php-sqlite3 sqlite3 uuid-runtime zip unzip rsync sshpass php-ssh2 gcc make autoconf libc-dev pkg-config libssh2-1-dev -y
  
  cd /var/www/html
  touch index.php
  wget -O ocweb.zip https://raw.githubusercontent.com/miladprg/ocweb/master/ocweb.zip
  unzip -o ocweb.zip
  rm ocweb.zip
  mkdir -p OCWeb_DATABASE
  mkdir -p OCWeb_DATABASE/backup
  cd OCWeb_DATABASE
  wget -O schema.sql https://raw.githubusercontent.com/miladprg/ocweb/master/schema.sql
  wget -O manage.sh https://raw.githubusercontent.com/miladprg/ocweb/master/manage.sh
  touch index.php
  touch backup/index.php
  
  sed -i '/^#.*config-per-group/ s/^#//' /etc/ocserv/ocserv.conf
  sed -i '/^config-per-user/ s/^#*/#/' /etc/ocserv/ocserv.conf
  sed -i '/^.*config-per-user/ s|=.*$|= /etc/ocserv/group|' /etc/ocserv/ocserv.conf
  sed -i '/^.*config-per-group/ s|=.*$|= /etc/ocserv/group|' /etc/ocserv/ocserv.conf
  
  cp /etc/sudoers /etc/sudoers.backup
  
  if ! grep -q "www-data ALL=(ALL:ALL) NOPASSWD: ALL" /etc/sudoers; then
      sudo sed -i '/^root/a\www-data ALL=(ALL:ALL) NOPASSWD: ALL' /etc/sudoers
  fi

  DATABASE_NAME="$(uuidgen).db"

  sqlite3 $DATABASE_NAME ""

  echo ".read schema.sql" | sqlite3 $DATABASE_NAME

  printf "\nNow enter detail of administrator of system.\n\n"

  read -p "Enter your Fullname: " FULLNAME
  read -p "Enter your Username: " USERNAME
  read -p "Enter your Password: " PASSWORD
  CURRENT_TIME=$(date +"%s")
  PASSWORD=$(echo -n "$PASSWORD" | sha256sum | cut -d ' ' -f1)
  echo "INSERT INTO ADMINS VALUES (null, '$FULLNAME', '$USERNAME', '$PASSWORD', 'administrator', '$CURRENT_TIME');" | sqlite3 $DATABASE_NAME

  chown -R :www-data /var/www/html/OCWeb_DATABASE
  chmod -R 775 /var/www/html/OCWeb_DATABASE
  chown -R :www-data /etc/radcli/
  chmod -R 775 /etc/radcli/
  chown -R :www-data /etc/ocserv/
  chmod -R 775 /etc/ocserv/

  sed -i "0,/define(\"DATABASE\",.*/s//define(\"DATABASE\", \"\/var\/www\/html\/OCWeb_DATABASE\/$DATABASE_NAME\");/" /var/www/html/ocweb/assets/utility/functions.php   
else
  cd /var/www/html
  wget -O ocweb.zip https://raw.githubusercontent.com/miladprg/ocweb/master/ocweb.zip
  unzip -o ocweb.zip
  rm ocweb.zip
  DATABASE_NAME=$(ls /var/www/html/OCWeb_DATABASE/*db)
  DATABASE_NAME=$(basename $DATABASE_NAME)
  sed -i "0,/define(\"DATABASE\",.*/s//define(\"DATABASE\", \"\/var\/www\/html\/OCWeb_DATABASE\/$DATABASE_NAME\");/" /var/www/html/ocweb/assets/utility/functions.php 
fi

echo "#!/bin/bash

journalctl -fu ocserv | while read line; do
    if echo \"\$line\" | grep -q \"user logged in\"; then
       echo \"\$(date +%s) \$(echo \$line | grep -oP 'main\[\K[^]]+')\" >> /etc/ocserv/logs.log
    fi
done" > /etc/ocserv/logs.sh

new_cron="@reboot bash /etc/ocserv/logs.sh &"

if ! (crontab -l | grep -qF "$new_cron"); then
    (crontab -l ; printf "\n#Don't remove below line\n" ; echo "$new_cron") | crontab -
fi

printf "\n\nWeb Address: <YOUR_VPS_IP>/ocweb.\n"
printf "\nIf you want reset administrator password just run:\n"
echo "bash <(curl -s https://raw.githubusercontent.com/miladprg/ocweb/master/install.sh) \"--reset\""
printf "\n\n"
printf "\nIf you want uninstall panel run:\n"
echo "bash <(curl -s https://raw.githubusercontent.com/miladprg/ocweb/master/install.sh) \"--uninstall\""
printf "\n"
cd ~
service apache2 restart
