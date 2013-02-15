#!/bin/bash
i=1
while true
do
	#echo "Welcome $c times..."
	#countNameservers=$(python startPHPSever.py)
	#echo "${countNameservers}"
	countNameservers=$(php startServer.php)
	echo "${countNameservers} --- ${i}"
	i=`expr $i + 1`
	sleep 1
done