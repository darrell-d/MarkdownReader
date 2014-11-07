<?php

class AST
{
  private $ast;

  function __construct()
  {
    $this->ast = array();
  }
  public function addNode($node)
  {
    array_push($this->ast,$node);
  }
  public function getNodes()
  {
    return $this->ast;
  }
}

?>
