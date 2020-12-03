import os

#this file was used to rename the random images downloaded from the internet in the ../Images/
#folder to match the names stored in the database (ie: item_imagen.jpg).
#note: this probably could and should have been done in a linux command or short bash script but I
#do not know bash that well

stream = os.popen('cd ../Images && ls')
output = stream.read()
file_names = output.split("\n")
for n in range(len(file_names)):
    os.popen('mv ../Images/' + file_names[n] + ' ../Images/item_image' + str(n) + ".jpg")
