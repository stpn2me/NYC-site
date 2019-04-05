<?php
function getBalance($address) {
    return file_get_contents('https://explorer.nycoin.info/ext/getbalance/'. $address);
}

echo 'Development funds: ' .  number_format(getBalance('RPx6Bfpunf3rjWtJbTegJ31aRBbTZh1VkS'), 2);

?>