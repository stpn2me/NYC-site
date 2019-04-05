<?php
function getBalance($address) {
    return file_get_contents('https://explorer.nycoin.info/ext/getbalance/'. $address);
}

echo 'Marketing & advertising funds: ' .  number_format(getBalance('RDFPhetnn9MSqsr6oDHNv3MRSDeNPG5CWX'), 2);
echo "<br>";
echo "<br>";
echo 'Exchange listing funds: ' .  number_format(getBalance('RJzFmnCVGJ7BXAPdEQFy6ypHhrV5z5rc5W'), 2);
echo "<br>";
echo "<br>";
echo 'Development funds: ' .  number_format(getBalance('RPx6Bfpunf3rjWtJbTegJ31aRBbTZh1VkS'), 2);

?>