script_name=accept.php

case "$2" in
    7)
        php_bin=/usr/local/php7/bin/php
    ;;
    *)
        php_bin=/usr/local/php/bin/php
    ;;
esac

startup() {
    $php_bin $script_name
}

stoped() {
    ps aux | grep $script_name | grep -v grep | awk '{print $2}' | xargs kill
}

case "$1" in
    start)
        startup 
    ;;

    stop)
        stoped
    ;;

    restart)
        stoped
        sleep 1
        startup
    ;;
esac
