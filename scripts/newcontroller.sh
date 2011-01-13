if [ "$1" = "" ]
then
	echo "You must enter a name in singular form"
	exit 1
else
	MODEL=$1
fi

if [ "$2" = "" ]
then
	echo "You must enter a name in plural form"
	exit 1
else
	CONTROLLER=$2
fi

cd /mnt/home/a/www/iam/app/
cp controllers/shellscontroller.php controllers/"$CONTROLLER"controller.php
gvim controllers/"$CONTROLLER"controller.php

cp models/shell.php models/"$MODEL".php
gvim models/"$MODEL".php

mkdir views/"$CONTROLLER"
cp views/shells/* views/"$CONTROLLER"/ -r
gvim views/"$CONTROLLER"/index.php

