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
qdbus org.kde.klauncher /KLauncher exec_blind kwrite /mnt/home/a/www/iam/app/controllers/"$CONTROLLER"controller.php
qdbus org.kde.klauncher /KLauncher exec_blind kwrite /mnt/home/a/www/iam/app/models/"$MODEL".php
qdbus org.kde.klauncher /KLauncher exec_blind kwrite /mnt/home/a/www/iam/app/views/"$CONTROLLER"/index.php

