#!/bin/bash
OCPASSWD_PATH="/etc/ocserv/"
OCPASSWD="${OCPASSWD_PATH}ocpasswd"

add_user(){
	echo -e "$2\n$2" | ocpasswd -c $OCPASSWD $1
}

delete_user(){
        sed -i "/$1/d" /etc/ocserv/logs.log
        ocpasswd -d $1
}

change_user_group(){
	sed -i "s/$1:.*:/$1:$2:/g" $OCPASSWD
}

lock_user() {
	ocpasswd -l $1
}

unlock_user() {
        ocpasswd -u $1
}

create_group() {
	mkdir -p /etc/ocserv/group
	file_path="/etc/ocserv/group/g$1"
	if [ ! -e "$file_path" ]; then
		echo "max-same-clients = $1" > $file_path
	fi
}

flush_ocpasswd() {
	rm $OCPASSWD
	touch $OCPASSWD
}

flush_groups() {
  rm -rf /etc/ocserv/group
}

disconnect_user() {
  sudo occtl disconnect user $1
}

$@
