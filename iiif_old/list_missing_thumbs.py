#!/usr/bin/python


import os

source_path="/home/herbarium/source/"
thumbnail_path="/home/herbarium/thumbnails/"

for file in os.listdir(source_path):
    if file.endswith(".tif"):
	
	file_name, file_extension=os.path.splitext(file)

	if not os.path.isfile(thumbnail_path+file_name+".jp2"):
		print(file)



