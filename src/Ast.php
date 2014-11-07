<?php

class AST
{
  private $ast;

  function __construct()
  {
    $ast = array();
  }
  private function addNode($node)
  {
    array_push($this->ast,$node);
  }
}

?>
