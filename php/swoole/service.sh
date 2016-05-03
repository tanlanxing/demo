case "$2" in
    7)
        php_bin=/usr/local/php7/bin/php
    ;;
    *)
        php_bin=/usr/local/php/bin/php
    ;;
esac

case "$1" in
    start)
        $php_bin reactor.php
    ;;

    stop)
        ps aux | grep reactor | grep -v grep | awk '{print $2}' | xargs kill
    ;;

    restart)
        ps aux | grep reactor | grep -v grep | awk '{print $2}' | xargs kill
        $php_bin reactor.php
    ;;
esac
