<?php
function getBalance($address) {
    return file_get_contents('https://explorer.nycoin.info/ext/getbalance/'. $address);
}

echo number_format(getBalance('RJzFmnCVGJ7BXAPdEQFy6ypHhrV5z5rc5W') / 10000000 * 100);

?>