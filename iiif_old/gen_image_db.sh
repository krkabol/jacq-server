#!/usr/bin/bash

BASE_DIR="/home/herbarium"
SOURCE_IMAGES="/home/herbarium/source"
THUMBS_DIR="/home/herbarium/thumbnails"
LOG_FILE_NAME="herbarium_db.log"
IMAGEMAGICK_LOG_FILE_NAME="imagemagick.log"

cd $BASE_DIR || return 1
rm "$LOG_FILE_NAME"

# Make sure we have a valid Kerberos ticket
##kinit -k -t /home/herbarium/krb5.keytab.herbarium nfs/vojtisel@EINFRA
##kinit -k -t /home/herbarium/krb5.keytab.herbarium nfs/komanek@EINFRA
kinit -k -t /home/herbarium/krb5.keytab.herbarium nfs/backup_cit@EINFRA
if [ $? -ne 0 ]; then
	echo -n $(date) >> $LOG_FILE_NAME
	echo " ERROR: Cannot renew Kerberos ticket, source files might not be available. Exiting..." >> $LOG_FILE_NAME
	exit 1
fi

for f in $SOURCE_IMAGES/*.tif; do
	FILE_BASENAME=$(basename $f)
	OUTPUT_FILE="$THUMBS_DIR/${FILE_BASENAME%.tif}.jp2"
	if [ ! -f "$OUTPUT_FILE" ]; then
		#image_to_j2k -i "$f" -o "$OUTPUT_FILE" -r 15
		#convert "$f" -quality 46 "$OUTPUT_FILE"
		echo "$OUTPUT_FILE" &>> $IMAGEMAGICK_LOG_FILE_NAME
		convert "$f[0]" -quality 46 "$OUTPUT_FILE"
		if [ $? -ne 0 ]; then
			echo -n $(date) >> $LOG_FILE_NAME
			echo " WARNING: Cannot convert file $f!" >> $LOG_FILE_NAME
		else
			echo -n $(date) >> $LOG_FILE_NAME
			echo " File $f converted" >> $LOG_FILE_NAME
		fi
	fi
done

echo -n $(date) >> $LOG_FILE_NAME
echo " Image conversion complete" >> $LOG_FILE_NAME

# Index any newly added images in the database
./image-feeder -d image_db.db -x -i $THUMBS_DIR -m application/octet-stream
if [ $? -ne 0 ]; then
	echo -n $(date) >> $LOG_FILE_NAME
	echo " WARNING: Image database could not have been updated" >> $LOG_FILE_NAME
else
	echo -n $(date) >> $LOG_FILE_NAME
	echo " Image database updated" >> $LOG_FILE_NAME
fi
