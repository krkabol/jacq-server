#!/bin/bash

cd ~/test
for i in /home/herbarium/source/*jpg
do
	NEWFNAME=`basename -s .jpg "$i"`.tif
	convert "$i" "${NEWFNAME}"
	chmod 774 "${NEWFNAME}"  # rename script depends on that
done
