<?php

/**
MARKDOWN -> TEXT | SPECIAL_CHAR;
TEXT -> TEXT | SPECIAL_CHAR;
SPECIAL_CHAR-> SPECIAL_CHAR | TEXT;

SPECIAL_CHAR: #,`,*,[,>;
#: #{1+};
`:`TEXT{1+}`;
\*:*{1-2}TEXT{1+}*{1-2}
[: [TEXT](TEXT);
>:>TEXT{1+}
**/
include('Symbols.php');
include('Node.php');
include('Ast.php');

ini_set ( "xdebug.max_nesting_level" , "2147483647" );

class MarkdownReader
{


  private $output;
  private $line_count;
  private $current_line;
  private $full_text;
  private $partial_node_text;
  private $node_size;

  private $ast;
  private $current_node;

  function __construct($source)
  {
    $this->line_count = "";
    $this->line_count = 0;
    $this->current_line = "";
    $this->full_text = "";
    $this->node_size = 0;

    $this->ast = new Ast();
    $this->current_node = new Node();

    if(is_file($source))
    {
      $file = fopen($source, "r");

      while(!feof($file))
      {
        $line = fgets($file);
        $this->line_count++;
        $this->full_text .= $line;
      }

      fclose($file);
    }
    else if(is_string($source))
    {
      $this->full_text = $source;
    }
    $this->parse_r(array_reverse(str_split($this->full_text) ) );
    var_dump($this->ast);
  }

  function parse_r($text)
  {
    $text_array = $text;
    if(count($text_array) == 0)
    {
      return;
    }
    //pop off head char
    $char = $text_array[count($text_array) - 1];
    array_pop($text_array);

    $this->partial_node_text .= $this->analyze($char);

    $this->parse_r($text_array);

  }
  function analyze($char)
  {
    if(ctype_space($char))
    {
      $this->saveNode();
    }
    if(strpos($char,"#") === 0)
    {
      $this->buildNode(Symbols::HASH,$char);
      $this->saveNode();
    }
    else if(strpos($char,"`") === 0)
    {
      $this->buildNode(Symbols::BACK_TICK,$char);
      $this->saveNode();
    }
    else if(strpos($char,"*") === 0)
    {
      $this->buildNode(Symbols::ASTERIX,$char);
      $this->saveNode();
    }
    else if(strpos($char,"[") === 0)
    {
      $this->buildNode(Symbols::SQUARE_BRACE_L,$char);
      $this->saveNode();
    }
    else if(strpos($char,"]") === 0)
    {
      $this->buildNode(Symbols::SQUARE_BRACE_R,$char);
      $this->saveNode();
    }
    else if(strpos($char,"(") === 0)
    {
      $this->buildNode(Symbols::BRACE_L,$char);
      $this->saveNode();
    }
    else if(strpos($char,")") === 0)
    {
      $this->buildNode(Symbols::BRACE_R,$char);
      $this->saveNode();
    }
    else if(strpos($char,">") === 0)
    {
      $this->buildNode(Symbols::POINTY_BRACE_R,$char);
      $this->saveNode();
    }
    else
    {
      $this->buildNode(Symbols::TEXT,$char);

    }
  }

  function saveNode()
  {
    $this->current_node->setPayload($this->partial_node_text);
    $this->current_node->setSize($this->node_size);
    $this->ast->addNode($this->current_node);

    $this->current_node = new Node();
    $this->partial_node_text = "";
    $this->node_size = 0;
  }
  function buildNode($type,$char)
  {
    $this->current_node->setNodeType($type);
    $this->partial_node_text.= $char;
    $this->node_size++;
  }


  function expect($sym)
  {
    return true;
  }
  function parse($line)
  {
    $this->line_count++;
    //Parse basic # tags
    if(gettype(strpos($line,"#")) == "integer")
    {
      $line = str_replace("#","",$line);
      $line = "<h1>" . $line . "</h1>";
    }



    return $line;
  }

}
?>
