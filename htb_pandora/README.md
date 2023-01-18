# Hack the Box - Pandora

## Information Gathering

> nmap -sC -sV 10.10.11.136

"""
Starting Nmap 7.91 ( https://nmap.org ) at 2022-02-11 20:25 EST
Nmap scan report for 10.10.11.136
Host is up (0.083s latency).
Not shown: 998 closed ports
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.2p1 Ubuntu 4ubuntu0.3 (Ubuntu Linux; protocol 2.0)
80/tcp open  http    Apache httpd 2.4.41 ((Ubuntu))
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 10.47 seconds

"""

### Run another full port nmap scan

> nmap -sC -sV -p- 10.10.11.136

"Nothing New"

### Run a UDP scan

> nmap -sU -sC -sV 10.10.11.136

"""
PORT    STATE SERVICE VERSION
161/udp open  snmp    SNMPv1 server; net-snmp SNMPv3 server (public)
| snmp-info: 
|   enterprise: net-snmp
|   engineIDFormat: unknown
|   engineIDData: 48fa95537765c36000000000
|   snmpEngineBoots: 30
|_  snmpEngineTime: 43m46s
| snmp-interfaces: 
|   lo
|     IP address: 127.0.0.1  Netmask: 255.0.0.0
|     Type: softwareLoopback  Speed: 10 Mbps
|   VMware VMXNET3 Ethernet Controller
|     IP address: 10.10.11.136  Netmask: 255.255.254.0
|     MAC address: 00:50:56:b9:98:e2 (VMware)
|_    Type: ethernetCsmacd  Speed: 4 Gbps
| snmp-netstat: 
|   TCP  0.0.0.0:22           0.0.0.0:0
|   TCP  10.10.11.136:34276   1.1.1.1:53
|   TCP  127.0.0.1:3306       0.0.0.0:0
|   TCP  127.0.0.53:53        0.0.0.0:0
|   UDP  0.0.0.0:161          *:*
|_  UDP  127.0.0.53:53        *:*
| snmp-processes: 

| snmp-sysdescr: Linux pandora 5.4.0-91-generic #102-Ubuntu SMP Fri Nov 5 16:31:28 UTC 2021 x86_64
|_  System uptime: 43m46.15s (262615 timeticks)
|_snmp-win32-software: 
Service Info: Host: pandora
"""