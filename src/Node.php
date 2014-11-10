<?php

class Node
{

  private $node_type;
  private $payload;
  private $node_size;

  function __construct()
  {
  }
  function setNodeType($type)
  {
    $this->node_type = $type;
  }
  function setPayload($payload)
  {
    $this->payload = $payload;
  }
  function setSize($size)
  {
    $this->node_size = $size;
  }

  function getNodeType()
  {
    return $this->node_type;
  }
  function getPayload()
  {
    return $this->payload;
  }
  function getSize()
  {
    return $this->node_size();
  }
}
?>
