<?php include 'env.php'; ?>
<?php
class WOL {
  var $broadcast;
  var $lastStatus;

  function WOL($localnetwork)
  {
    $this->lastStatus = 'NA';
    $this->broadcast = $localnetwork.'.255';
  }

  function getLastStatus()
  {
    return $this->lastStatus;
  }

  function wakeOnLan($mac, $socket_number=7)
  {
    $addr_byte = explode(':', $mac);
    $hw_addr = '';

    for ($a=0; $a < 6; $a++)
      $hw_addr .= chr(hexdec($addr_byte[$a]));

    $msg = chr(255).chr(255).chr(255).chr(255).chr(255).chr(255);

    for ($a = 1; $a <= 16; $a++)
      $msg .= $hw_addr;

    if (!$s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))
    {
      $this->lastStatus = 'socket_create_fail';
      return false;
    }

    if (socket_set_option($s, SOL_SOCKET, SO_BROADCAST, TRUE) < 0)
    {
      $this->lastStatus = 'setsockopt_fail';
      return false;
    }

    if (socket_sendto($s, $msg, strlen($msg), 0, $this->broadcast, $socket_number))
    {
      $this->lastStatus = 'OK';
      socket_close($s);
      return true;
    }
    else
    {
      $this->lastStatus = 'send_fail';
      return false;
    }
  }
}

$wol = new WOL($localnetwork);
?>
