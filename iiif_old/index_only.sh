#!/usr/bin/bash

THUMBS_DIR="/home/herbarium/thumbnails"
LOG_FILE_NAME="herbarium_db.log"

# Index any newly added images in the database
./image-feeder -d image_db.db -x -i $THUMBS_DIR -m application/octet-stream
if [ $? -ne 0 ]; then
	echo -n $(date) >> $LOG_FILE_NAME
	echo " WARNING: Image database could not have been updated" >> $LOG_FILE_NAME
else
	echo -n $(date) >> $LOG_FILE_NAME
	echo " Image database updated" >> $LOG_FILE_NAME
fi
