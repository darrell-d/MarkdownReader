<?php

class Builder
{

  private $ast;
  private $current_node;
  private $nodes;
  function __construct()
  {

  }

  function setAST($ast)
  {
    $this->ast = $ast;
  }
  function build()
  {
    $this->nodes = array_reverse($this->ast->getNodes());
    while(!empty($this->nodes))
    {
      $this->current_node =  array_pop($this->nodes);
      echo $this->expect($this->current_node);
    }



  }

  function expect($node)
  {
    $index = count($this->nodes) -1;
    if($index > 0)
    {
        $next = $this->nodes[$index];
    }
    else
    {
        $next = "";
    }

    if($node->getNodeType() == Symbols::MARKDOWN)
    {
      if(
          $next->getNodeType() == Symbols::TEXT ||
          $next->getNodeType() == Symbols::HASH ||
          $next->getNodeType() == Symbols::ASTERIX ||
          $next->getNodeType() == Symbols::BACK_TICK ||
          $next->getNodeType() == Symbols::TEXT ||
          $next->getNodeType() == Symbols::SQUARE_BRACE_L ||
          $next->getNodeType() == Symbols::BRACE_L ||
          $next->getNodeType() == Symbols::POINT_BRACE_R
        )
        {
          return true;
        }

        return false;
    }
    if($node->getNodeType() == Symbols::HASH )
    {
      if($next->getNodeType() == Symbols::TEXT || $next->getNodeType() == Symbols::HASH)
      {
        return true;
      }

      return false;
    }
  }

}
?>
